<?php

global $top_level;
require($top_level."include/cache.php");

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

$cache_dir = "/var/cache/berlios/";
$time = 60 * 60; // 60 min
$entries = 5;

$boxstuff = "";
$boxtitle = "BerliOS News";
$boxurl = "http://news.berlios.de/";
$back_url = $boxurl."backend.php";
$cache_file = $cache_dir."BerliOS_News.backend";
$boxstuff = cache_display($cache_file,$back_url,$time,$entries);
newsbox($boxtitle, $boxurl, $boxstuff);

$boxstuff = "";
$boxtitle = "Developer News";
$boxurl = "http://developer.berlios.de/";
$back_url = $boxurl."export/rss_sfnews.php";
$cache_file = $cache_dir."DeveloperNews.backend";
$boxstuff = cache_display($cache_file,$back_url,$time,$entries);
newsbox($boxtitle, $boxurl, $boxstuff);

$boxstuff = "";
$boxtitle = "SourceWell";
$boxurl = "http://sourcewell.berlios.de/";
$back_url = $boxurl."backend.php";
$cache_file = $cache_dir."SourceWell.backend";
$boxstuff = cache_display($cache_file,$back_url,$time,$entries);
newsbox($boxtitle, $boxurl, $boxstuff);

$boxstuff = "";
$boxtitle = "DocsWell";
$boxurl = "http://docswell.berlios.de/";
$back_url = $boxurl."backend.php";
$cache_file = $cache_dir."DocsWell.backend";
$boxstuff = cache_display($cache_file,$back_url,$time,$entries);
newsbox($boxtitle, $boxurl, $boxstuff);

$boxstuff = "";
$boxtitle = "SourceLines";
$boxurl = "http://sourcelines.berlios.de/";
$back_url = $boxurl."backend.php";
$cache_file = $cache_dir."SourceLines.backend";
$boxstuff = cache_display($cache_file,$back_url,$time,$entries);
newsbox($boxtitle, $boxurl, $boxstuff);

$entries = 12;
$boxstuff = "";
$boxtitle = "DevCounter";
$boxurl = "http://devcounter.berlios.de/";
$back_url = $boxurl."backend.php";
$cache_file = $cache_dir."DevCounter.backend";
$boxstuff = cache_display($cache_file,$back_url,$time,$entries);
newsbox($boxtitle, $boxurl, $boxstuff);

$boxstuff = "";
$boxtitle = "SourceBiz";
$boxurl = "http://sourcebiz.berlios.de/";
$back_url = $boxurl."backend.php";
$cache_file = $cache_dir."SourceBiz.backend";
$boxstuff = cache_display($cache_file,$back_url,$time,$entries);
newsbox($boxtitle, $boxurl, $boxstuff);

?>
