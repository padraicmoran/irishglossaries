<?php

// performance testing
$start_timestamp = microtime(true);

include ("includes/cms.php");

$main = initVars('main', '');
$display = initVars('display', 'fulltext');
$cpFamily = initVars('cpFamily', '');
$readingID = initVars('readingID', 0);

$xslDoc = new DOMDocument();
$xslDoc->load("./includes/entry.xsl");

// get details for text versions
$compareVersions = array();
$versionData = array();
$sql = "
   Select 
      g.RecordID As g_RecordID, g.Name, g.Code As g_Code, v.RecordID As v_RecordID, v.DisplayOrder, v.Code As v_Code, v.ShortDesc 
   From 
      version v Inner Join 
      glossary g On v.GlossaryID = g.RecordID 
   Order By 
      g.RecordID, v.DisplayOrder, v.Code ";
$versions = mysqli_query($link, $sql);
while ($row = mysqli_fetch_array($versions)) {
   // compile list of texts to compare (from query string)
   $tmp = 'cp' . $row['v_RecordID'];
   if (isset($_GET[$tmp])) {
      $compareVersions[] = $row['v_RecordID'];
   }
   $versionData[$row['v_RecordID']]['code'] = $row['v_Code'];
   $versionData[$row['v_RecordID']]['desc'] = $row['ShortDesc'];
}
// short-cut: specify families of text to compare  
if ($cpFamily == 'sc') $compareVersions = array(1, 15, 16, 18, 7, 11, 8, 9, 6);  // incl. Loman
elseif ($cpFamily == 'om') $compareVersions = array(10, 12, 13, 14, 17);         // incl. Irsan
elseif ($cpFamily == 'ddc') $compareVersions = array(2, 3, 4, 5);



// build version selector form
if ($main == '' || count($compareVersions) == 0 || (count($compareVersions) == 1 && $compareVersions[0] == $main) || isset($_GET['changeForm'])) {
   templateHeader('', 'Concordances');
   templateMid('');

   ?>

<h1>Concordances</h1>
   
<p>Concordances allow you to compare the structure of different texts, whether distinct glossaries or different manuscripts of a single glossary. You can also see the full text of entries to compare readings.</p>

<p>These concordances are generated automatically based on the first <a href="http://www.dil.ie/" target="_blank"><i>DIL</i></a> headword associated with each entry. Accordingly there are some limitations: some entries may not appear as expected where there are ambiguous headwords, conflations of several entries or texts with the same <i>DIL</i> headword occuring more than once. </p>

   <?php

   if ($main == '' && count($compareVersions) != 0) print '<div class="error"><b>You must select a base version (in the left-hand column) with which to compare other versions.</b></div>';
   elseif ($main != '' && count($compareVersions) == 0 || (count($compareVersions) == 1 && $compareVersions[0] == $main)) print '<div class="error"><b>You must select at least one other version from the right-hand column to compare with the base text.</b></div>';
   else print '<p><b>Select a base text and at least one other text to compare with it.</b></p>';

   print '<form action="concordances.php" method="get">';
   print '<input type="hidden" name="readingID" value="' . $readingID . '"/>';
	print '<div class="table-responsive">';
   print '<table class="table table-hover" cellpadding="0" cellspacing="0" border="0">';
   print '<tr>';
   print '<th>Base</th>';
   print '<th>Glossary</th>';
   print '<th>Version</th>';
   print '<th>Compare</th>';
   print '</tr>';
   $cnt = 0;
   mysqli_data_seek($versions, 0);
   while ($row = mysqli_fetch_array($versions)) {
      print '<tr>';
      print '<td><input type="radio" class="form-check-input" name="main" value="' . $row['v_RecordID'] . '"';
      if ($main == $row['v_RecordID']) print ' checked="checked"';
      print ' /></td>';
      print '<td>' . $row['Name'] . '</td>';
      print '<td><b>' . $row['v_Code'] . '</b> = ' . $row['ShortDesc'] . '</td>';
      print '<td><input type="checkbox" class="form-check-input" name="cp' . $row['v_RecordID'] . '" value="1"';
      if (in_array($row['v_RecordID'], $compareVersions)) print ' checked="checked"';
      print ' /></td>';
      print '</tr>';
      $cnt ++;
   }
   print '<tr><td colspan="4" align="center"><br/><input type="submit" class="btn btn-primary" value="Generate concordances" /></td></tr>';
   print '</table>';
	print '</div>';
   print '</form>'; 
}

else {
   // generate concordance
   templateHeader('wide', 'Concordances');
   templateMid('wide');
   print '<h1>Concordances</h1>';

   $mainVersion = $main;
   $concordances = array();

   // get total readings for main version
   $sql = "Select Count(r.RecordID) From reading r Where r.VersionID = $mainVersion ";
   $tmp = mysqli_query($link, $sql);
   $row = mysqli_fetch_array($tmp);
   $maxRow = $row[0];

   // if readingID is set, find page of relevant entry
   if ($readingID > 0) {
      $sql = "Select r.Seq From reading r Where r.VersionID = $mainVersion And r.RecordID = $readingID ";
      $tmp = mysqli_query($link, $sql);
      $row = mysqli_fetch_array($tmp);
      $page = intval(($row[0] + 1) / $perPage);
      if (($row[0] + 1) % $perPage > 0) $page ++;
   }

   // paging
   if ($perPage == 0) $perPage = $maxRow; // start at 1
   $maxPage = intval($maxRow / $perPage);
   if ($maxRow % $perPage > 0) $maxPage ++;
   if ($page > $maxPage) $page = $maxPage;
   
   $startRow = (($page - 1) * $perPage);  // start at 0
   $endRow = $startRow + $perPage - 1;
   if ($endRow >= $maxRow) $endRow = $maxRow - 1;

   // get details for main version
   $sql = "Select r.RecordID, r.Headword, r.Seq, r.Ref, r.EntryText, r.Stratum, r.DIL
		From reading r
      Where r.VersionID = $mainVersion 
      Order By r.Seq 
      Limit $startRow, $perPage";
   $mainReadings = mysqli_query($link, $sql);

   if (! $mainReadings) print '<p>No entries found.</p>';
   else {
      // populate concordances array: main entries first
      $mainStartSeq = 0;
      while ($rowMR = mysqli_fetch_array($mainReadings)) {
      	if ($mainStartSeq == 0) $mainStartSeq = $rowMR['Seq'];
      
         $concordances[$rowMR['RecordID']]['readingID'] = $rowMR['RecordID'];
         $concordances[$rowMR['RecordID']]['ref'] = $rowMR['Ref'];
         $concordances[$rowMR['RecordID']]['link'] = '<b><a title="Show this entry in this text\'s transcription. " data-bs-toggle="tooltip" href="texts.php?versionID=' . $main . '&amp;readingID=' . $rowMR['RecordID'] . '#' . $rowMR['RecordID'] . '">' . $rowMR['Ref'] . '</a></b> ' . $rowMR['Stratum'];
         $concordances[$rowMR['RecordID']]['dil'] = $rowMR['DIL'];
         if ($display == 'headwords') $concordances[$rowMR['RecordID']]['text'] = $rowMR['Headword'];
         else $concordances[$rowMR['RecordID']]['text'] = $rowMR['EntryText'];

      }
      mysqli_data_seek($mainReadings, mysqli_num_rows($mainReadings) - 1);
      $tmp = mysqli_fetch_array($mainReadings);
      $mainEndSeq = $tmp['Seq'];
      
      // add entries for compare versions; get totals
      foreach ($compareVersions as $compareVersion) {
         if ($compareVersion != $main) {
            $sql = "Select r.RecordID As r_RecordID, c.RecordID As c_RecordID, c.Seq, c.Ref, c.Headword, c.EntryText, c.Stratum, c.DIL
               From (reading r Left Outer Join 
                  reading c On c.DIL = r.DIL) 
               Where r.VersionID = $mainVersion And 
                  c.VersionID = $compareVersion And 
                  c.VersionID <> $mainVersion And
                  r.Seq >= " . $mainStartSeq . " And
                  r.Seq <= " . $mainEndSeq . "
               Order By r.Seq
               ";
               
            $compareReadings = mysqli_query($link, $sql);
            while ($row = mysqli_fetch_array($compareReadings)) {
               $concordances[$row['r_RecordID']]['readingID' . $compareVersion] = $row['c_RecordID'];
               $concordances[$row['r_RecordID']]['ref' . $compareVersion] = $row['Ref'];
               $concordances[$row['r_RecordID']]['link' . $compareVersion] = '<a title="Show this entry in this text\'s transcription. " data-bs-toggle="tooltip" href="texts.php?versionID=' . $compareVersion . '&amp;readingID=' . $row['c_RecordID'] . '#' . $row['c_RecordID'] . '">' . $row['Ref'] . '</a> ' . $row['Stratum'];
               $concordances[$row['r_RecordID']]['dil' . $compareVersion] = $row['DIL'];
               if ($display == 'headwords') $concordances[$row['r_RecordID']]['text' . $compareVersion] = $row['Headword'];
               else $concordances[$row['r_RecordID']]['text' . $compareVersion] = $row['EntryText'];
            }
         }
      }

      print '<p>';
      // list families if selected
      if ($cpFamily == 'sc') print 'Versions of <i>Sanas Cormaic</i>, with <i>Loman</i>. ';
      elseif ($cpFamily == 'om') print 'Versions of O\'Mulconry’s Glossary, with <i>Irsan</i>. ';
      elseif ($cpFamily == 'ddc') print 'Versions of <i>Dúil Dromma Cetta</i>. ';
      print 'Note that headwords only are currently available for transcriptions H¹a, H¹b, K: see Y (closely related) for text in those cases. ';
      // full text or headwords only
      print '<br/>';
      if ($display == 'headwords') print '<a class="btn btn-outline-secondary btn-sm" href="concordances.php?' . changeKey('display', 'fulltext', '', '') . '">Show full text of entries</a> ';      
      else print '<a class="btn btn-outline-secondary btn-sm" href="concordances.php?' . changeKey('display', 'headwords', '', '') . '">Show headwords only</a> ';

		// disabled due to threat of XSS scripting
      // print '<a href="' . htmlentities($_SERVER['REQUEST_URI']) . '&amp;changeForm=1">Change selection of texts</a>→';
      print '</p>';

      // paging link
      pageNav();

      // table headers
		print '<div class="table-responsive">';
      print '<table class="table table-hover">';
      print '<tr>';
      print '<th colspan="2"><b><abbr title="' . $versionData[$compareVersion]['desc'] . '">' . $versionData[$mainVersion]['code'] . '</abbr> (main text)</b></th>';
      foreach ($compareVersions as $compareVersion) {
         if ($compareVersion != $mainVersion)print '<th colspan="2"><abbr title="' . $versionData[$compareVersion]['desc'] . '">' . $versionData[$compareVersion]['code'] . '</abbr></th>';
      }
      print '</tr>';

      // entries
      $row = 0;
      foreach ($concordances as $key => $value) {
         if ($concordances[$key]['readingID'] == $readingID) $css = ' highlight';
         else $css = '';

         print '<tr valign="top" id="r' . $concordances[$key]['ref'] . '">';
         // main version
         print '<td class="' . $css . ' small" nowrap="nowrap"><a name="' . $concordances[$key]['readingID'] . '"></a>' . $concordances[$key]['link'] . '</td>';
         print '<td class="' . $css . ' entry">' . formatEntry($concordances[$key]['text'], $concordances[$key]['dil'], '') . '</td>';
         // compare versions
         foreach ($compareVersions as $compareVersion) {
            if ($compareVersion != $mainVersion) {
               if (isset($concordances[$key]['ref' . $compareVersion])) print '<td class="' . $css . ' small" nowrap="nowrap">' . $concordances[$key]['link' . $compareVersion] . '</td>';
               else print '<td class="' . $css . ' small text-secondary" nowrap="nowrap">[' . $versionData[$compareVersion]['code'] . ']</td>';
               if (isset($concordances[$key]['text' . $compareVersion])) print '<td class="' . $css . ' entry">' . formatEntry($concordances[$key]['text' . $compareVersion], $concordances[$key]['dil' . $compareVersion], '') . '</td>';
               else print '<td class="' . $css . '"> </td>';
            }
			}
         print '</tr>';
         $row ++;
      }
      print '</table>';
      print '</div>';

      // paging link
      pageNav();
   }
}

// performance testing
$end_timestamp = microtime(true);
$duration = $end_timestamp - $start_timestamp;
print '<!-- time: ' . $duration . ' -->';

templateEnd('');
?>
