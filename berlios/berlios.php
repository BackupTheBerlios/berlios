<?php
	$ua = "$HTTP_USER_AGENT";
	$fsn = "10pt";
	$h1 = "14pt";
	$h2 = "12pt";
	$h3 = "10pt";
	$h4 = "9pt";
	$h5 = "8pt";
	$h6 = "8pt";
	if (ereg("X11", "$ua")) {
		$fsn = "12pt";
                $h1 = "16pt";
                $h2 = "14pt";
                $h3 = "12pt";
                $h4 = "10pt";
                $h5 = "8pt";
                $h6 = "6pt";
	}
	if (ereg("Gecko", "$ua")) {
		$fsn = "10pt";
                $h1 = "14pt";
                $h2 = "12pt";
                $h3 = "10pt";
                $h4 = "9pt";
                $h5 = "8pt";
                $h6 = "8pt";
	}
?>
BODY { background-color: #FFFFFF }

OL,UL,P,BODY,TD,TR,TH,FORM { font-family: verdana,arial,helvetica,sans-serif; font-size:<?php echo $fsn ?>; color: #333333; }

H1 { font-size: <?php echo $h1; ?> font-family: verdana,arial,helvetica,sans-serif; }
H2 { font-size: <?php echo $h2; ?>; font-family: verdana,arial,helvetica,sans-serif; }
H3 { font-size: <?php echo $h3; ?>; font-family: verdana,arial,helvetica,sans-serif; }
H4 { font-size: <?php echo $h4; ?>; font-family: verdana,arial,helvetica,sans-serif; }
H5 { font-size: <?php echo $h5; ?>; font-family: verdana,arial,helvetica,sans-serif; }
H6 { font-size: <?php echo $h6; ?>; font-family: verdana,arial,helvetica,sans-serif; }

PRE,TT { font-family: courier,sans-serif }

SPAN.center { text-align: center }
SPAN.boxspace { font-size: 2pt; }
SPAN.osdn {font-size: xx-small; font-family: verdana,arial,helvetica,sans-serif;}
SPAN.search {font-size: xx-small; font-family:  verdana,arial,helvetica,sans-serif;}
SPAN.slogan {font-size: large; font-weight: bold; font-family: verdana,arial,helvetica,sans-serif;}
SPAN.footer {font-size: xx-small; font-family: verdana,arial,helvetica,sans-serif;}

.maintitlebar { color: #FFFFFF }
A.maintitlebar { color: #FFFFFF }
A.maintitlebar:visited { color: #FFFFFF }

A.sortbutton { color: #FFFFFF; text-decoration: underline; }
A.sortbutton:visited { color: #FFFFFF; text-decoration: underline; }

.menus { color: #000000; text-decoration: none; }
.menus:visited { color: #000000; text-decoration: none; }

A:link { text-decoration:none }
A:visited { text-decoration:none }
A:active { text-decoration:none }
A:hover { text-decoration:underline; color:#FF0000 }

.tabs { color: #000000; }
.tabs:visited { color: #000000; }
.tabs:hover { color:#FF0000; }
.tabselect { color: #000000; font-weight: bold; }
.tabselect:visited { font-weight: bold;}
.tabselect:hover { color:#FF0000; font-weight: bold; }

.titlebar { text-decoration:none; color:#000000; font-family: Helvetica,verdana,arial,helvetica,sans-serif; font-size: <?php echo $fsn ?>; font-weight: bold; }
.develtitle { color:#000000; font-weight: bold; }
.legallink { color:#000000; font-weight: bold; }

