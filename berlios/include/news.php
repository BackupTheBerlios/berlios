<?php

global $top_level;

function rssfail ($error) {
    $boxstuff = "Error - $error";
}
 
function FixQuotes ($what = "") {
    $what = ereg_replace("'","''",$what);
    while (eregi("\\\\'", $what)) {
        $what = ereg_replace("\\\\'","'",$what);
    }
    return $what;
}

function newsbox ($title, $url, $content) {
    echo "
    <table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" bgcolor=\"#CCCCCC\">
      <tr>
        <td>
          <table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"3\">
            <tr>
              <td colspan=\"1\" bgcolor=\"#CCCCCC\">
                <center><b><a href=\"$url\">$title</a></b></center>
              </td>
            </tr>
            <tr>
              <td bgcolor=\"#FFFFFF\">
                 $content
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
    <br>";
}

$cache_dir = "/home/groups/berlios/htdocs/berlios/cache/";
$time = 60 * 60; // 60 min

$boxtitle = "BerliOS News";
$boxurl = "http://news.berlios.de/";
$back_url = $boxurl."backend.php";
$cache_file = $cache_dir."BerliOS_News.backend";
include($top_level."include/backend2newslist.php");
newsbox($boxtitle, $boxurl, $boxstuff);
$boxstuff = "";

$boxtitle = "SourceWell";
$boxurl = "http://sourcewell.berlios.de/";
$back_url = $boxurl."backend.php";
$cache_file = $cache_dir."SourceWell.backend";
include($top_level."include/backend2newslist.php");
newsbox($boxtitle, $boxurl, $boxstuff);
$boxstuff = "";

$boxtitle = "DocsWell";
$boxurl = "http://docswell.berlios.de/";
$back_url = $boxurl."backend.php";
$cache_file = $cache_dir."DocsWell.backend";
include($top_level."include/backend2newslist.php");
newsbox($boxtitle, $boxurl, $boxstuff);
$boxstuff = "";

$boxtitle = "SourceLines";
$boxurl = "http://sourcelines.berlios.de/";
$back_url = $boxurl."backend.php";
$cache_file = $cache_dir."SourceLines.backend";
include($top_level."include/backend2newslist.php");
newsbox($boxtitle, $boxurl, $boxstuff);
$boxstuff = "";

$boxtitle = "DevCounter";
$boxurl = "http://devcounter.berlios.de/";
$back_url = $boxurl."backend.php";
$cache_file = $cache_dir."DevCounter.backend";
include($top_level."include/backend2newslist.php");
newsbox($boxtitle, $boxurl, $boxstuff);
$boxstuff = "";

$boxtitle = "SourceBiz";
$boxurl = "http://sourcebiz.berlios.de/";
$back_url = $boxurl."backend.php3";
$cache_file = $cache_dir."SourceBiz.backend";
include($top_level."include/backend2newslist.php");
newsbox($boxtitle, $boxurl, $boxstuff);
$boxstuff = "";

?>
