<?php
$osdn_sites[0] = array('SourceWell' => 'http://sourcewell.berlios.de/');
$osdn_sites[1] = array('DocsWell' => 'http://docswell.berlios.de/');
$osdn_sites[2] = array('News' => 'http://news.berlios.de/');
$osdn_sites[3] = array('SourceBiz' => 'http://sourcebiz.berlios.de/');
$osdn_sites[4] = array("SourceLines" => 'http://sourcelines.berlios.de/');
$osdn_sites[5] = array('DevCounter' => 'http://devcounter.berlios.de/');
$osdn_sites[6] = array('SourceAgency' => 'http://sourceagency.berlios.de/');
$osdn_sites[7] = array('Developer' => 'http://developer.berlios.de/');
$osdn_sites[8] = array('OpenFacts' => 'http://openfacts.berlios.de/');

function osdn_nav_dropdown() {
	GLOBAL $osdn_sites;
?>

	<!-- OSDN navdropdown -->


	<script language="JavaScript">
	<!--
	document.write('<form name=form1>'+
	'<font size=-1>'+
	'<a href="http://www.osdn.com"><?php echo html_image("images/osdn_logo_grey.png","135","33",array("hspace"=>"10","alt"=>" OSDN - Open Source Development Network ","border"=>"0")); ?></A><br>'+
	'<select name=navbar onChange="window.location=this.options[selectedIndex].value">'+
	'<option value="http://www.osdn.com/gallery.html">Network Gallery</option>'+
	'<option>------------</option>'+<?php
	reset ($osdn_sites);
	while (list ($key, $val) = each ($osdn_sites)) {
		list ($key, $val) = each ($val);
		print "\n	'<option value=\"$val\">$key</option>'+";
	}
?>
	'</select>'+
	'</form>');
	//-->
	</script>

	<noscript>
	<a href="http://www.osdn.com"><?php echo html_image("images/osdn_logo_grey.png","135","33",array("hspace"=>"10","alt"=>" OSDN - Open Source Development Network ","border"=>"0")); ?></A><br>
	<a href="http://www.osdn.com/gallery.html"><font size="2" color="#fefefe" face="arial, helvetica">Network Gallery</font></a>
	</noscript>


	<!-- end OSDN navdropdown -->


<?php
}

/*
	Picks random OSDN sites to display
*/
function osdn_print_randpick($sitear, $num_sites = 1) {
	shuffle($sitear);
	reset($sitear);
	while ( ( $i < $num_sites ) && (list($key,$val) = each($sitear)) ) {
		list($key,$val) = each($val);
		print "\t\t&nbsp;<font color='#ffffff'>&middot;</font>&nbsp;<a href='$val'style='text-decoration:none'><font color='#ffffff'>$key</font></a>\n";
		$i++;
	}
	print "&nbsp;<font color='#ffffff'>&middot;</font>&nbsp;";
}

function osdn_print_navbar() {
	global $sys_default_name;


	print '<!-- 

OSDN navbar 

-->
<table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#CCCCCC">
	<tr> 
		<td valign="middle" align="left" bgcolor="#7B797B">
		<SPAN class="osdn">
			&nbsp;&nbsp;&nbsp;<b><a href="http://www.berlios.de/" style="text-decoration:none"><font color="#ffffff">BerliOS</a>&nbsp;<font color="#ffffff">:</font>&nbsp;
';
	osdn_print_randpick($GLOBALS['osdn_sites'], 5);
	print '
		</SPAN>
		</td>
		<td valign="middle" align="right" bgcolor="#7B797B">
		<SPAN class="osdn">
			<b>';
/*
		<a href="http://www.osdn.com/index.pl?indexpage=myosdn" style="text-decoration:none"><font color="#ffffff">My OSDN</font></a>&nbsp;&middot;&nbsp;
		<a href="" style="text-decoration:none"><font color="#ffffff">JOBS</font></a>&nbsp;&middot;&nbsp;
*/
	print '
		<a href="/partners.php" style="text-decoration:none"><font color="#ffffff">Partners</font></a>&nbsp;<font color="#ffffff">&middot;</font>&nbsp; 
		<a href="/contact.php" style="text-decoration:none"><font color="#ffffff">Contact Us</font></a>&nbsp;</b></font>
		</SPAN>
		</td>
	</tr>
</table>

<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr> 
		<td bgcolor="#ffffff" WIDTH="20%">';
	echo '<a href="//'.$GLOBALS['sys_default_host'].'/">'.html_image("images/berliOS_logo.png","238","61",array("hspace"=>"5","border"=>"0","alt"=>" BerliOS ")).'</a></TD><TD bgcolor="#ffce31" WIDTH="60%">';
	echo '&nbsp;&nbsp;<b><font size="+1">BerliOS Developer</font></b>';
	echo '<br>&nbsp;&nbsp;Fostering Open Source Development';

	srand((double)microtime()*1000000);
	$random_num=rand(0,100000);

	if (session_issecure()) {

		//secure pages use James Byer's Ad server

	} else {

		//insecure pages use osdn ad server

	}
	print '</td>
		<td valign="center" align="right" bgcolor="#ffce31" WIDTH="20%"><a href="http://www.fokus.fhg.de">' . html_image("images/logo_fokus.png","60","60",array("hspace"=>"5","vspace"=>"5","border"=>"0","alt"=>" Fraunhofer FOKUS ")) . '</a>
	</td>
	</tr>
</table>
';

//
//  Actual layer call must be outside of table for some reason
//
/*
if (!session_issecure()) {


}
*/
echo '<!-- 


End OSDN NavBar 


-->';
}

?>
