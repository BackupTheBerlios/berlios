<!-- $Id: site_header.html.es,v 1.4 2002/05/13 23:28:13 grex Exp $ -->
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
<table border="0" cellspacing="0" cellpadding=0 width="100%">
  <tr valign="top" bgcolor="#FFCC33">
    <td>
      <a href="index.php.es"><img src="<?php echo $top_level; ?>/images/berliOS_logo.png" hspace="5" vspace="5" border="0" height="61" width="238" alt="BerliOS"></a>
    </td>
    <td width="10" bgcolor="#FFCC33">
      <img src="<?php echo $top_level; ?>/images/blank.gif" border="0" height="1" width="10" alt="">
    </td>
    <td valign="middle" width="99%">
      <b><font size="+1">BerliOS</font></b>
      <br>The Open Source Mediator</td>

      <!-- logo at right -->
      <map name="BerliOSPie_Navigate">
      <!-- central circle -->
      <area shape="circle" coords="30,30,6" href="goals.php.es" 
       alt="BerliOS">

      <!-- Informations platform Wedge -->
      <area shape="poly" coords="9,30,10,25,12,20,15,16, 19,12,27,24,25,27,24,30" 
       href="info_plat.php.es" alt="Plataforma de Información">

      <!-- Vermittlung von Ideen Wedge -->
      <area shape="poly" coords="20,12,24,10,29,9,34,9, 38,11,40,12,33,24,30,24, 29,24,27,24" 
       href="proj_spon.php.es" alt="Patrocinio de Proyectos">

      <!-- Entwicklungs plattform Wedge -->
      <area shape="poly" coords="33,24,34,25,35,26,36,29,36,29, 41,30,43,29,45,30,51,30,50,25, 49,21,47,18,44,15,41,12,40,12" 
       href="supp_deve.php.es" alt="Apoyando el desarrollo de Open Source">

      <!-- Vermittlung von Known-how OS-Software Wedge -->
      <area shape="poly" coords="37,30,51,30,50,35,48,40,46,44, 42,47,40,48,33,36,35,34,36,32, 37,30" 
       href="exis_solu.php.es" alt="Soluciones existentes">

      <!-- Presentation platform wedge -->
      <area shape="poly" coords="27,36,29,36,31,36,33,36,40,48, 36,50,30,51,24,50,19,48" 
       href="pres_plat.php.es" alt="Presentación de la plataforma">

      <!-- Vermittlung von Know-how Dienstleistungen wedge -->
      <area shape="poly" coords="24,30,24,32,25,34,25,34, 27,36,19,48,16,46,13,43, 11,39,10,35,9,30" 
       href="know_hows.php.es" alt="Saber Hacer">

      <!-- Anwender outer wedge -->
      <area shape="poly" coords="19,48,23,50,29,51,32,51,36,50, 40,49,40,48,45,56,39,58,34,60, 27,60,20,58,15,56" 
       href="manufacturer.php.es" alt="Proveedores de Servicios e Industria">

      <!-- Entwickler outer wedge -->
      <area shape="poly" 
       coords="0,30,1,24,3,17,6,13, 9,9,11,7,14,5,15,4, 20,12,16,15,13,19,11,22, 10,25,9,29,9,30"
       href="users.php.es" alt="Usuarios">

     <!-- Dienstleister and hersteller outer wedge -->
     <area shape="poly" 
      coords="51,30,60,30,59,25,58,20, 56,15,53,11,51,8,48,6, 45,4,40,12,44,14,46,16, 47,18,49,21,50,24" 
      href="developers.php.es" alt="Desarrolladores">
     </map>

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
