<?php
function cache_display($cache_file,$back_url,$time,$entries) {
        $filename = $cache_file;

        while ((filesize($filename)<=1) || ((time() - filectime($filename)) > $time)) {
                // file is non-existant or expired, must redo, or wait for someone else to

                if (!file_exists($filename)) {
                        @touch($filename);
                }
                // open file. If this does not work, wait one second and try cycle again
                //if ($rfh=@fopen($filename,'r')) { Lutz was hast Du nur hier gebaut?
                if ($rfh=@fopen($filename,'w')) {
                        // obtain a blocking write lock, else wait 1 second and try again
                        if(flock($rfh,2)) {
                                // open file for writing. if this does not work, something is broken.
                                if (!$wfh = @fopen($filename,'w')) {
                                        return "Unable to open cache file for writing after obtaining lock.";
                                }
                                // have successful locks and opens now
                                $return=cache_get_new_data($back_url,$entries);
                                fwrite($wfh,$return); //write the file
                                fclose($wfh); //close the file
                                flock($rfh,3); //release lock
                                fclose($rfh); //close the lock
                                return $return;
                        } else { // unable to obtain flock
                                fclose($rfh); //close the lock
                                sleep(1);
                                unlink($filename);
                        }
                } else { // unable to open for reading
                        sleep(1);
                        unlink($filename);
                }
        }

        // file is now good, use it for return value
        if (!$rfh = fopen($filename,'r')) { //bad filename
                return cache_get_new_data($back_url,$entries);
        }
        while(!flock($rfh,1+4) && ($counter < 30)) { // obtained non blocking shared lock
                usleep(250000); // wait 0.25 seconds for the lock to become available
                $counter++;
        }
	$result = "";
        $i = 0;
        while (!feof ($rfh) && $i < $entries) {
            $result .= fgets($rfh, 4096);
            $i++;
        }
        flock($rfh,3); // cancel read lock
        fclose($rfh);
        return $result;
}

function cache_get_new_data($back_url,$entries) {
    $rdf = parse_url($back_url);
    $fp = fsockopen($rdf['host'], 80, $errno, $errstr, 15);
    if (!$fp) {
        rssfail("Can't connect to host.");
    } else {
//	print("<p>fsockopen ".$rdf['host']."\n");
	fputs($fp, "GET " . $rdf['path'] . "?" . $rdf['query'] . " HTTP/1.0\r\n");
	fputs($fp, "HOST: " . $rdf['host'] . "\r\n\r\n");
	$string = "";
	while(!feof($fp)) {
	    $pagetext = fgets($fp,228);
	    $string .= chop($pagetext);
	}
	fputs($fp,"Connection: close\r\n\r\n");
	fclose($fp);
//	print("<p>fsockclose ".$rdf['host']."\n");
	$items = explode("</item>",$string);
	$content = "<span class=\"small\">";
	for ($i=0;$i<$entries;$i++) {
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
    return $content;
}
?>
