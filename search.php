<?php
include ("includes/cms.php");
$testing = false;

$xslDoc = new DOMDocument();
$xslDoc->load("./includes/entry.xsl");

$sType = initVars('sType', 'vars');
$sAdv = initVars('adv', '');
$sqlVersions = '';
$sqlLangs = '';
foreach ($_GET as $key => $val) {
   if (substr($key, 0, 5) == 'sVer_' && $val == '1') $sqlVersions .= substr($key, 5) . ', ';
   if (substr($key, 0, 6) == 'sLang_' && $val == '1') $sqlLangs .= substr($key, 6) . '|';
}
// trim version, language lists if necessary
if (strlen($sqlVersions) > 2) $sqlVersions = substr($sqlVersions, 0, -2);
if (strlen($sqlLangs) > 1) $sqlLangs = substr($sqlLangs, 0, -1);

// check whether to display advanced options
if ($sAdv != '' || $sqlVersions != '' || $sqlLangs != '') $sAdv = true;

templateHeader('', 'Search');
templateMid('');

// search form
   ?>


<h1>Search</h1>

<form name="search" action="search.php" method="get" class="advSearch">   
   
<div class="row">

	<label class="col-3" for="sText" class="box">Find entries containing:</label>
	<div class="col-7">

<input type="text" class="form-control" name="sText" id="sText" value="<?php print $search; ?>" />
<span class="small">(use * as a wildcard character, e.g. <i>*chtaire</i> matches <i>rechtaire</i>, <i>techtaire</i>)</span>

<p class="mt-4">
<?php
writeRadio('sType', 'exact', 'Exact match', $sType);
writeRadio('sType', 'vars', 'Common spelling variations ', $sType);
?>
&nbsp; <span class="small"><a href="database.php#search-notes">Spelling variations explained</a>→</span>
</p>

<p><a class="btn btn-secondary btn-sm" data-bs-toggle="collapse" href="#advOptions" role="button">Show/hide more search options</a></p>

	</div>
</div> 


<!-- advanced options -->

<div id="advOptions" class="collapse <?php if ($sAdv == '1') print 'show'; ?>">
<p class="mt-4">You may optionally limit your results by version: &nbsp;
<a href="#" class="btn btn-outline-secondary btn-sm" onclick="search_longestVersions(); return false; ">Longest versions only</a>
<a href="#" class="btn btn-outline-secondary btn-sm" onclick="search_clearAllVersions(); return false; ">Remove limits</a>
</p>

	<div class="row">
		<div class="col-3"></div>
		<div class="col-7">

<table class="table">
<tr>
<th><i>Sanas Cormaic</i> (longer version)</th>
<td><?php writeCheckbox('sVer_9', '1', '<b>Y</b>', '_GET'); ?></td>
<td><?php writeCheckbox('sVer_15', '1', 'H¹a', '_GET'); ?></td>
<td><?php writeCheckbox('sVer_16', '1', 'H¹b', '_GET'); ?></td>
<td><?php writeCheckbox('sVer_18', '1', 'K', '_GET'); ?></td>
</tr>

<tr>
<th><i>Sanas Cormaic</i> (shorter version)</th>
<td><?php writeCheckbox('sVer_1', '1', '<b>B</b>', '_GET'); ?></td>
<td><?php writeCheckbox('sVer_7', '1', 'L', '_GET'); ?></td>
<td><?php writeCheckbox('sVer_11', '1', 'La', '_GET'); ?></td>
<td><?php writeCheckbox('sVer_8', '1', 'M', '_GET'); ?></td>
</tr>

<tr>
<th><i>Dúil Dromma Cetta</i></th>
<td><?php writeCheckbox('sVer_2', '1', '<b>D¹</b>', '_GET'); ?></td>
<td><?php writeCheckbox('sVer_3', '1', 'D²', '_GET'); ?></td>
<td><?php writeCheckbox('sVer_4', '1', 'D³', '_GET'); ?></td>
<td><?php writeCheckbox('sVer_5', '1', 'D⁴', '_GET'); ?></td>
</tr>

<tr>
<th>O’Mulconry’s Glossary</th>
<td><?php writeCheckbox('sVer_10', '1', '<b>OM¹</b>', '_GET'); ?></td>
<td><?php writeCheckbox('sVer_12', '1', 'OM²', '_GET'); ?></td>
<td><?php writeCheckbox('sVer_13', '1', 'OM³', '_GET'); ?></td>
<td><?php writeCheckbox('sVer_14', '1', 'OM⁴', '_GET'); ?></td>
</tr>

<tr>
<th><i>Loman/Irsan</i></th>
<td colspan="2"><?php writeCheckbox('sVer_6', '1', '<b><i>Loman</i></b>', '_GET'); ?></td>
<td colspan="2"><?php writeCheckbox('sVer_17', '1', '<b><i>Irsan</i></b>', '_GET'); ?></td>
</tr>
</table>


		</div>
	</div>


<p>Limit search to specific languages (currently for Y, OM¹, D¹<!--, <i>Irsan</i>, <i>Loman</i>--> only): &nbsp;
<a href="#" class="btn btn-outline-secondary btn-sm" onclick="search_clearAllLangs(); return false; ">Remove limits</a>
</p>

	<div class="row">
		<div class="col-3"></div>
		<div class="col-7 pb-4">
	
<?php 
writeCheckbox('sLang_gle', '1', 'Irish ', '_GET'); 
writeCheckbox('sLang_lat', '1', 'Latin ', '_GET');
writeCheckbox('sLang_grc', '1', 'Greek ', '_GET');
writeCheckbox('sLang_heb', '1', 'Hebrew ', '_GET');
writeCheckbox('sLang_cym', '1', 'Welsh/Brittonic ', '_GET');
writeCheckbox('sLang_non', '1', 'Norse ', '_GET');
writeCheckbox('sLang_eng', '1', 'English ', '_GET');
writeCheckbox('sLang_qpi', '1', 'Pictish ', '_GET');
?>

		</div>
	</div>
</div>

<div class="row">
	<div class="col-3"></div>
	<div class="col-7	">

<input type="submit" class="btn btn-primary" value="Search"/>

	</div>
</div>

</form>   
   
   
   <?php
if ($search != '') {
   print '<h2>Search results</h2>';

   // build SQL
   $searchString = $search;
   $searchString = mb_strtolower($searchString);

   if ($sType == 'vars') {
      // allow for variations 
     
      // create search string
      // TO DO check compatibility of ALL characters
      $find = array(                        // explanation of rules; * = 'and vice versa'
         '/\*/ui',                          // wildcard *
         '/c{1,2}($|[^h])/ui',              // 1. V + c (not + h) matches V + c or g
         '/p{1,2}($|[^h])/ui',              // 2. V + p (not + h) matches V + p or b
         '/t{1,2}($|[^h])/ui',              // 3. V + t (not + h) matches V + t or d
         '/g{1,2}($|[^h])/ui',              // 4. V + g (not + h) matches V + c, ch, g, gh *
         '/b{1,2}($|[^h])/ui',              // 5. V + b (not + h) matches V + p, b, bh *
         '/d{1,2}($|[^h])/ui',              // 6. V + d (not + h) matches V + t, th, d, dh *
         '/gh/ui',                          // 4a. V + gh matches V + g *
         '/bh/ui',                          // 5a. V + bh matches V + b *
         '/dh/ui',                          // 6a. V + dh matches V + d *
         '/mh?/ui',                         // 7. V + m matches V + mh *
         '/(f|ph)?([aeiouáéíóúæ])/ui',      // 7a. V may be V or fV or fhV
         '/(nd|nn)/ui',                     // 9. nd matches nn *  
         '/(mb|mm|m)/ui',                   // 10. mb matches mm matches m *
         '/^h?([aeiouáéíóúæ])/ui',          // 11. initial h + V matches V *
         '/a([\wáéíóúæ\(])/ui',             // 12a. (short) a matches á, optionally followed by a glide vowel i, or might be missing (i.e. a glide vowel)
         '/á([\wáéíóúæ\(])/ui',             // 12b. (long) á matches a, optionally followed by a glide vowel i
         '/(e|é)/ui',                       // 13. e matches é *, optionally followed by glide vowels i or a
         '/i([\wáéíóúæ\(])/ui',             // 14. (short) i matches í, or might be missing (i.e. a glide vowel)
         '/í([\wáéíóúæ\(])/ui',             // 15. (long) í matches i
         '/(u|ú)/ui',                       // 17. u matches ú *, optionally followed by glide vowel i
         '/(o|ó|ua)/ui',                    // 16. o matches ó matches ua *, optionally followed by glide vowel i
         '/(\-|\s)/'                        // 18. a hyphen matches a space *, or may be closed up 
         );
         // not done: gemination
      $replace = array(
         '.*',      
         '(c|g){1,2}$1',          // 1.
         '(p|b){1,2}$1',          // 2.
         '(t|d){1,2}$1',          // 3.
         '(c|ch|g|gh){1,2}$1',    // 4. 
         '(p|b|bh){1,2}$1',       // 5.   
         '(t|th|d|dh){1,2}$1',    // 6. 
         'gh?',                   // 4a. 
         'bh?',                   // 5a.   
         'dh?',                   // 6a. 
         'mh?',                   // 7.
         '(f|fh|ph)?$2',          // 7a.
         '(nd|nn)',               // 9.
         '(mb|mm|m)',             // 10.
         'h?$1',                  // 11.
         '(a|á)?i?$1',            // 12a.
         '(a|á)i?$1',             // 12b.
         '(e|é)i?a?',             // 13.
         '(i|í)?$1',              // 14.
         '(i|í)$1',               // 15.
         '(u|ú)+i?',              // 17.
         '(o|ó|ua)+i?',           // 16.
         '(\-|\s)?'               // 18.
         );
   }
   else {
      // exact match
      $find = array(                      
         '/\*/ui'                 // wildcard *
      );   
      $replace = array(
         '.*'
      );      
   }
   $searchString = preg_replace($find, $replace, $searchString);
   // find whole words; specify languages if necessary
   if ($sqlLangs == '') $searchString = '<w.*>' . $searchString . '</w>';   // find whole words, any language
   elseif ($sqlLangs == 'gle') $searchString = '((<w>)|(<w hw=".*">))' . $searchString . '</w>';   // find Irish words only
   elseif (strpos($sqlLangs, 'gle') === false) $searchString = '<w.* xml:lang="(' . $sqlLangs . ')".*>' . $searchString . '</w>';   // find words in specified langs (not incl. Irish) 
   else $searchString = '((<w.* xml:lang="(' . $sqlLangs . ')".*>)|(<w.*>))' . $searchString . '</w>';   // find whole words in specified langs or Irish
   
   
   // build SQL for main search
   $sql = "Select 
         g.RecordID As g_RecordID, g.Name, 
         v.RecordID As v_RecordID, v.Code, v.MS_Source, 
         r.RecordID As r_RecordID, r.DIL, r.Headword, r.Ref, r.PrintedRef, r.MS_Ref, r.Notes, r.EntryText 
      From 
         (reading r Inner Join 
         version v On r.VersionID = v.RecordID) Inner Join 
         glossary g On v.GlossaryID = g.RecordID 
      Where 
         (r.DIL RegExp '" . $searchString . "' Or 
         r.EntryText RegExp '" . $searchString . "') 
         ";
   if ($sqlVersions != '') $sql .= 'And v.RecordID In (' . $sqlVersions . ')';
   $sql .= ' Order By r.DIL, g.Name, v.DisplayOrder, v.Code, r.Ref ';
   if ($testing) print "<p class=\"note\"><b>SQL:</b><br/><textarea style=\"width: 100%; height: 240px; \">$sql</textarea></p>";



   // output
   $matches = mysqli_query($link, $sql);
   if (! $matches) {
      print '<p>No entries were found matching your search word. Try a shorter search term, or broaden the search criteria. </p>';
		print '<p><b>Note:</b><br/>This resource is a collection of early Irish texts, and does not contain translations at present.<br/> ';
		print 'For online <b>dictionaries</b>, try <a href="http://www.dil.ie/search?q=' . $search . '">DIL</a> (Old and Middle Irish) or <a href="http://www.focloir.ie/en/dictionary/ei/' . $search . '">foclóir.ie</a> (modern Irish).';
   }
   else {
      // count of matches
      if (mysqli_num_rows($matches) == 1) print '<p><b>One match found.</b> ';
      else print '<p><b>' . mysqli_num_rows($matches) . ' matches found.</b> ';
      print 'Entries grouped by <i>DIL</i> headword. Click the version code to see the entry in context, or the manuscript reference to see the manuscript image (where available). ';
      print 'Note that headwords only are currently available for transcriptions of H¹a, H¹b, K: see Y (closely related) for text in those cases. </p>';
//      print '<p class="note">Work in progress: Multiple-word searches to do. Spelling variants are not currently highlighted.</p>';


		$cnt = 0;
		$currentDIL = '';
      while ($row = mysqli_fetch_array($matches)) {
         $newSection = false;
         if ($row['DIL'] != $currentDIL) {
         	$newSection = true;
         	$currentDIL = $row['DIL'];
         }
         if ($newSection) {
            if ($cnt > 0) endSection();
            print '<h2>' . $row['DIL'] . '</h2>';
            startSection($groupBy);
         }
   
         print '<tr>';
         print '<td width="140" class="small">' . $row['Name'] . '</td>';
         print '<td width="40" class="small"><a title="Show this entry in the text\'s transcription. " onmouseover="tt(this);" onmouseout="tt(this);" href="texts.php?versionID=' . $row['v_RecordID'] . '&amp;readingID=' . $row['r_RecordID'] . '#' . $row['r_RecordID'] . '">' . $row['Ref'] . '</a></td>';
         print '<td class="entry">' . "\r\n\r\n" . formatEntry($row['EntryText'], $row['DIL'], '') . "\r\n" . '</td>';
         print '<td class="small text-end" nowrap="nowrap">' . msLink($row['v_RecordID'], $row['r_RecordID'], $row['MS_Source'], $row['MS_Ref']) . '</td>';
         print '</tr>';
        
         if ($cnt == mysqli_num_rows($matches) - 1) endSection();
         $cnt ++;
      }
      print '<div class="small">* Asterisks after headwords indicate they are not in DIL; "cmpd" indicates they are found under compound section of headword.</div>';
   }   
}

templateEnd('');

function startSection($groupBy) {
   print '<table class="table" width="100%">';
   print '<tr>';
   if ($groupBy != 'glossary') print '<th>Text</th>';
   print '<th>Version/Ref</th>';
   print '<th>Reading</th>';
   print '<th class="text-end">MS ref</th>';
   print '</tr>';
}

function endSection() {
   print '</table>';
}


?>
