<?php





	$rdf = parse_url($back_url);
	$fp = fsockopen($rdf['host'], 80, $errno, $errstr, 15);
	if (!$fp) {
	    rssfail(1);
	    exit;
	}
	if ($fp) {
	    fputs($fp, "GET " . $rdf['path'] . "?" . $rdf['query'] . " HTTP/1.0\r\n");
	    fputs($fp, "HOST: " . $rdf['host'] . "\r\n\r\n");
	    $string = "";
	    while(!feof($fp)) {
	    	$pagetext = fgets($fp,228);
	    	$string .= chop($pagetext);
	    }
	    fputs($fp,"Connection: close\r\n\r\n");
	    fclose($fp);
	    $items = explode("</item>",$string);
	    $content = "<font class=\"content\">";
	    for ($i=0;$i<35;$i++) {
		$link = ereg_replace(".*<link>","",$items[$i]);
		$link = ereg_replace("</link>.*","",$link);
		$title2 = ereg_replace(".*<title>","",$items[$i]);
		$title2 = ereg_replace("</title>.*","",$title2);
		if ($items[$i] == "") {
                    if ($i==0) {

  		      $content = "";
                    }
		} else {
		    if (strcmp($link,$title2)) {
			$content .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"$link\" target=\"new\">$title2</a><br>\n";
		    }
		}

	    }

	}
    $content = FixQuotes($content);
    if (($content == "") AND ($blockfile == "")) {
	rssfail(2);
    } else {
     $boxstuff=$content;
    }
    
    
    
?>
