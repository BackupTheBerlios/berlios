<!-- $Id: site_menubar.html.es,v 1.1 2002/05/13 21:50:58 grex Exp $ -->
<!-- Translation into Spanish by Gregorio Robles, grex@scouts-es.org -->

<?php
 if ( !isset( $top_level ) ) {
   $top_level = ".";
 }
?>
<td BGCOLOR="#FFCC33">
<!-- BerliOS menu -->
<table BORDER=0 CELLSPACING=0 CELLPADDING=3 WIDTH="100%" BGCOLOR="#000000" >
<tr BGCOLOR="#000000">
<td ALIGN=CENTER><img SRC="<?php echo $top_level; ?>/images/blank.gif" height="1" width="135" border=0><br>
<span class="titlebar"><font color="#FFFFFF">Público</font></span></td>
</tr>

<tr ALIGN=RIGHT BGCOLOR="#FFCC33">
<td>

<!--  <a href="<?php echo $top_level; ?>/index.php.es" class="menus">Inicio</a> -->

<p><a href="users.php.es" class="menus">Usuarios</a>
<br><a href="developers.php.es" class="menus">Desarrolladores</a>
<br><a href="manufacturer.php.es" class="menus">Industria &amp;<br>Proveedores Servicios</a>

</td>
</tr>
</table>
<p>

<table BORDER=0 CELLSPACING=0 CELLPADDING=3 WIDTH="100%" BGCOLOR="#000000" >
<tr BGCOLOR="#000000">
<td ALIGN=CENTER><img SRC="<?php echo $top_level; ?>/images/blank.gif" height="1" width="135" border=0><br>
<span class="titlebar"><font color="#FFFFFF">Plataforma</font></span></td>
</tr>

<tr ALIGN=RIGHT BGCOLOR="#FFCC33">
<td>

<p><a href="info_plat.php.es" class="menus">Plataforma de Información</a>
<br><a href="supp_deve.php.es" class="menus">Desarrollo de Open Source</a>
<br><a href="pres_plat.php.es" class="menus">Presentación de la Plataforma</a>
</td>
</tr>
</table>
<p>

<table BORDER=0 CELLSPACING=0 CELLPADDING=3 WIDTH="100%" BGCOLOR="#000000" >
<tr BGCOLOR="#000000">
<td ALIGN=CENTER><img SRC="<?php echo $top_level; ?>/images/blank.gif" height="1" width="135" border=0><br>
<span class="titlebar"><font color="#FFFFFF">Mediador</font></span></td>
</tr>

<tr ALIGN=RIGHT BGCOLOR="#FFCC33">
<td>

<p><a href="goals.php.es" class="menus">Objetivos</a>
<br><a href="proj_spon.php.es" class="menus">Patrocinio de Proyectos</a>
<br><a href="exis_solu.php.es" class="menus">Soluciones Existentes</a>
<br><a href="know_hows.php.es" class="menus">Know How</a>
</td>
</tr>
</table>
<p>
<!-- BerliOS menu -->
<table BORDER=0 CELLSPACING=0 CELLPADDING=3 WIDTH="100%" BGCOLOR="#000000" >
<tr BGCOLOR="#000000">
<td ALIGN=CENTER><img SRC="<?php echo $top_level; ?>/images/blank.gif" 
height="1" width="135" border=0><br>
<span class="titlebar"><font color="#FFFFFF">Portales</font></span></td>
</tr>

<tr ALIGN=RIGHT BGCOLOR="#FFCC33">
<td>

<p><a href="http://news.berlios.de/"          class="menus">News</a>
<br><a href="http://forum.berlios.de/"        class="menus">Forum</a>
<br><a href="http://sourcebiz.berlios.de/"    class="menus">SourceBiz</a>
<br><a href="http://sourcewell.berlios.de/"   class="menus">SourceWell</a>
<br><a href="http://sourcelines.berlios.de/"  class="menus">SourceLines</a>
<br><a href="http://sourceagency.berlios.de/" class="menus">SourceAgency</a>
<br><a href="http://devcounter.berlios.de/"   class="menus">DevCounter</a>
<br><a href="http://developer.berlios.de/"    class="menus">Developer</a>
<br><a href="http://docswell.berlios.de"      class="menus">DocsWell</a>
<br><a href="http://wiki.berlios.de/"         class="menus">Wiki</a>

<p><a href="index.php.de"><img src="<?php echo $top_level; ?>/images/deflag.png" border="0"></a>
<p><a href="index.php.en"><img src="<?php echo $top_level; ?>/images/englishflag.png" border="0"></a>
</td>
</td>
</tr>
</table>

<!--
<table BORDER=0 CELLSPACING=0 CELLPADDING=3 WIDTH="100%" BGCOLOR="#000000" >
<tr BGCOLOR="#000000">
<td ALIGN=CENTER><img SRC="<?php echo $top_level; ?>/images/blank.gif" height="1" width="135" border=0><br>
<span class="titlebar"><font color="#FFFFFF">Búsqueda</font></span></td>
</tr>

<tr ALIGN=RIGHT BGCOLOR="#FFCC33">
<td>
<center>
<font size="-1">
<form action="/cgi-bin/webglimpse/export/http"><input TYPE="hidden" NAME="localcopy" VALUE="n"><input TYPE="hidden" NAME="lines" checked VALUE="on"><input TYPE="text" SIZE="15" NAME="query" VALUE="">
<p><input TYPE="submit" NAME="Search" VALUE="Buscar">
</form>
</font>
</center>
</td>
</tr>
</table>
-->
<!-- end Search menu --></td>

<td WIDTH="10" BGCOLOR="#FFFFFF"><img SRC="<?php echo $top_level; ?>/images/blank.gif" BORDER=0 height=1 width=10></td>
