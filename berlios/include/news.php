<?php

function rssfail ()
  {
   $boxstuff = "Fehler";
  }
 
function FixQuotes ($what = "") 
  {
   $what = ereg_replace("'","''",$what);
    while (eregi("\\\\'", $what)) 
      {
       $what = ereg_replace("\\\\'","'",$what);
      }
   return $what;
  }

function newsbox ($title, $content) {
    echo "
    <table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" bgcolor=\"#000000\">
      <tr>
        <td>
          <table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"3\">
            <tr>
              <td colspan=\"1\" bgcolor=\"#CCCCCC\">
                <font size=\"2\"><b>$title</b></font>
              </td>
            </tr>
            <tr>
              <td bgcolor=\"#FFFFFF\">
                 <font size=\"2\">$content</font></font>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
    <br>";
}

$boxtitle = "BerliOS News";
$back_url = "http://news.berlios.de/backend.php";
include("backend2newslist.php");
newsbox($boxtitle, $boxstuff);
$boxstuff = "";

$boxtitle = "SourceBiz";
$back_url = "http://sourcebiz.berlios.de/backend.php3";
include("backend2newslist.php");
newsbox($boxtitle, $boxstuff);
$boxstuff = "";

$boxtitle = "SourceWell";
$back_url = "http://sourcewell.berlios.de/backend.php3";
include("backend2newslist.php");
newsbox($boxtitle, $boxstuff);
$boxstuff = "";

$boxtitle = "DocsWell";
$back_url = "http://docswell.berlios.de/backend.php";
include("backend2newslist.php");
newsbox($boxtitle, $boxstuff);
$boxstuff = "";

//$boxtitle = "Slashdot";
//$cachefile = "/usr/local/httpd/htdocs.news/cache/Slashdot.cache";
//include($cachefile);
//newsbox($boxtitle, $boxstuff);
//$boxstuff = "";

//$boxtitle = "NewsForge";
//$cachefile = "/usr/local/httpd/htdocs.news/cache/NewsForge.cache";
//include($cachefile);
//newsbox($boxtitle, $boxstuff);
//$boxstuff = "";

//$boxtitle = "LinuxWeeklyNews";
//$cachefile = "/usr/local/httpd/htdocs.news/cache/LinuxWeelyNews.cache";
//include($cachefile);
//newsbox($boxtitle, $boxstuff);
//$boxstuff = "";

//$boxtitle = "Freshmeat";
//$cachefile = "/usr/local/httpd/htdocs.news/cache/Freshmeat.cache";
//include($cachefile);
//newsbox($boxtitle, $boxstuff);
//$boxstuff = "";
?>
