<?php
include ("./includes/cms.php");

templateHeader('', 'Downloads');
templateMid('');
?>

<h1>Downloads</h1>

<p>You can download <a href="http://www.tei-c.org/release/doc/tei-p5-doc/en/html/index.html" target="_blank">TEI-encoded</a> 
XML files for each glossary version below (see <a href="./abbr.php">Abbreviations</a> page for details). All files &lt; 200 KB.
Longest versions marked in <b>bold</b>.</p> 

<p>Any use of the text should be appropriately acknowledged.
</p>

<div class="table-responsive">
<table class="table">
<tr>
<th><i>Sanas Cormaic</i> (longer version)</th>
<td><a href="./xml/Y.xml"><b>Y</b></a></td>
<td><a href="./xml/H1a.xml">H1a</a></td>
<td><a href="./xml/H1b.xml">H1b</a></td>
<td><a href="./xml/K.xml">K</a></td>
</tr>

<tr>
<th><i>Sanas Cormaic</i> (shorter version)</th>
<td><a href="./xml/B.xml"><b>B</b></a></td>
<td><a href="./xml/L.xml">L</a></td>
<td><a href="./xml/La.xml">La</a></td>
<td><a href="./xml/M.xml">M</a></td>
</tr>

<tr>
<th><i>Dúil Dromma Cetta</i></th>
<td><a href="./xml/D1.xml"><b>D¹</b></a></td>
<td><a href="./xml/D2.xml">D²</a></td>
<td><a href="./xml/D3.xml">D³</a></td>
<td><a href="./xml/D4.xml">D⁴</a></td>
</tr>

<tr>
<th>O’Mulconry’s Glossary</th>
<td><a href="./xml/OM1.xml"><b>OM¹</b></a></td>
<td><a href="./xml/OM2.xml">OM²</a></td>
<td><a href="./xml/OM3.xml">OM³</a></td>
<td><a href="./xml/OM4.xml">OM⁴</a></td>
</tr>

<tr>
<th><i>Loman/Irsan</i></th>
<td colspan="2"><a href="./xml/Loman.xml"><b><i>Loman</i></b></a></td>
<td colspan="2"><a href="./xml/Irsan.xml"><b><i>Irsan</i></b></a></td>
</tr>
</table>
</div>

<p>Also available:</p>
<ul>
<li><a href="./xml/eigp.rng">XML schema file</a> (RELAX NG, 301 KB).</li>
<li><a href="./docs/EIGP_Transcription_notes.pdf">Transcription notes</a> (PDF file, 68 KB): Documentation for transcription style and mark-up.</li>
</ul>

<p>A copy of these files will also, in due course, be deposited with the University of Cambridge’s institutional repository, <a href="http://www.dspace.cam.ac.uk/handle/1810/219187" target="_blank">DSpace@Cambridge</a>.</p>

<p>Several of the older editions (see <a href="./biblio.php">Bibliography</a>) are available to download from <a href="http://www.archive.org" target="_blank">archive.org</a>.</p>
   
   
<?php
templateEnd('');
?>
