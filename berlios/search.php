<?php
$url = "http://www.berlios.de/index2.php";
if (isset($category)) {
	switch ($category) {
	case "news":
		$url = "http://news.berlios.de/modules.php";
		$url .= "?name=Search&query=$search";
	break;
	case "document":
		$url = "http://docswell.berlios.de/docsearch.php";
		$url .= "?search=$search";
	break;
	case "solution":
		$url = "http://sourcelines.berlios.de/solsearch.php";
		$url .= "?search=$search";
	break;
	case "software":
		$url = "http://sourcewell.berlios.de/appsearch.php";
		$url .= "?search=$search";
	break;
	case "developer":
		$url = "http://devcounter.berlios.de/devresults.php";
		$url .= "?option=name&search_text=$search";
	break;
	case "project":
		$url = "http://developer.berlios.de/search/";
		$url .= "?type_of_search=soft&exact=1&forum_id=&is_bug_page=&group_id=&words=$search";
	break;
	case "sponsored project":
		$url = "http://sourceagency.berlios.de/beta/search.php3";
		$url .= "?search=$search";
	break;
	case "project member":
		$url = "http://developer.berlios.de/search/";
		$url .= "?type_of_search=people&exact=1&forum_id=&is_bug_page=&group_id=&words=$search";
	break;
	case "enterprise":
		$url = "http://sourcebiz.berlios.de/enterprises.php3";
		$url .= "?by=filter&name=$search";
	break;
	}
}
?>
<HTML>
<HEAD>
   <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
   <META HTTP-EQUIV="refresh" CONTENT="0;url=<?php echo "$url"; ?>">
   <META NAME="GENERATOR" CONTENT="Mozilla/4.05 [en] (X11; I; SunOS 5.6 sun4m) [Netscape]">
   <META NAME="robots" CONTENT="noindex">
   <TITLE>Page redirected to ...</TITLE>
<LINK rel="stylesheet" type="text/css" href="berlios.css">
</HEAD>
<BODY>
</BODY>
</HTML>
