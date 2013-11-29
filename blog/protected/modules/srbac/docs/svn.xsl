<html xsl:version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns="http://www.w3.org/1999/xhtml">
  
  <body style="font-family:Arial;font-size:12pt;background-color:#EEEEEE">
  	<h2>Direct Mail</h2>
  	<table width="100%" cellpadding = "4" cellspacing="0" border="1">
  		<tr>
  		<th>Revision</th>
  		<th>message</th>
  		</tr>
    <xsl:for-each select="DirectMail/logentry">
    	<tr align="left">
      	<td width="10%"><xsl:value-of select="@revision"/></td>
      	<td width="70%"><xsl:value-of select="msg"/></td>
      	</tr>
    </xsl:for-each>
    </table>
  </body>
</html>
