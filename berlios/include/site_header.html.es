<!-- $Id: site_header.html.es,v 1.7 2002/05/13 23:48:43 grex Exp $ -->
<!-- Translation into Spanish by Gregorio Robles, grex@scouts-es.org -->

<?php
 if ( !isset( $top_level ) ) {
   $top_level = ".";
 }
?>
<!-- top strip -->
<table border="0" cellspacing="0" cellpadding="2" width="100%" bgcolor="#000000" >
  <tr>
    <td>
      <span class="maintitlebar">&nbsp; 
      <b><a href="<?php echo $top_level; ?>/index.php.es" class="maintitlebar">Inicio</a></b> |
      <b><a href="<?php echo $top_level; ?>/about/index.php.es" class="maintitlebar">Sobre BerilOS</a></b> |
     <b><a href="<?php echo $top_level; ?>/partners/index.php.es" class="maintitlebar">Colaboradores</a></b> |
     <b><a href="<?php echo $top_level; ?>/contact/index.php.es" class="maintitlebar">Contacto</a></b></span>
    </td>
  </tr>
</table>
<!-- end top strip -->

<!-- top title -->
<table border="0" cellspacing="0" cellpadding="0" width="100%">
  <tr valign="top" bgcolor="#FFCC33">
    <td>
      <a href="index.php.es"><img src="<?php echo $top_level; ?>/images/berliOS_logo.png" hspace="5" vspace="5" border="0" height="61" width="238" alt="BerliOS"></a>
    </td>
    <td width="10" bgcolor="#FFCC33">
      <img src="<?php echo $top_level; ?>/images/blank.gif" border="0" height="1" width="10" alt="">
    </td>
    <td valign="middle" width="99%">
      <b><font size="+1">BerliOS</font></b>
      <br>The Open Source Mediator

      <!-- logo at right -->
    </td>
    <td valign="middle">
      <table border="0">
        <tr>
          <td>
            <a href="index.php.es">
            <img src="<?php echo $top_level; ?>/images/berlios_pie_nav.jpg" ALT="Navigate Pie" 
             hspace="10" border="0" height="60" width="60" usemap="#BerliOSPie_Navigate" align="right"></a>
          </td>
          <td>
            <a href="http://www.fokus.gmd.de/" target="_blank"><img 
             src="<?php echo $top_level; ?>/images/logo_fokus.png" alt="FOKUS" 
             hspace="10" border="0" height="60" width="60" align="right"></a>
          </td>
        </tr>
      </table>
    </td>
  </tr>
<!-- end logo at right -->

  <tr>
    <td colspan="4" bgcolor="#000000">
      <img src="<?php echo $top_level; ?>/images/blank.gif" height="2" width="2" alt="">
    </td>
  </tr>
</table>
<!-- end top title -->
