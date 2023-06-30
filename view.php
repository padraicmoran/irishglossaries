<?php
include ("includes/cms.php");

$versionID = initVars('versionID', 0);
$readingID = initVars('readingID', 0);
$msRef = str_replace('_', ' ', initVars('msRef', ''));
if ($msRef == '') $msRef = 'error';

$msRefLabel = "<b>$msRef</b>";

// special cases: where long entries mean a col. has no assigned entry; paging therefore breaks
if ($versionID == 9 && $msRef == 'col. 60') $msRef = 'col. 62';
if ($versionID == 9 && $msRef == 'col. 75') $msRef = 'col. 76';
// stop OM stepping back into Y (and breaking page)
if ($versionID == 10 && $msRef == 'col. 87') $msRef = 'col. 88';


$msSource = '';
$nextMsRef = '';
$prevMsRef = '';
$firstColOnPage = '';
$iiif_manifest = '';
$iiif_index = 0;

// version switchers: when moving between versions in one MS
// movement between Y/OM1
if ($versionID == 10 && $msRef == 'col. 84') $versionID = 9;
elseif ($versionID == 9 && $msRef == 'col. 90') $versionID = 10;

// movement between K/OM4
if ($versionID == 18 && $msRef == 'p. 41') $versionID = 14;
elseif ($versionID == 14 && $msRef == 'p. 40') $versionID = 18;

// movement between D1/H2
if ($versionID == 6 && $msRef == 'p. 75') $versionID = 2;
elseif ($versionID == 2 && $msRef == 'p. 76') $versionID = 6;

// movement between H1b/OM3
if ($versionID == 13 && $msRef == 'p. 101') $versionID = 16;
if ($versionID == 16 && $msRef == 'p. 103') $versionID = 13;

// movement between Loman/Irsan
if ($versionID == 6 && $msRef == 'p. 80') $versionID = 17;
if ($versionID == 17 && $msRef == 'p. 78') $versionID = 6;


// check msRef and get version details
$sql = "Select g.Name, v.Code, v.ShortDesc, v.Desc, v.MS_Source, v.Copyright 
   From version v Inner Join 
      glossary g On v.GlossaryID = g.RecordID Inner Join
      reading r On r.VersionID = v.RecordID
   Where v.RecordID = $versionID And
      r.MS_Ref Like '$msRef%' ";
$title = mysqli_query($link, $sql);
if (mysqli_num_rows($title) > 0) {
	$row = mysqli_fetch_array($title);
   $gName = $row['Name'];
   $gVersion = $row['Code'];
   $gDesc = $row['Desc'];
   $pageTitle = "$gVersion: $msRef";
   $msSource = $row['MS_Source'];
   $copyright = $row['Copyright'];
}
else $msRef = 'error';

// work out image URL (and next/prev msRef)
if ($msRef != 'error') getURL();

?>

<!doctype html>
<html lang="en">
<head>
<title>Early Irish Glossaries Database</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet"> 
<link href="https://fonts.googleapis.com/css2?family=Noto+Serif:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet"> 

<link rel="shortcut icon" href="./favicon.ico" type="image/x-icon" />
<link rel="stylesheet" href="./includes/eigd.css">

</head>
<body>

<?php
showNav();
?>

<div class="container-fluid py-2">
<?php

if ($msRef != 'error') {
   // title of text/ms
   print '<div style="float: left; ">';
   print '<b>' . $gName . '</b> ';
   print '(version <a href="texts.php?versionID=' . $versionID . '">' . $gVersion . '</a>) = ';
   print str_replace('MS', ' <span class="sc">ms</span>', $gDesc);
   print '</div>';
   
   // paging
   print '<div style="float: right; ">';
   print $msRefLabel . ' ';
   if ($prevMsRef != '') print '<a class="btn btn-secondary btn-sm" href="view.php?versionID=' . $versionID . '&amp;msRef=' . urlencode(str_replace(' ', '_', $prevMsRef)) . '">prev page</a> ';
   if ($nextMsRef != '') print '<a class="btn btn-secondary btn-sm" href="view.php?versionID=' . $versionID . '&amp;msRef=' . urlencode(str_replace(' ', '_', $nextMsRef)) . '">next page</a> ';
   print '</div>';
}
else {
   print 'No images found. Go to <a href="texts.php">Texts</a>.';
}
?>
</div>



<!-- manuscript pane -->
<script src="https://unpkg.com/mirador@latest/dist/mirador.min.js"></script>
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500">
<div id="miradorViewer" style="position: absolute; top: 110px; bottom: 50px; width: 75%; overflow: auto; ">

<script type="text/javascript">
// https://github.com/ProjectMirador/mirador/blob/master/src/config/settings.js

var mirador = Mirador.viewer({
	id: "miradorViewer",
	language: 'en',
	window: {
		allowClose: false,
		allowTopMenuButton: false,
		views: [ { key: 'single' } ]
	},
	workspaceControlPanel: {
   	enabled: false,
	},
	windows: [{ 
		manifestId: '<?php print $iiif_manifest ?>', 
		thumbnailNavigation: "off",
		canvasIndex: <?php print $iiif_index ?>
	}],
  	workspace: {
		showZoomControls: true
	}
});
</script>



</div>

<div class="p-2 border-start bg-light" style="position: absolute; top: 110px; bottom: 50px; right: 0px; width: 25%; overflow: auto;">
<?php
 	
// work out what MS_Refs to look for
if ($versionID == 9 || $versionID == 10) {
   // special case for Y, OM1
   $sqlMatchMsRef = "(
      r.MS_Ref = 'col. $firstColOnPage'
      Or r.MS_Ref = 'col. " . ($firstColOnPage + 1) . "'
      Or r.MS_Ref = 'col. " . ($firstColOnPage + 2) . "'
      ) ";
}
else $sqlMatchMsRef = "r.MS_Ref Like '$msRef%' ";

if ($iiif_manifest != '') {
   $sql = "Select 
         r.RecordID, r.Headword, r.Ref, r.MS_Ref
      From 
         reading r  
      Where 
         r.VersionID = $versionID And
         $sqlMatchMsRef
      Order By 
         r.Seq ";
   
   $headwords = mysqli_query($link, $sql);
   if ($headwords) {
      print '<p>Headwords on this page:</p>';
      $tmpMsRef = '';
      while ($row = mysqli_fetch_array($headwords)) {
			if ($tmpMsRef <> $row['MS_Ref']) {
				$tmpMsRef = $row['MS_Ref'];
				print '<div class="newCol">' . $tmpMsRef . '</div>';
			}
         print '<a name="' . $row['RecordID'] . '"></a>'; 
         print '<a href="texts.php?versionID=' . $versionID . '&amp;readingID=' . $row['RecordID'] . '#' . $row['RecordID'] . '" style="display: block; padding: 1px 2px 1px 2px; width: 100%; "'; 
         if ($row['RecordID'] == $readingID) print ' class="highlight"';
         print 'title="Show this entry in the transcription. ">';
         print $row['Headword'];
         print '</a>';
      }
      print '<p>&nbsp;</p>';
   }
}
?>
</div>

<div class="p-2 border-top bg-secondary bg-gradient text-light text-center footer" style="position: absolute; bottom: 0px; width: 100%; height: 50px; ">
<?php

if ($msRef != 'error') {
   // copyright statement
   if ($copyright == 'ria') print '© <a href="http://www.ria.ie/" target="_blank">The Royal Irish Academy</a>. '; 
   elseif ($copyright == 'tcd') print '© Board of <a href="http://www.tcd.ie/" target="_blank">Trinity College Dublin</a>. ';
   elseif ($copyright == 'killiney') print '© <a href="http://www.franciscans.ie/" target="_blank">Order of Friars Minor</a>–<a href="http://www.ucd.ie/" target="_blank">University College Dublin</a> Partnership. ';
   elseif ($copyright == 'bodleian') print '© <a href="http://www.ouls.ox.ac.uk/bodley/" target="_blank">Bodleian Library</a>, University of Oxford. ';
   
   // link to image source
   $linkText = 'Image courtesy of ';
   if ($msSource == 'isos') print $linkText . '<a href="http://www.isos.dias.ie/" target="_blank">Irish Script on Screen</a>.';
   elseif ($msSource == 'scan') print 'Image scanned by Early Irish Glossaries Project. ';
}

?>
  
   </div>
</div>

<!-- Start of StatCounter Code -->
<script type="text/javascript">
var sc_project=4794191; 
var sc_invisible=1; 
var sc_partition=54; 
var sc_click_stat=1; 
var sc_security="34334f7f"; 
</script>

<script type="text/javascript"
src="http://www.statcounter.com/counter/counter_xhtml.js"></script><noscript><div
class="statcounter"><a title="web stats" class="statcounter"
href="http://www.statcounter.com/free_web_stats.html"><img
class="statcounter"
src="http://c.statcounter.com/4794191/0/34334f7f/1/"
alt="web stats" /></a></div></noscript>
<!-- End of StatCounter Code -->
</body>
</html>
<?php
mysqli_close($link);

// determine URL for this MS image (zoomify or external link)
// also get prev/next page links, first col on page for Y
function getURL() {
   global $versionID, $msRef, $msRefLabel, $prevMsRef, $nextMsRef, $firstColOnPage, $iiif_manifest, $iiif_index;
   switch($versionID) {
   case 1:       // B
      $iiif_manifest = 'https://www.isos.dias.ie/static/manifests/RIA_MS_23_P_16.json?manifest=https://www.isos.dias.ie/static/manifests/RIA_MS_23_P_16.json';
		$pg = substr($msRef, 3);
      $iiif_index = $pg + 9;
     
      if ($pg > 263) $prevMsRef = 'p. ' . ($pg - 1);
      if ($pg < 272) $nextMsRef = 'p. ' . ($pg + 1);
      break;

   case 2:      // H.3.18 (D1)
   case 3:      // H.3.18 (D2)
   case 6:      // H.3.18 (Loman)
   case 17:     // H.3.18 (Irsan)
      $iiif_manifest = 'https://www.isos.dias.ie/static/manifests/TCD_MS_1337.json?manifest=https://www.isos.dias.ie/static/manifests/TCD_MS_1337.json';

      $pg = substr($msRef, 3);
      if ($versionID == 2 && $msRef == 'slip after p. 70') $iiif_index = 99;
      elseif ($versionID == 2 && $pg < 71) $iiif_index = $pg + 27;
      elseif ($versionID == 2 && $pg >= 71) $iiif_index = $pg + 29;
      elseif ($versionID == 6 || $versionID == 17) $iiif_index = $pg + 30;
      else $iiif_index = $pg + 177; // D2

      if (($pg > 63 && $pg <= 75) || ($pg > 76 && $pg <= 83) || ($pg > 633 && $pg <= 638)) $prevMsRef = 'p. ' . ($pg - 1);
      if (($pg >= 63 && $pg < 75) || ($pg >= 76 && $pg < 83) || ($pg >= 633 && $pg < 638)) $nextMsRef = 'p. ' . ($pg + 1);
      
      // special case for p. 70 (slip)
      if ($pg == 70) $nextMsRef = 'slip after p. 70';
      if ($msRef == 'slip after p. 70') {
         $prevMsRef = 'p. 70';
         $nextMsRef = 'p. 71';
      }
      if ($pg == 71) $prevMsRef = 'slip after p. 70';
      
      break;

   case 7:      // L      
      $iiif_manifest = 'https://www.isos.dias.ie/static/manifests/TCD_MS_1339.json?manifest=https://www.isos.dias.ie/static/manifests/TCD_MS_1339.json';
		$iiif_index = 178;
      break;

   case 8:     // M
      $iiif_manifest = 'https://www.isos.dias.ie/static/manifests/RIA_MS_D_ii_1.json?manifest=https://www.isos.dias.ie/static/manifests/RIA_MS_D_ii_1.json';  

      $fol = substr($msRef, 5, -1);
      $side = substr($msRef, -1);
      $iiif_index = (($fol - 176) * 2) + 236;
      if ($side == 'v') $iiif_index ++;

      // 177r-184r [119r-126r] = 7 fols. = 14.5 pp
      // msRef =  177r* [119r]      ISOS =   239*
      //          177v [119v]                240
      //          178r*                      241*
      //          178v                       242
      // ((msRef - 176) * 2) + 237 
      $msRefLabel .= ' [' . ($fol- 58) . $side . ']'; 
      
      if ($fol > 177 || ($fol == 177 && $side == 'v')) {
         if ($side == 'r') $prevMsRef = 'fol. ' . ($fol - 1) . 'v'; 
         else $prevMsRef = 'fol. ' . ($fol) . 'r';
      }   
      if ($fol < 184 || ($fol == 184 && $side == 'v')) {
         if ($side == 'r') $nextMsRef = 'fol. ' . ($fol) . 'v'; 
         else $nextMsRef = 'fol. ' . ($fol + 1) . 'r';
      }   
      break;

   case 9:      // YBL (Y) 
   case 10:     // YBL (OM1)
      $iiif_manifest = 'https://www.isos.dias.ie/static/manifests/TCD_MS_1318.json?manifest=https://www.isos.dias.ie/static/manifests/TCD_MS_1318.json';

      $firstColOnPage = floor(substr($msRef, 5) / 3) * 3;
      $msRefLabel = '<b>cols. ' . $firstColOnPage . '–' . ($firstColOnPage + 2) . '</b>';
		$iiif_index = floor(($firstColOnPage - 3) / 3) + 48;
      
      if ($firstColOnPage > 3) $prevMsRef = 'col. ' . ($firstColOnPage - 3);
      if ($firstColOnPage < 120) $nextMsRef = 'col. ' . ($firstColOnPage + 3);
      break;

   case 11:     // La
		$iiif_manifest = 'https://iiif.bodleian.ox.ac.uk/iiif/manifest/cb909a51-5acd-4fee-95ec-51ff09b87676.json';

      $fol = substr($msRef, 5, -1);
      $side = substr($msRef, -1);

      if ($fol > 79 || ($fol == 79 && $side == 'v')) {
         if ($side == 'r') $prevMsRef = 'fol. ' . ($fol - 1) . 'v'; 
         else $prevMsRef = 'fol. ' . ($fol) . 'r';
         if ($prevMsRef == 'fol. 82v') $prevMsRef = 'fol. 80v';
      }   
      if ($fol < 86) {
         if ($side == 'r') $nextMsRef = 'fol. ' . ($fol) . 'v'; 
         else $nextMsRef = 'fol. ' . ($fol + 1) . 'r';
         if ($nextMsRef == 'fol. 81r') $nextMsRef = 'fol. 83r';
      }   
      break;

   case 12:     // H.2.15b (OM2)
   case 13:     // H.2.15b (OM3)
   case 15:     // H.2.15b (H1a)
   case 16:     // H.2.15b (H1b)
      $iiif_manifest = 'https://www.isos.dias.ie/static/manifests/TCD_MS_1317.json?manifest=https://www.isos.dias.ie/static/manifests/TCD_MS_1317.json';

      $pg = substr($msRef, 3);
      if ($versionID == 15) $iiif_index = $pg + 3;
      else $iiif_index = $pg + 5;
      $msRefLabel .= ' [' . ($pg + 77) . ']';

      if (($pg > 13 && $pg <= 39) || ($pg == 42) || ($pg > 77 && $pg <= 102) || ($pg > 102 && $pg <= 104)) $prevMsRef = 'p. ' . ($pg - 1);
      if (($pg >= 13 && $pg < 39) || ($pg == 41) || ($pg >= 77 && $pg < 102) || ($pg >= 102 && $pg < 104)) $nextMsRef = 'p. ' . ($pg + 1);
      break;
   
   case 14:     // Killiney MS (OM4)      
   case 18:     // Killiney MS (K)      
      $iiif_manifest = 'https://www.isos.dias.ie/static/manifests/UCD_MS_A_12.json?manifest=https://www.isos.dias.ie/static/manifests/UCD_MS_A_12.json';

      $pg = $iiif_index;
      $iiif_index = substr($msRef, 3) + 3;

      if ($pg > 1 && $pg <= 42) $prevMsRef = 'p. ' . ($pg - 1);
      if ($pg >= 1 && $pg < 42) $nextMsRef = 'p. ' . ($pg + 1);
      break;
   }
}

// pad a number with leading zeros; return a string of specified length
function zeroPad($num, $len) {
   if (strlen($num) < $len) return str_pad($num, $len, "0", STR_PAD_LEFT);
   else return $num;   
}

?>
