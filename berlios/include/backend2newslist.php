<?php
if ((filesize($cache_file)<=1) || ((time() - filectime($cache_file)) > $time)) {
    if (!file_exists($cache_file)) {
        @touch($cache_file);
    }
    if ($rfh=@fopen($cache_file,'r')) {
        // obtain a blocking write lock, else wait 1 second and try again
        if (flock($rfh,2)) {
            // open file for writing. if this does not work, something is broken.
            if (!$wfh = @fopen($cache_file,'w')) {
                rssfail("Can't write cache file.");
            } else {
                // have successful locks and opens now
                $rdf = parse_url($back_url);
	            $fp = fsockopen($rdf['host'], 80, $errno, $errstr, 15);
	            if (!$fp) {
	                rssfail("Can't connect to host.");
	            } else {
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
	                $content = "<span class=\"small\">";
	                for ($i=0;$i<35;$i++) {
			            $link = ereg_replace(".*<link>","",$items[$i]);
			            $link = ereg_replace("</link>.*","",$link);
			            $title2 = ereg_replace(".*<title>","",$items[$i]);
			            $title2 = ereg_replace("</title>.*","",$title2);
			            if (strcmp($link,$title2)) {
				            $content .= "<li><a href=\"$link\" target=\"_blank\">$title2</a></li>\n";
			            }
		            }
		            $content .= "</span>";
                }
                $content = FixQuotes($content);
                fwrite($wfh,$content); //write the file
                fclose($wfh); //close the file
                flock($rfh,3); //release lock
                fclose($rfh); //close the lock
            }
        } else { // unable to obtain flock
            unlink($cache_file);
        }
    } else { // unable to open for reading
        unlink($cache_file);
    }
}
if ($rfh=@fopen($cache_file,'r')) {
    while (!feof ($rfh)) {
        $boxstuff .= fgets($rfh, 4096);
    }
    fclose ($rfh);
} 
?>
