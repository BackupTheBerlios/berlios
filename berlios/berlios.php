<?php
$th_body_bgcolor = "#FFFFFF";
$th_font_family = "verdana,arial,helv,helvetica,sans-serif";
$th_tt_font_family = "courier,sans-serif";
$th_font_color = "#333333";
$th_hover_font_color = "#FF0000";

$th_nav_bgcolor = "#FFCC33";
$th_navstrip_bgcolor = "#7B7B7B";
$th_nav_font_color = "#333333";
$th_navstrip_font_color = "#FFFFFF";

$th_box_frame_color = "#CCCCCC";
$th_box_frame_width = "1";
$th_box_title_bgcolor = "#CCCCCC";
$th_box_body_bgcolor = "#FFFFFF";
$th_box_title_align = "left";
$th_box_body_align = "left";
$th_box_title_font_color = "#000000";
$th_box_body_font_color = "#333333";
$th_box_error_font_color = "#FF2020";

$th_strip_frame_color = "#CCCCCC";
$th_strip_frame_width = "1";
$th_strip_title_bgcolor = "#CCCCCC";
$th_strip_body_bgcolor = "";
$th_strip_title_align = "center";
$th_strip_body_align = "";
$th_strip_title_font_color = "#000000";
$th_strip_body_font_color = "";

$fsn = "12px";
$fss = "11px";
$h1 = "16px";
$h2 = "14px";
$h3 = "12px";
$h4 = "10px";
$h5 = "8px";
$h6 = "8px";
?>
BODY { background-color: #FFFFFF; margin: 0; }

OL,UL,P,BODY,TD,TR,TH,FORM,TEXTAREA,INPUT { font-family: <?php echo $th_font_family; ?>; font-size:<?php echo $fsn; ?>; color: <?php echo $th_font_color; ?> }

H1 { font-size: <?php echo $h1; ?>; font-family: <?php echo $th_font_family; ?> }
H2 { font-size: <?php echo $h2; ?>; font-family: <?php echo $th_font_family; ?> }
H3 { font-size: <?php echo $h3; ?>; font-family: <?php echo $th_font_family; ?> }
H4 { font-size: <?php echo $h4; ?>; font-family: <?php echo $th_font_family; ?> }
H5 { font-size: <?php echo $h5; ?>; font-family: <?php echo $th_font_family; ?> }
H6 { font-size: <?php echo $h6; ?>; font-family: <?php echo $th_font_family; ?> }

PRE,TT { font-family: <?php echo $th_tt_font_family; ?> }

.maintitlebar { color: <?php echo $th_navstrip_font_color; ?> }
A.maintitlebar { color: <?php echo $th_navstrip_font_color; ?> }
A.maintitlebar:visited { color: <?php echo $th_navstrip_font_color; ?> }

.menus { color: <?php echo $th_nav_font_color; ?>; text-decoration: none }
.menus:visited { color: <?php echo $th_font_color; ?>; text-decoration: none }
.menus:hover { text-decoration: underline; }

A:link { text-decoration: none }
A:visited { text-decoration: none }
A:active { text-decoration: none }
A:hover { text-decoration: underline; }

.title { font-family: <?php echo $th_font_family; ?>; font-size: <?php echo $h2; ?>; }

.small { font-family: <?php echo $th_font_family; ?>; font-size: <?php echo $fss; ?>; }
.newsind { font-family: <?php echo $th_font_family; ?>; font-size: <?php echo $fss; ?>; text-indent: -9px; margin-left: 9px; padding-bottom: 2px;}

A.small:link { text-decoration:none; font-size: <?php echo $fss; ?> }
A.small:visited { text-decoration:none; font-size: <?php echo $fss; ?> }
A.small:active { text-decoration:none; font-size: <?php echo $fss; ?> }
A.small:hover { text-decoration:underline; font-size: <?php echo $fss; ?> }

.titlebar { text-decoration: none; color: <?php echo $th_navstrip_font_color; ?>; font-family: <?php echo $th_font_family; ?>; font-size: <?php echo $fsn; ?>; font-weight: bold }
