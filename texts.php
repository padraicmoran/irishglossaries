<?php
include ("includes/cms.php");

$xslDoc = new DOMDocument();
$xslDoc->load("./includes/entry.xsl");

$versionID = initVars('versionID', 0);
$readingID = initVars('readingID', 0);

if ($versionID == 0) {
   templateHeader('', 'Texts');
   templateMid('');

   ?>
   
<h1>Texts</h1>   

<p>The database contains five texts, with 18 manuscript versions in total. (For each text, the longest manuscript version is listed first.)
</p>

<p>PDF versions are available in a <a target="blank" href="https://zenodo.org/record/8262288">Zenodo archive</a>. 
XML versions are available on <a target="blank" href="https://zenodo.org/record/8262354">Zenodo</a> as well as on our 
<a href="downloads.php">Downloads</a> page, where you 
can also find transcription notes.
</p> 

<h2><i>Sanas Cormaic</i> (Cormac’s Glossary)</h2>

<p>This glossary is represented in shorter version (c. 700 entries) and a longer version (c. 1300 entries).</p>

<p>Manuscripts containing the longer version are closely related, and therefore at present headwords only are given for H¹a, H¹b and K (marked * below):</p>

<dl class="row">
<dt class="col-2 col-md-1"><b><a href="./texts.php?versionID=9">Y</a></b></dt>
<dd class="col-10 col-md-11">Yellow Book of Lecan (TCD, <span class="sc">ms</span> 1318 (H.2.16)), pp. 255a–283a</dd>
<dt class="col-2 col-md-1"><a href="./texts.php?versionID=15">H¹a</a>*</dt>
<dd class="col-10 col-md-11">Dublin, Trinity College, <span class="sc">ms</span> 1317 (H.2.15b), pp. 13–39 [89–115]</dd>
<dt class="col-2 col-md-1"><a href="./texts.php?versionID=16">H¹b</a>*</dt>
<dd class="col-10 col-md-11">Dublin, Trinity College, <span class="sc">ms</span> 1317 (H.2.15b), pp. 77–102 [153–178]</dd>
<dt class="col-2 col-md-1"><a href="./texts.php?versionID=18">K</a>*</dt>
<dd class="col-10 col-md-11">University College Dublin, Franciscan (Killiney) <span class="sc">ms</span> A 12, pp. 1–40</dd>
</dl>

<p>Shorter version:</p>

<dl class="row">
<dt class="col-2 col-md-1"><b><a href="./texts.php?versionID=1">B</a></b></dt>
<dd class="col-10 col-md-11">Leabhar Breac (RIA, <span class="sc">ms</span> 23 P 16), pp. 263–72</dd>
<dt class="col-2 col-md-1"><a href="./texts.php?versionID=7">L</a></dt>
<dd class="col-10 col-md-11">Book of Leinster (TCD, <span class="sc">ms</span> 1339 (H.2.18), p. 179 (frag. <i>T–U</i>)</dd>
<dt class="col-2 col-md-1"><a href="./texts.php?versionID=11">La</a></dt>
<dd class="col-10 col-md-11">Oxford, Bodleian Library, <span class="sc">ms</span> Laud 610, fols. 79r–80v, 83r–86r (frag. <i>I–T</i>)</dd>
<dt class="col-2 col-md-1"><a href="./texts.php?versionID=8">M</a></dt>
<dd class="col-10 col-md-11">Book of Uí Maine (Dublin, RIA, <span class="sc">ms</span> D ii 1), fols. 177r–84ra [119r–126ra] (<i>A–T</i>)</dd>
</dl>

<h2><i>Dúil Dromma Cetta</i> (D)</h2>

<dl class="row">
<dt class="col-2 col-md-1"><b><a href="./texts.php?versionID=2">D¹</a></b></dt>
<dd class="col-10 col-md-11">Dublin, Trinity College, <span class="sc">ms</span> 1337 (H.3.18), pp. 63–75</dd>
<dt class="col-2 col-md-1"><a href="./texts.php?versionID=3">D²</a></dt>
<dd class="col-10 col-md-11">Dublin, Trinity College, <span class="sc">ms</span> 1337 (H.3.18), pp. 633a–638b</dd>
<dt class="col-2 col-md-1"><a href="./texts.php?versionID=4">D³</a></dt>
<dd class="col-10 col-md-11">London, British Library, <span class="sc">ms</span> Egerton 1782, fol. 15 (frag. <i>D–M</i>)</dd>
<dt class="col-2 col-md-1"><a href="./texts.php?versionID=5">D⁴</a></dt>
<dd class="col-10 col-md-11">Dublin, Trinity College, <span class="sc">ms</span> 1287 (H.1.13), pp. 361–2 (frag. <i>I–M</i>)</dd>
</dl>

<h2>O’Mulconry’s Glossary (OM)</h2>
<dl class="row">
<dt class="col-2 col-md-1"><b><a href="./texts.php?versionID=10">OM¹</a></b></dt>
<dd class="col-10 col-md-11">Yellow Book of Lecan (TCD, <span class="sc">ms</span> 1318 (H.2.16)), cols. 88–122</dd>
<dt class="col-2 col-md-1"><a href="./texts.php?versionID=12">OM²</a></dt>
<dd class="col-10 col-md-11">Dublin, Trinity College, <span class="sc">ms</span> 1317 (H.2.15b), pp. 41–2 [118–9] (frag. <i>E–G</i>)</dd>
<dt class="col-2 col-md-1"><a href="./texts.php?versionID=13">OM³</a></dt>
<dd class="col-10 col-md-11">Dublin, Trinity College, <span class="sc">ms</span> 1317 (H.2.15b), pp. 102–4 [178–80] (frag. <i>A–C</i>)</dd>
<dt class="col-2 col-md-1"><a href="./texts.php?versionID=14">OM⁴</a></dt>
<dd class="col-10 col-md-11">University College Dublin, Franciscan (Killiney) <span class="sc">ms</span> A 12, pp. 41–42 (frag. <i>A</i> only)</dd>
</dl>

<h2><i>Loman</i> and <i>Irsan</i></h2>

<p><i>Loman</i> is an independent copy of the latter part of the additional entries (YAdd) in the longer version of <i>Sanas Cormaic</i>. <i>Irsan</i> is a distinct text more loosely related to OM.</p>

<dl class="row">
<dt class="col-2 col-md-1"><b><i><a href="./texts.php?versionID=6">Loman</a></i></b></dt>
<dd class="col-10 col-md-11">Dublin, Trinity College, <span class="sc">ms</span> 1337 (H.3.18), pp. 76a–79c (<i>L–U</i>)</dd>
<dt class="col-2 col-md-1"><b><i><a href="./texts.php?versionID=17">Irsan</a></i></b></dt>
<dd class="col-10 col-md-11">Dublin, Trinity College, <span class="sc">ms</span> 1337 (H.3.18), pp. 80a–83b (<i>A–S</i>)</dd>
</dl>

   <?php
}
else {
   
   // get version (glossary) details
   $sql = "Select g.Name, v.Code, v.Desc, v.MS_Source From version v Inner Join glossary g On v.glossaryID = g.recordID Where v.recordID = " . $versionID;
   $title = mysqli_query($link, $sql);
   if ($title) {
   	$row = mysqli_fetch_array($title);
      $gName = $row['Name'];
      $gCode = $row['Code'];
      $gDesc = $row['Desc'];
      $msSource = $row['MS_Source'];
   }
   else {
      $gName = '';
      $gCode = '';
      $gDesc = '';
      $msSource = '';
   }
   if ($gName == $gCode) $pageHeading = $gName;
   else $pageHeading = "$gName, version $gCode";

   templateHeader('', 'Text ' . $gCode);
   
   // get and display readings
   if ($gName == '') {
      // some error: version not recognised
      templateMid('');
      print "<h1>Texts</h1>";
      print '<p><a href="texts.php">Select a version...</a></p>';
   }
   else {
      // get total readings
      $sql = "Select Count(r.RecordID) From reading r Where r.VersionID = $versionID ";
      $tmp = mysqli_query($link, $sql);
      $row = mysqli_fetch_array($tmp);
      $maxRow = $row[0];
      
      // if entry is selected, find page of relevant entry
      if ($readingID > 0) {
         $sql = "Select r.Seq From reading r Where r.VersionID = $versionID And r.RecordID = $readingID ";
         $tmp = mysqli_query($link, $sql);
         $row = mysqli_fetch_array($tmp);
         $page = intval(($row[0] + 1) / $perPage);
         if (($row[0] + 1) % $perPage > 0) $page ++;
      }
   
      // paging details
      if ($perPage == 0) $perPage = $maxRow; // start at 1
      $maxPage = intval($maxRow / $perPage);
      if ($maxRow % $perPage > 0) $maxPage ++;
      if ($page > $maxPage) $page = $maxPage;
      
      $startRow = (($page - 1) * $perPage);  // start at 0
      $endRow = $startRow + $perPage - 1;
      if ($endRow >= $maxRow) $endRow = $maxRow - 1;
      
      // get readings
      $sql = "Select r.DIL, r.RecordID, r.Headword, r.Seq, r.Ref, r.PrintedRef, r.EntryText, r.Stratum, r.MS_Ref
         From reading r 
         Where r.VersionID = $versionID  
         Order By r.Seq
         Limit $startRow, $perPage";
         
      $readings = mysqli_query($link, $sql);
      if ($readings) {
   
         // set concordance parameters
         if ($versionID == 1 || $versionID == 7 || $versionID == 8 || $versionID == 9 || $versionID == 11 || $versionID == 15 || $versionID == 16 || $versionID == 18 || $versionID == 6) $concordanceParams = '&amp;cpFamily=sc';     // Cormac  
         elseif ($versionID == 10 || ($versionID >= 12 && $versionID <= 14) || $versionID == 17) $concordanceParams = '&amp;cpFamily=om';      // OM
         elseif ($versionID >= 2 && $versionID <= 5) $concordanceParams = '&amp;cpFamily=ddc';    // DDC
         else $concordanceParams = '';
   
         // sidebar
  /*
         print '<h3 style="display: inline; padding-right: 20px; ">Search</h3>';
         print '<form action="./search.php" method="get">';
         print '<input type="radio" class="form-check-input" name="sVer_' . $versionID . '" id="thisVersion" value="1" checked="checked" /> <label for="thisVersion">' . $gCode . ' only</label><br/>';
         print '<input type="radio" class="form-check-input" name="sVer_' . $versionID . '" id="allVersions" value="" /> <label for="allVersions">all texts</label>';
         print '<input type="text" class="form-control form-control-sm" name="sText" />';
         print '<input  class="btn btn-secondary btn-sm my-1" type="submit" value="go" />';
         print '</form>';

         listVersions();
*/
         templateMid('');

         // page heading
         print "<h1>$pageHeading</h1>";
  
  			// version info
         print '<p class="mb-5">Version ' . $gCode . ' = ' . $gDesc . '.</p>';

  
         msNotes();         
         pageNav();

			print '<div class="table-responsive">';
         print '<table class="table table-hover" width="100%">';
         print '<tr>';
         print '<thead>';
         print '<th>Ref</th>';
         print '<th>Text</th>';
         print '<th class="text-end">§</th>';
         print '<th class="text-end small"><a href="./abbr.php" data-bs-toggle="tooltip" title="See abbreviations page for details of print references.">Print ref</a></th>';
         print '<th class="text-center small d-print-none">MS</th>';
         print '<th>&nbsp;</th>';
         print '</thead>';
         print '</tr>';

         while ($row = mysqli_fetch_array($readings)) {
            $thisReadingID = $row['RecordID'];
            if ($thisReadingID == $readingID) $css = ' highlight';
            else $css = '';
            print '<tr valign="top" id="r' . $thisReadingID . '">';
            print '<td class="small text-secondary ' . $css . '" nowrap="nowrap">' . $row['Ref'] . '</td>' . "\r\n";

            print '<td class="' . $css . ' entry">';
            print '<a name="' . $thisReadingID . '"></a>';
            print "\r\n\r\n" . formatEntry($row['EntryText'], $row['DIL']) . "\r\n";
            print '</td>' . "\r\n";

            print '<td class="small text-secondary ' . $css . ' ">' . $row['Stratum'] . '</td>';
            print '<td class="small text-secondary ' . $css . ' text-end">' . editLink($row['RecordID'], checkBlank($row['PrintedRef'], 'na')) . '</td>';
            print '<td class="small text-secondary ' . $css . '  text-end" nowrap="nowrap">' . msLink($versionID, $thisReadingID, $msSource, $row['MS_Ref']) .  '</td>';
            print '<td class="small text-secondary ' . $css . ' text-end"><a class="btn btn-outline-secondary btn-sm d-print-none" href="./concordances.php?main=' . $versionID . $concordanceParams . '&amp;display=fulltext&amp;readingID=' . $thisReadingID . '#' . $thisReadingID . '" title="Show concordance for this entry within this family of texts." data-bs-toggle="tooltip">concord.</a></td>';
            print '</tr>';
         }
         print '</table>';
			print '</div>';
         pageNav();
      }   
   }
}

templateEnd('');

function msNotes() {
    global $versionID, $msSource, $projectAccess;
    print '<p>';

    // notes on source of MS images
/*
    if ($msSource == 'na') print 'Images are not available for this manuscript. ';
    elseif (! $projectAccess) {
       if ($msSource == 'isos') print 'Manuscript images are available from the <a href="http://www.isos.dias.ie/" target="_blank">Irish Script on Screen</a> (ISOS) website. Clicking on the manuscript image gives access to higher quality images for those already registered with ISOS. ';
       elseif ($msSource == 'oxford') print 'Manuscript images are available from the <a href="http://www.image.ox.ac.uk/" target="_blank">Early Manuscripts at Oxford University</a> website. ';
       elseif ($msSource == 'scan') print 'Images are not currently available for this manuscript. ';
    }
*/
    // note on abridged transcriptions
    if ($versionID == 15 || $versionID == 16 || $versionID == 18) print 'This transcription samples headwords only, being closely related to <a href="texts.php?versionID=9">Y</a>. ';
    print '</p>';
}

?>
