<?php
//echo mb_http_output();
// template functions: header, mid, end
function templateHeader($type, $title) {
   global $search, $scope, $position, $variations, $groupBy, $pageHeading, $navID;
   global $versionID, $page, $perPage, $DIL_links;
   
   if ($title == '') $title = "Early Irish Glossaries Database · Digital editions of early Irish texts";
   else $title .= ' · Early Irish Glossaries Database'
   
   // get version info
//   $versionList = mysql_query("Select g.RecordID, g.Name, g.Code, v.RecordID, v.Code, v.Desc From version v Inner Join glossary g On v.GlossaryID = g.RecordID ");

   ?>

<!doctype html>
<html lang="en">
<head>
<title><?php print $title ?></title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://unpkg.com/@popperjs/core@2"></script>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet"> 
<link href="https://fonts.googleapis.com/css2?family=Noto+Serif:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet"> 

<link rel="shortcut icon" href="./favicon.ico" type="image/x-icon" />
<link rel="stylesheet" href="./includes/eigd.css">
<link rel="stylesheet" media="print" href="./includes/eigd_print.css">
<script language="JavaScript" type="text/javascript" src="./includes/eigd.js"></script>
<script>

window.addEventListener('load', function() {
	window.scrollBy(0, -300);
});

</script>
</head>
<body>
<?php
	showNav();

	if ($type == 'home') {
		// fluid container with wide col, bg image
?>
<div class="container-fluid">
   <div class="row">
	   <div class="col-md-3 bg-light px-4 homeImage d-print-none" style="min-height: 280px; "></div>
	   <div class="col-md-8 p-5">

<?php
	}	
	elseif ($type == 'wide') {
		// full-width container 
?>
<div class="container-fluid">
	<div class="row topImage shadow-sm d-print-none" style="min-height: 205px; "></div>
  	<div class="container-fluid my-5 px-4" style="min-height: 600px; ">
<?php	
	}
	else {
		// default: container with responsive margins
?>
<div class="container-fluid">
	<div class="row topImage shadow-sm d-print-none" style="min-height: 205px; "></div>
  	<div class="container my-5" style="min-height: 600px; ">



<?php
	}
}



function templateMid($type) {
	// now redundant, after switch from two-col to one-col layout
}


function templateEnd($type) {
   global $link, $projectAccess, $pUser, $pPwd;

	if ($type == 'home') {
   ?>
		</div>
	</div>
<?php
	}
	else {
?>
	</div>
	
<?php
	}
?>
</div>

<div class="container-fluid bg-secondary bg-gradient text-light px-5 py-4 footer">

<p>Paul Russell, Pádraic Moran, Sharon Arbuthnot, <i>Early Irish Glossaries Database</i>, version 3.3 (2023)
&lt;<?php print $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ?>&gt; [accessed <?php print date("j F Y") ?>]
</p>

<?php
	// now for all pages
   print '<p class="d-print-none">Image: <i>Sanas Cormaic</i> in the <a href="/irishglossariesdev/texts.php?versionID=8">Book of Uí Maine</a>. ';
   print 'By permission of the <a href="http://www.ria.ie" target="_blank">Royal Irish Academy</a> © RIA. ' ;
   print 'Image courtesy of <a href="http://www.isos.dias.ie/" target="_blank">Irish Script on Screen</a>.</p>';

?>

</div>

<!-- Script for tooltips -->
<script language="JavaScript" type="text/javascript">

var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl)
})

</script>


<!-- Default Statcounter code for EIGD
http://www.asnc.cam.ac.uk/irishglossaries -->
<script type="text/javascript">
var sc_project=4794191; 
var sc_invisible=1; 
var sc_security="34334f7f"; 
</script>
<script type="text/javascript"
src="https://www.statcounter.com/counter/counter.js"
async></script>
<noscript><div class="statcounter"><a title="Web Analytics"
href="https://statcounter.com/" target="_blank"><img
class="statcounter"
src="https://c.statcounter.com/4794191/0/34334f7f/1/"
alt="Web Analytics"
referrerPolicy="no-referrer-when-downgrade"></a></div></noscript>
<!-- End of Statcounter Code -->

</body>
</html>

   <?php
   mysqli_close($link);
}



function showNav() {
   global $search;
?>

<nav class="navbar navbar-expand-lg navbar-dark sticky-top bg-gradient border-bottom shadow-lg" style="background-color: #5f7384;"><a href="#" onclick="adjust(); return false; "></a>
	<div class="container-fluid">
		<a class="navbar-brand" href="./">Early Irish Glossaries Database</a>
		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav ms-4 me-auto mb-2 mb-lg-0">
				<li class="nav-item"><a class="nav-link" aria-current="page" href="./">Home</a></li>
				<li class="nav-item"><a class="nav-link" href="./texts.php">Texts</a></li>
				<li class="nav-item"><a class="nav-link" href="./concordances.php">Concordances</a></li>
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Resources</a>
					<ul class="dropdown-menu" aria-labelledby="navbarDropdown">
						<li><a class="dropdown-item" href="./abbr.php">Abbreviations</a></li>
						<li><a class="dropdown-item" href="./biblio.php">Bibliography</a></li>
						<li><a class="dropdown-item" href="./downloads.php">Downloads</a></li>
						<li><a class="dropdown-item" href="./links.php">Links</a></li>
						<li><a class="dropdown-item" href="./database.php">About the database</a></li>
						<li><a class="dropdown-item" href="./project.php">About the project</a></li>
					</ul>
				</li>
				<li class="nav-item"><a class="nav-link" href="./search.php?adv=1">Detailed search</a></li>
			</ul>
			<form class="d-flex" action="./search.php" method="get">
				<input name="sText" id="search" class="form-control me-2" type="search" placeholder="Search" aria-label="Search" value="<?php print $search; ?>" >
				<button class="btn btn-secondary" type="submit">Go</button>
			</form>
		</div>

	</div>
</nav>

<?php
}

?>
