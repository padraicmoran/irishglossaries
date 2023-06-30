/* Scripts for Irish Glossaries project

Detailed search page
*/

function search_longestVersions() {
   search_clearAllVersions();
   with (document.forms['search']) {
      elements['sVer_9'].checked = true;
      elements['sVer_1'].checked = true;
      elements['sVer_2'].checked = true;
      elements['sVer_10'].checked = true;
      elements['sVer_6'].checked = true;
      elements['sVer_17'].checked = true;
   }
}
function search_clearAllVersions() {
   for (var n = 0; n < document.forms['search'].length; n ++ ) {
      if (document.forms['search'].elements[n].name.substr(0,5) == 'sVer_') document.forms['search'].elements[n].checked = false; 
   }
}
function search_clearAllLangs() {
   for (var n = 0; n < document.forms['search'].length; n ++ ) {
      if (document.forms['search'].elements[n].name.substr(0,6) == 'sLang_') document.forms['search'].elements[n].checked = false; 
   }
}


