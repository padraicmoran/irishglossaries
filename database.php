<?php
include ("includes/cms.php");

templateHeader('', 'About the database');
templateMid('');
?>

<h1>About the database</h1>
  
<h2>Project history</h2>
  
<p>The current database originally goes back to paper concordances compiled by Paul Russell in the 1980s, which informed a number of articles on early Irish glossaries (see <a href="biblio.php">Bibliography</a>). 
In 2005, these concordances formed the basis of a pilot project in collaboration with Pádraic Moran, which made headwords of all versions of the glossaries available in searchable form, with a facility to generate concordances between texts.</p>

<p>In July 2006 the three-year <a href="project.php">Early Irish Glossaries Project</a> began, funded by the <a href="http://www.ahrc.ac.uk" target="_blank">Arts and Humanities Research Council</a>, directed by Paul Russell, in association with Sharon Arbuthnot and Pádraic Moran. 
The original pilot database was then expanded to include the full texts of all major glossary versions, with links to lexicographical resources and manuscript images, and with advanced search options.
This expanded resource was launched in 2009.</p>

<p>All of the technical work was carried out by Pádraic Moran.</p>


<table class="table">
<tr>
<th>Version</th>
<th>Year</th>
<th>Notes</th>
</tr>

<tr>
<td>1.0</td>
<td>2006</td>
<td>Headwords and concordances only.</td>
</tr>

<tr>
<td>2.0</td>
<td>2009</td>
<td>Publication of complete transcriptions.</td>
</tr>

<tr>
<td>3.0</td>
<td>2017</td>
<td>Corrections to transcriptions of OM and <i>Irsan</i> (Pádraic Moran, April 2017).</td>
</tr>

<tr>
<td>3.1</td>
<td>2020</td>
<td>Code-base updated to PHP 7 (August 2020).</td>
</tr>

<tr>
<td>3.2</td>
<td>2022</td>
<td>Interface upgraded, converted to Bootstrap (March 2020).</td>
</tr>

<tr>
<td>3.3</td>
<td>2023</td>
<td>Manuscript image viewer updated to Mirador, with zoomable viewing using IIIF (29 Jan 2023).</td>
</tr>
</table>

<p>Website source code is available on <a href="https://github.com/padraicmoran/irishglossaries">GitHub</a>. 
</p>

<p>Text transcriptions are archived on Zenodo (in <a href="https://zenodo.org/record/8262288">PDF</a>
and <a href="https://zenodo.org/record/8262354">XML</a> formats).
</p>

<h2>Technical notes</h2>

<p>Text captured from older printed editions or transcribed from unpublished manuscripts, and marked up in XML following <a href="http://www.tei-c.org" target="_blank">TEI</a> guidelines. 
</p>

<p>The XML transcriptions are stored in a MySQL database, running on a Apache server. 
The web interface was developed in PHP. 
Hosting is provided by the University of Cambridge’s Faculty of English. 
</p>

<p>XML files and further technical specifications are available from <a href="downloads.php">downloads</a> page.
</p>

</p>



<a name="search-notes"></a>
<h2>Detailed search</h2>

<p>The following notes provides some detail on the <a href="search.php?adv=1">detailed search</a> options.
</p> 

<p><b>‘Find entries containing…’</b><br/>
Type your search word here. 
You may use the asterisk (*) character to stand for ambiguous characters. 
For example:
</p>

<ul>
<li><i>recht</i> (no asterisk) will match <i>recht</i> only. 
(Though see also notes on common spelling variations below.)</li>
<li><i>recht*</i> will match <i>recht</i>, <i>rechtaire</i>, <i>Rechtgal</i>, etc.</li>
<li><i>*chtaire</i> will match <i>rechtaire</i>, <i>techtaire</i>, etc.</li>
</ul>

<p><b>‘Exact match/Common spelling variations’</b><br/>
A major barrier to searching medieval Irish texts is the wide extent of spelling variation in manuscripts. 
To this end, we have developed a search tool that takes the most common such variations into account. 
When ‘Common spelling variations’ is selected, the following orthographical patterns are taken into account:
</p> 

<ul>
<li><i>c/g, p/b, t/d</i> after vowels may be interchangable.</li>
<li><i>g/gh, b/bh, d/dh, m/mh</i> may be interchangable.</li>
<li><i>f/ph</i> may be interchangeable, and <i>f</i> may also occur prosthetically before vowels.</li>
<li><i>nd/nn/n</i> may be interchangable, as <i>mb/mm/m</i>.</li>
<li><i>h</i> may be prefixed before vowels initially.</li>
<li>Length marks may be present or absent on vowels.</li>
<li><i>a</i> or <i>i</i> between a consonant and another vowel may be present or absent (as a glide vowel).</li>
</ul>

<p>It should be emphasised that these orthographic patterns are not formulated as diachronic rules, but merely describe spelling variations found in the manuscripts. 
They aim to be inclusive rather than linguistically precise, and leave the reader to determine the validity of matches on the basis of a wider range of results.
</p>

<p>These search rules are somewhat experimental, and we value any feedback that might help their fine-tuning.
</p>

<p><b>‘Limit results by version’</b><br/>
Check the relevant boxes to limit the search to particular glossary versions. 
Clicking ‘Longest versions only’ will select the main version of each text (including both a long and a short version of <i>Sanas Cormaic</i>).
</p>

<p><b>‘Limit to specific languages’</b><br/>
Where certain versions have been tagged for different lanugages (currently Y, OM¹, D¹), you may specify which languages to search for. 
Searching ‘Irish’ only, for example, will exclude all matches in words tagged as Latin, Greek, etc. 
Apart from words obviously Irish or Latin, we have tagged other languages only where explicitly labelled in the texts themselves. 
In several cases, ambiguities and misidentifications are discussed in the editorial notes of the published editions.
</p>

<p><b>Work in progress</b><br/>
Searching for more than one word is currently unpredictable, and needs further work.
</p> 



<h2>Acknowledgements</h2>

<p>The original pilot database was funded by the Legonna Celtic Research Prize, organised by the Council of the <a href="http://www.llgc.org.uk/" target="_blank">National Library of Wales, Aberystwyth</a>, which was won by Paul Russell in 2003. 
The paper concordances were typed up by Jill Galloway, and Sharon Arbuthnot added the <i>DIL</i> headwords for <i>Sanas Cormaic</i>.
</p>

<p>The main phase was carried out as part of the Early Irish Glossaries Project, funded by the <a href="http://www.ahrc.ac.uk" target="_blank">Arts and Humanities Research Council</a>.
</p>

<p>Jen Pollard, computing officer at Cambridge University’s Faculty of English, provided technical support during all phases of development. 
</p>  

<p>We are grateful for any and all feedback, and would like to acknowledge comments and suggestions from Jacopo Bisagni, Elliott Lash, Emily Lethbridge and Francis Rowland, among others. 
</p>

<p>Any comments or suggestions may be addressed Paul Russell in the first instance. 
Those relating specifically to the website may be directed to Pádraic Moran.
</p>

<p>Contact details:
</p>

<p>Dr Paul Russell<br/>
<a href="&#109;&#097;&#105;&#108;&#116;&#111;&#058;pr270&#64;cam&#46;ac&#46;uk">pr270&#64;cam&#46;ac&#46;uk</a><br/>
<a href="http://www.asnc.cam.ac.uk/" target="_blank">Department of Anglo-Saxon, Norse, and Celtic</a><br/>
Faculty of English<br/>
9 West Road<br/>
Cambridge<br/>
CB3 9DP
</p>

<p>Dr Pádraic Moran<br/>
<a href="&#109;&#097;&#105;&#108;&#116;&#111;&#058;padraic.moran&#64;universityofgalway&#46;ie">padraic.moran&#64;universityofgalway&#46;ie</a><br/>
<a href="http://www.universityofgalway.ie/classics/" target="_blank">Classics</a> (School of Languages, Literature and Cultures)<br/>
University of Galway 
</p>


   
<?php
templateEnd('');
?>
