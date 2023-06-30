<?php
$tablePrefix = '';
$cmsTable = $tablePrefix . 'cmstable';
$cmsTableField = $tablePrefix . 'cmstablefield';

//if (! isset($_GET['$keyword'])) $keyword = '';
//else $keyword = $_GET['$keyword'];

// paging
$page = initVars('page', 1);
if ($page < 1) $page = 1;
$perPage = initVars('perPage', 50);

// $perPageSteps = array(50, 100, 250, 500);	
// small pages only allowed due to server attack July 2020
$perPageSteps = array(50, 100, 200);	
if ($perPage > 200) $perPage = 200;

// search variables
$search = initVars('sText', '');

// paging navigation
function pageNav() {
   global $versionID, $maxRow, $page, $perPage, $maxPage, $perPageSteps;

   $formName = uniqid('form');
   print '<form name="' . $formName . '" action="" method="get">';
   
   // add any other query string variables to form
   parse_str($_SERVER['QUERY_STRING'], $qs);
   foreach ($qs as $qsKey => $qsValue) {
      if ($qsKey <> 'page' && $qsKey <> 'perPage') {
         print '<input type="hidden" name="' . $qsKey . '" value="' . stripNonAlphaNum($qsValue) . '" />';
      }
   }

	// start layout
   print '<div class="container-fluid bg-light border py-3" style="">';
   print '	<div class="row">';

   // page selector
   print '		<div class="col-4">';
   print '<b>' . $maxRow . ' entries.</b> ';
   if ($maxPage > 1) {
      print 'Page <select name="page" style="width: 50px; " onchange="document.forms[\'' . $formName . '\'].submit(); ">';
      for ($n = 1; $n <= $maxPage; $n ++) {
         print '<option value="' . $n . '"';
         if ($n == $page) print ' selected="selected"';
         print '>' . $n . '</option>';
      } 
      print '</select>';
      print " of $maxPage. ";
   }
   else print 'Page 1 of 1.';
   print '		</div>';

   // previous and next links
   print '		<div class="col-4 text-center">';
   if ($page > 1) print '<a class="btn btn-secondary" href="' . $_SERVER['SCRIPT_NAME'] . '?' . changeKey('page', ($page - 1), 'readingID', '') . '">← Previous page</a>';
   if ($page < $maxPage) print ' <a class="btn btn-secondary" href="' . $_SERVER['SCRIPT_NAME'] . '?' . changeKey('page', ($page + 1), 'readingID', '') . '">Next page→</a>';
   print '		</div>';

   // adjust entries per page
   print '		<div class="col-4 text-end">';
   print '<select name="perPage" style="width: 55px; " onchange="document.forms[\'' . $formName . '\'].submit(); ">';
   foreach ($perPageSteps as $n) {
      if ($n < $maxRow) { 
         print '<option value="' . $n . '"';
         if ($n == $perPage) print ' selected="selected"';
         print '>' . $n . '</option>';
      }
   }
// "All" option disabled after server attack July 2020
//   if ($perPage == 1500) print '<option value="-1" selected="selected">All</option>'; 
//   else print '<option value="1500">All</option>';
   print '</select>';
   print ' entries per page.';
   print '		</div>';

   print '	</div>';
   print '</div>';
   print '</form>';

}

// show table of versions and abbreviations, with links to headwords section
function listVersions() {
   ?>   
   <form name="versionSelector" action="texts.php" method="get">
   <select class="form-select" name="versionID" onchange="if (this.options[this.selectedIndex].value) document.forms['versionSelector'].submit();">
   <option value="">Go to another text...</option>

   <option value="" class="head">Cormac's Glossary</option>
   <option value="1">B - Leabhar Breac, pp. 263–72</option>
   <option value="15">H¹a - H.2.15b, pp. 13–39</option>
   <option value="16">H¹b - H.2.15b, pp. 77–102</option>
   <option value="18">K - Killiney MS A 12, pp. 1–41</option>
   <option value="7">L - Book of Leinster, p. 179</option>
   <option value="11">La - Laud 610, fols. 79r–84r</option>
   <option value="8">M - Book of Úi Maine, fols. 177r–84ra</option>
   <option value="9">Y - Yellow Book of Lecan, pp. 255a–283a</option>

   <option value="" class="head">Dúil Dromma Cetta</option>
   <option value="2">D¹ - H.3.18, pp. 63–75</option>
   <option value="3">D² - H.3.18, pp. 633a–638b</option>
   <option value="4">D³ - Egerton 1782, fol. 15</option>
   <option value="5">D⁴ - H.1.13, pp. 361–2</option>

   <option value="" class="head">O’Mulconry’s Glossary</option>
   <option value="10">OM¹ - Yellow Book of Lecan, cols. 88–122</option>
   <option value="12">OM² - H.2.15b, pp. 43–4</option>
   <option value="13">OM³ - H.2.15b, pp. 102–104</option>
   <option value="14">OM⁴ - Killiney MS A 12, pp. 41–42</option>

   <option value="6" class="head">Loman</option>
   <option value="6">H.3.18, pp. 76a–79c</option>

   <option value="17" class="head">Irsan</option>
   <option value="17">H.3.18, pp. 80a–83b</option>
   </select>
   </form>
   <?php
}

function formatEntry($txt, $dilHeadword) {
   global $search, $xslDoc;

   $xmlDoc = new DOMDocument();
   $xmlDoc->resolveExternals = TRUE;
   $xmlDoc->substituteEntities = TRUE;
   $xmlDoc->loadXML('<!DOCTYPE p [
<!ENTITY aolig "{aolig}">
<!ENTITY aoligacute "{aoligacute}">
]><p>' . $txt . '</p>');

   $proc = new XSLTProcessor();
   $proc->importStylesheet($xslDoc);
   $txt = $proc->transformToXML($xmlDoc);
   
   $pattern = array();
   $replacement = array();
   array_push($pattern, 
      '/<b>([A-Za-zæęœáéíóúǽṡḟṁṅṗ<>\/]*)<\/b>/ui',
      '/AO-LIG/ui',
      '/ÁO-LIG/ui'
      );
   array_push($replacement, 
      '<b>' . DIL_link($dilHeadword) . '$1</a></b>',
      '<span class="lig">a</span>o',
      '<span class="lig">á</span>o'
      );     

   $txt = preg_replace($pattern, $replacement, $txt);
   if ($search != '' && $search != '*') $txt = preg_replace('/(>)([A-Za-zæęœáéíóúǽṡḟṁṅṗ]*)(' . $search . ')/ui', '$1$2<span class="highlight">$3</span>', $txt);
   return $txt;
}

function DIL_url($lemma) {
   if (strpos($lemma, '*')) {
      // if contains asterisk, don't add a link
      return '';
   }
   else {
      $find = array(
         '/[0-9]/',
         '/\(cmpd\)/'
         );
      $replace = array(
         '',
         ''
         );
      $searchStr = preg_replace($find, $replace, $lemma);
      return 'http://www.dil.ie/search?search_in=headword&amp;q=' . urlencode($searchStr);
   }
}
function DIL_link($hw) {
   $url = DIL_url($hw);
   if ($url == '') return '<a title="Not given in DIL" onclick="return false; " data-bs-toggle="tooltip">';
   else return '<a target="_blank" title="Link to DIL headword" href="' . $url . '" data-bs-toggle="tooltip">';
}


// add editing link if authenticates
function editLink($recordID, $text) {
   $auth = false;
   if (isset($_SERVER['REMOTE_USER'])) if ($_SERVER['REMOTE_USER'] == 'editor') $auth = true;
   if ($_SERVER["HTTP_HOST"] == 'localhost') $auth = true;

   if ($auth) return '<a target="_manager" href="./admin/manager/tables.php?tableID=2&id=' . $recordID . '">' . $text . '</a>';
   else return $text;
}

function msLink($versionID, $readingID, $msSource, $msRef) {
   if ($versionID == 4 || $versionID == 5) return $msRef; // no online images yet for D3 (Egerton 1782) or D4 (TCD 1287)
   else return '<a class="btn btn-outline-secondary btn-sm" data-bs-toggle="tooltip" title="Show manuscript image." href="view.php?versionID=' . $versionID . '&amp;msRef=' . urlencode(str_replace(' ', '_', preg_replace('/[a-d]$/ui', '', $msRef))) . '&amp;readingID=' . $readingID . '#' . $readingID . '">' . $msRef . '</a>' ;
}

?>
