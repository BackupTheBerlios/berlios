<?php
require("include/site_header.html.es");
?>

<!-- $Id: index.php.es,v 1.15 2004/03/02 12:07:35 helix Exp $ -->
<!-- Translation into Spanish by Gregorio Robles, grex@scouts-es.org -->

            <map name="BerliOSPie">
<!-- central circle -->
            <area shape="circle" coords="225,225,45" href="index.php.es" 
                  alt="BerliOS">
            
            <!-- Informations platform Wedge -->
            <area shape="poly" coords="70,226,74,185,87,153,109,120,146,90,201,184,187,201,179,223" 
                  href="info_plat.php.es" alt="Information Platform">
            <!-- Vermittlung von Ideen Wedge
            <area shape="poly" coords="147,89,181,75,217,69,256,71,286,80,304,89,248,182,229,179,215,179,201,184" 
                  href="proj_spon.php.es" alt="Project Sponsoring"> -->
            <!-- Entwicklungs plattform Wedge -->
            <area shape="poly" coords="249,182,257,189,264,197,273,216,272,221,311,225,322,216,339,226,380,224,377,188,367,160,351,133,333,113,307,92,303,90" 
                  href="supp_deve.php.es" alt="Supporting OS Development">
            <!-- Vermittlung von Known-how OS-Software Wedge
            <area shape="poly" coords="275,222,380,225,378,265,364,299,344,327,317,355,304,361,249,268,266,253,273,239,278,223" 
                  href="exis_solu.php.es" alt="Existing Solutions"> -->
            <!-- Presentation platform wedge -->
            <area shape="poly" coords="201,267,221,272,234,271,248,268,303,361,271,375,223,382,183,376,145,363" 
                  href="pres_plat.php.es" alt="Presentation Platform">
            <!-- Vermittlung von Know-how Dienstleistungen wedge
            <area shape="poly" coords="179,225,183,242,185,252,190,258,200,267,146,362,124,347,99,321,82,291,73,264,68,225" 
                  href="know_how.php.es" alt="Know How"> -->
            
            <!-- Anwender outer wedge -->
            <area shape="poly" coords="146,363,173,374,215,382,238,382,274,376,300,365,303,361,337,420,293,439,256,449,203,449,154,438,114,421" 
                  href="manufacturer.php.es" 
                  alt="Service Providers and Manufacturers">
            <!-- Entwickler outer wedge -->
            <area shape="poly" 
                  coords="0,225,5,177,23,126,42,94,64,68,86,51,105,36,113,30,147,90,118,113,96,140,84,162,75,186,70,219,69,226" 
                  href="users.php.es" 
                  alt="Users">
            <!-- Dienstleister and hersteller outer wedge -->
            <area shape="poly" 
                  coords="380,226,450,226,447,188,437,151,422,113,401,82,380,63,361,46,339,32,303,90,327,105,343,121,355,137,366,157,373,179" 
                  href="developers.php.es" alt="Developrs">
            </map>

<?php
if ( file_exists("include/adsense.php") ) {
        include("include/adsense.php");
}
?>
            <p>
            <center>
            <img src="images/berlios_small_pie_e.jpg" width="451" height="451" 
                 alt="BerliOS Pie" usemap="#BerliOSPie" border="0">
            </center>
           
            <b>El proyecto BerliOS</b>

            <p>El objetivo de BerliOS es proporcionar apoyo a los diferentes grupos de inter&eacute;s en el &aacute;rea del software libre (también conocido como Open Source). Nuestra finalidad es la de llevar a cabo una funci&oacute;n neutral de mediaci&oacute;n. Los grupos a los que BerliOS va dirigido son por una parte <a href="developers.php.es">desarrolladores</a> y <a href="users.php.es">usuarios</a> de software libre (también conocido como Open Source) y en la otra <a href="manufacturer.php.es">compañ&iacute;as</a> que proveen soluciones comerciales para sistemas operativos y aplicaciones Open Source, as&iacute; como soporte y otros servicios relacionados.

<p><b>Search</b>
<form action="search.php">
<input TYPE="submit" NAME="Search" VALUE="Seach">
<input TYPE="text" SIZE="20" NAME="search" VALUE="">
in <select name="category">
<option value="news">News
<option value="document">Documentation
<option value="developer">Software Developer
<option value="project member">Project Member
<option value="solution">Solutions
<option value="software">Software Components
<option value="project">Projects
<option value="sponsored project">Sponsored Projects
<option value="enterprise">Enterprises
</select>
</form>

<?php
require("include/site_footer.php");
?>

</body>
</html>
