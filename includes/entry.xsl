<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet [
<!ENTITY nbsp "&#160;">
]>
<xsl:stylesheet version="1.0" 
  xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  >

  <xsl:preserve-space elements="a"/>
  
  <xsl:output 
    method="html" 
    encoding="UTF-8" />
  
  <xsl:template match="/">
        <xsl:for-each select="/p">
          <xsl:apply-templates/>
        </xsl:for-each>      
  </xsl:template>

  <xsl:template match="//w">
    <xsl:choose>
      <xsl:when test="@type='hw'"><b><xsl:apply-templates/></b> </xsl:when>
      <xsl:when test="@xml:lang='lat'"><a target="_blank" title="Link to Perseus" data-bs-toggle="tooltip"><xsl:attribute name="href">http://www.perseus.tufts.edu/hopper/morph.jsp?l=<xsl:value-of select="."/>&amp;la=la</xsl:attribute><xsl:apply-templates/></a></xsl:when>
      <xsl:otherwise><a target="_blank" title="Link to DIL" data-bs-toggle="tooltip"><xsl:attribute name="href">http://www.dil.ie/search?q=<xsl:value-of select="."/></xsl:attribute><xsl:apply-templates/></a></xsl:otherwise>
    </xsl:choose>
  </xsl:template> 
  <xsl:template match="//phr">
    <xsl:choose>
      <xsl:when test="@type='hw'"><b><xsl:apply-templates/></b> </xsl:when>
      <xsl:otherwise><xsl:apply-templates/></xsl:otherwise>
    </xsl:choose>
  </xsl:template> 
  <xsl:template match="//cb"><span class="badge bg-secondary"><xsl:value-of select="@n"/></span></xsl:template>
  <xsl:template match="//space"><a href="#" class="badge bg-warning" data-bs-toggle="tooltip"><xsl:attribute name="title">Space left in MS: <xsl:value-of select="@extent"/></xsl:attribute>[...]</a> </xsl:template>
  <xsl:template match="//gap">
    <xsl:choose>
      <xsl:when test="@reason='sampling'"><a href="#" class="inlineNote" data-bs-toggle="tooltip"><xsl:attribute name="title">Gap in transcription: Headwords only are sampled for this version. See version Y for a closely related text. </xsl:attribute>[...]</a></xsl:when>
      <xsl:otherwise><a href="#" class="badge bg-warning" data-bs-toggle="tooltip"><xsl:attribute name="title">Gap in transcription: <xsl:value-of select="@extent"/> (<xsl:value-of select="@reason"/>)</xsl:attribute>[...]</a></xsl:otherwise>
    </xsl:choose>
  </xsl:template>
  <xsl:template match="//unclear">[<xsl:apply-templates/>?]</xsl:template>
  <xsl:template match="//supplied">[<xsl:apply-templates/>]</xsl:template>
  <xsl:template match="//ex"><i><xsl:apply-templates/></i></xsl:template>
  <xsl:template match="//del"><span class="del"><xsl:apply-templates/></span></xsl:template>
  <xsl:template match="//corr">[<a href="#"  class="badge bg-warning" data-bs-toggle="tooltip" title="Editorial correction.">ED.</a> <xsl:apply-templates/>]</xsl:template>
  <xsl:template match="//sic"><xsl:apply-templates/> (<i>sic</i>)</xsl:template>
  <xsl:template match="//note"><a href="#"  class="badge bg-warning" data-bs-toggle="tooltip"><xsl:attribute name="title"><xsl:apply-templates/></xsl:attribute>NOTE</a> </xsl:template>
  <xsl:template match="//add">
    <xsl:choose>
      <xsl:when test="@place='margin'">
        <span class="gloss">(<a href="#"  class="badge bg-warning" data-bs-toggle="tooltip" title="Gloss in margin.">MARG</a> <xsl:apply-templates/>)</span>
      </xsl:when>
      <xsl:when test="@place='margin-left'">
        <span class="gloss">(<a href="#"  class="badge bg-warning" data-bs-toggle="tooltip" title="Gloss in margin left.">MARG-L</a> <xsl:apply-templates/>)</span>
      </xsl:when>
      <xsl:when test="@place='margin-right'">
        <span class="gloss">(<a href="#"  class="badge bg-warning" data-bs-toggle="tooltip" title="Gloss in margin right.">MARG-R</a> <xsl:apply-templates/>)</span>
      </xsl:when>
      <xsl:when test="@place='margin-top'">
        <span class="gloss">(<a href="#"  class="badge bg-warning" data-bs-toggle="tooltip" title="Gloss in margin top.">MARG-T</a> <xsl:apply-templates/>)</span>
      </xsl:when>
      <xsl:when test="@place='margin-bottom'">
        <span class="gloss">(<a href="#"  class="badge bg-warning" data-bs-toggle="tooltip" title="Gloss in margin bottom.">MARG-B</a> <xsl:apply-templates/>)</span>
      </xsl:when>
      <xsl:when test="@place='margin-inline'">
        <span class="gloss">(<a href="#"  class="badge bg-warning" data-bs-toggle="tooltip" title="Text added inline.">INLINE</a> <xsl:apply-templates/>)</span>
      </xsl:when>
      <xsl:otherwise>
        <span class="gloss">(<xsl:apply-templates/>)</span>
      </xsl:otherwise>
    </xsl:choose>
  </xsl:template>
</xsl:stylesheet>
