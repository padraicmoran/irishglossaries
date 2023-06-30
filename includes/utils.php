<?php

// initialise variables (previously done by 'register globals');
// gives preference to POST vars if duplicate names exist  
function initVars($varName, $default) {
   global $$varName;
   if (isset($_POST[$varName])) return stripNonAlphaNum($_POST[$varName]);
   elseif (isset($_GET[$varName])) return stripNonAlphaNum($_GET[$varName]);
   else return $default;
}

function stripNonAlphaNum($tmp) {
	$tmp = preg_replace("/[^0-9a-zA-ZáéíóúÁÉÍÓÚ_\.\*]/", "", $tmp);
//	print "<!--$tmp-->";
	return $tmp;
}

// return server variable if set, '' if not set
function getServerValue($serverVar) {
   if (isset($_SERVER[$serverVar])) return $_SERVER[$serverVar];
   else return '';
}

function escSql($tmp) {
   if (! get_magic_quotes_gpc()) return addslashes($tmp);
   else return $tmp;
}

function mySqlDate($tmp) {
	return date("Y-m-d H:i:s", strtotime($tmp));
}

function toHTML($str) {
   // link strings with http:// etc.
   $str = preg_replace('/(\s)(http:\/\/[a-zA-Z0-0.-?&%_=~-]*)/', '<a href="\\2" target="_blank">\\2</a>', $str);
   
   // links strings with www. etc.
   $str = preg_replace('/(\s)(www.[\w.-?&%_=~-]*)/', '\\1<a href="http://\\2" target="_blank">\\2</a>', $str);
   
   // link e-mail addresses
   $str = preg_replace('/([\w.-]*)@([\w-.]*)([\w])/', '<a href="&#109;&#097;&#105;&#108;&#116;&#111;&#058;\\1&#64;\\2\\3">\\1&#64;\\2\\3</a>', $str);
   
   // standardise line-breaks; clean up space around certain tags
   $str = preg_replace("/\r\n/", "\n", $str);
   
   // add <p> and <br /> tags
   $str = preg_replace("/(.*)(\n\n|$)/", "<p>\\1</p>", $str);
   $str = preg_replace("/\n/", "<br />", $str);

   // remove <p> and <br /> tags around certain others
   $str = preg_replace("/(<p>|<br \/>)*(<\/?(h[1-7]|li|ul|ol|table|tr|td|dl|dt|dd)>)(<\/p>|<br \/>)*/", "\\2", $str);
   
   // images
   $str = preg_replace('/{image:(.*)}/', '<img src="\\1" alt="" border="0"/>', $str);

   // downloads
   $str = preg_replace('/{download:(.*)}/', '<a href="downloads/\\1" target="_blank">Download...</a>', $str);

   // add line-breaks back in for readability
   //$str = preg_replace("/(<(p|h[1-7]|li|ul|ol|table|tr|td)>)/", "\r\n\r\n\\1", $str);

   return $str;
}

// check blank: if string is empty, return alternative (useful for links)
function checkBlank($str, $alt) {
   if ($str == '') return $alt;
   else return $str;
}

// highlight keyword in string
function highlight($str, $keyword) {
   if ($keyword != '') return preg_replace('/(' . $keyword . ')/i', '<span class="highlight">\\0</span>', $str);
   else return $str;
}

// form controls
// select control from db
function writeSelect($name, $sql, $selected, $allowNull, $nullText, $extras) {
	print "<select name=\"$name\" $extras>\n";
	if ($allowNull) print "<option value=\"\">$nullText\n";
	$result = mysql_query($sql) or die("<p><b>Error: " . mysql_error() . "</b></p>");
	while ($rows = mysql_fetch_array($result)) {
		print "<option value=\"" . $rows[0] . "\"";
		if ($rows[0] == $selected) print ' selected="selected"';
		print ">" . $rows[1] . "</option>\n";
	}
	print "</select>\n";
}

// writes option tag, setting selected=true if values match
function writeOption($label, $value, $testValue) {
   print '<option value="' . $value . '"';
   if ($value == $testValue) print ' selected="selected"';
   print '>' . $label . '</option>';
}

// writes radio control, setting selected=true if values match
function writeRadio($name, $value, $label, $testValue) {
   print '<input type="radio" class="form-check-input" name="' . $name . '" id="' . $name . '_' . $value . '" value="' . $value . '"';
   if ($value == $testValue) print ' checked="checked"';
   print '/> <label for="' . $name . '_' . $value . '">' . $label . '</label> ';
}

// writes radio control, setting selected=true if values match
function writeCheckbox($name, $value, $label, $testValue) {
   print '<input type="checkbox" class="form-check-input" name="' . $name . '" id="' . $name . '" value="' . $value . '"';
   if ($value == $testValue) print ' checked="checked"';
   elseif ($testValue == '_GET') {
      if (isset($_GET[$name])) print ' checked="checked"';
   }
   print '/> <label for="' . $name . '">' . $label . '</label> ';
}

// change a single key value in GET string, or append new value if key not already present
// 22 June 2008: Updated to allow for two key changes. Bit of a hack, array would be ideal.
function changeKey($key1, $value1, $key2, $value2) {
   $tmp = '';
   $found1 = false;
   $found2 = false;
   foreach($_GET As $getKey => $getValue) {
      if ($tmp != '') $tmp .= '&amp;';
      if ($getKey == $key1) {
         $tmp .= $getKey . '=' . stripNonAlphaNum(urlencode($value1));
         $found1 = true;
      } 
      elseif ($getKey == $key2) {
         $tmp .= $getKey . '=' . stripNonAlphaNum(urlencode($value2));
         $found2 = true;
      } 
      else $tmp .= $getKey . '=' . stripNonAlphaNum(urlencode($getValue));
   }
   if (! $found1 && $key1 != '') { 
      if ($tmp != '') $tmp .= '&amp;';
      $tmp .= $key1 . '=' . stripNonAlphaNum(urlencode($value1));
   }
   if (! $found2 && $key2 != '') { 
      if ($tmp != '') $tmp .= '&amp;';
      $tmp .= $key2 . '=' . stripNonAlphaNum(urlencode($value2));
   }
   return $tmp;      
}

?>
