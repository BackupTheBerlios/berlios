<?php
//
// BerliOS : The Open Source Mediator
// Copyright 2000-2004 (c) The BerliOS Crew
// http://developer.berlios.de
//
// $Id: make_donation.php,v 1.1 2004/04/02 11:31:10 helix Exp $

require "pre.php";    // Initial db and session library, opens session

if (! isset($group_id)) {
  $group_id = 1;
}
$group=&group_get_object($group_id);

$res_don=db_query("SELECT * FROM group_donate WHERE group_id='".$group_id."'");
if (db_numrows($res_don) < 1) {

  echo db_error();
  exit_error("Invalid Group","That group does not request for donation.");
}

$comment=db_result($res_don,0,'comment');

$HTML->header(array(title=>"Make a Donation"));

if (isset($amt)) {
?>
<form action="projectdonation.php" method="post">
<h2>Thank you for offering your donation of &euro;<?php echo $amt; ?>,00 to <?php echo $group->getPublicName(); ?> (<?php echo $group->getUnixName(); ?>).</h2>
<p>If you want your name (and comment) to appear on our public <a href="/project/donations.php?group_id=<?php echo $group_id; ?>" target="_blank">Supporters page</a>,
enter a comment in the provided field (up to 100 characters). If you do
not wish to appear on the public Supporters page, leave the comment
field blank. To proceed, click on the provided PayPal button.</p>
<p>Please note: If you want your donation to be credited to your BerliOS Developer user account (so your name will appear on the supporters page and your
user account will have the donor icon), you must <a href="/account/login.php?return_to=/donate/">login to BerliOS Developer</a> before making your donation. (You need not login if you want your donation to be anonymous.)

<p>
<center>
<input type="hidden" name="group_id" value="<?php echo $group_id; ?>">
<input type="hidden" name="amt" value="<?php echo $amt; ?>">
Comment: <input type="text" name="comment" size="60" maxlength="100">
<p>
<input type="image" src="/images/x-click-but7.gif" border="0" name="submit" alt="[Donate &euro;<?php echo $amt; ?>,00 EUR]">
</center>
</form>

<hr>

<h3><a name="troubleshooting"><br></a>Troubleshooting:</h3>

<p><a name="problems"></a><b>I am having a problem using the donation system -- PayPal is not accepting my donation.</b>

<blockquote>
<p>Questions, concerns, or BerliOS Developer site issues related to the
donation system may be directed to the BerliOS Crew by <a href="/support/?group_id=1">submitting a Donation Support Request</a>.
</blockquote>

<?php
} else {
?>

<p><a href="/account/login.php?return_to=/donate/">Login</a> first,
if you want your name on the supporters page.
<p></p>
<p><b>Please note:</b>
If you want your donation to be credited to your BerliOS Developer user
account (so your name will appear on the supporters page and your user
account will have the donor icon), you must <a href="/account/login.php?return_to=/donate/">login to BerliOS Developer</a> before making your donation.  (You need not login if you want your donation to be anonymous.)</p>

<h2 align="center">Click on one of the PayPal buttons below to donate money to <a href="/projects/<?php echo $group->getUnixName(); ?>"><?php echo $group->getPublicName(); ?></a>.</h2>

<center>
<table width="100%">
<tbody><tr>
<td align="center"><a href="?group_id=<?php echo $group_id; ?>&amt=5"><img src="/images/x-click-but7.gif" alt="[Donate &euro;5,00 EUR]" border="0"></a></td>
<td align="center"><a href="?group_id=<?php echo $group_id; ?>&amt=10"><img src="/images/x-click-but7.gif" alt="[Donate &euro;10,00 EUR]" border="0"></a></td>
<td align="center"><a href="?group_id=<?php echo $group_id; ?>&amt=20"><img src="/images/x-click-but7.gif" alt="[Donate &euro;20,00 EUR]" border="0"></a></td>
<td align="center"><a href="?group_id=<?php echo $group_id; ?>&amt=50"><img src="/images/x-click-but7.gif" alt="[Donate &euro;50,00 EUR]" border="0"></a></td>
<td align="center"><a href="?group_id=<?php echo $group_id; ?>&amt=100"><img src="/images/x-click-but7.gif" alt="[Donate &euro;100,00 EUR]" border="0"></a></td>
<td align="center"><a href="?group_id=<?php echo $group_id; ?>&amt=250"><img src="/images/x-click-but7.gif" alt="[Donate &euro;250,00 EUR]" border="0"></a></td>
</tr>
<tr>
<td align="center">Donate &euro;5,00</td>
<td align="center">Donate &euro;10,00</td>
<td align="center">Donate &euro;20,00</td>
<td align="center">Donate &euro;50,00</td>
<td align="center">Donate &euro;100,00</td>
<td align="center">Donate &euro;250,00</td>
</tr>
</tbody></table>
</center>

<p>Information provided (by this user or project's admin) about donations:</p>

<blockquote>
<?php echo $comment; ?>
</blockquote>

<hr>

<h2 align="center">Instructions and Frequently Asked Questions</h2>

<p>This page includes information about how donations may be made, how the
donated money will be used, and information about the public
recognition you will receive (unless you opt-out) after making a
donation. We appreciate your interest in contributing to
BerliOS and want to make this experience one that you will feel
good about. Please read the entirety of this page.

<p><br><b><a name="toc">Table of Contents</a></b>
</p>
<blockquote>
      <div style="padding: 4px; background-color: rgb(221, 221, 221); color: rgb(0, 0, 0);">
        <ul>
          <li><a href="#why">Why should I donate money to <?php echo $group->getPublicName(); ?> (<?php echo $group->getUnixName(); ?>) and how will the money be used?</a></li>
	      <li><a href="#how">How do I donate money to <?php echo $group->getPublicName(); ?> (<?php echo $group->getUnixName(); ?>)
	      <li><a href="#caution">A note of caution before donating</a></li>
          <li><a href="#outsideus">I live outside Germany; can I still contribute money?</a></li>
          <li><a href="#taxdeduct">Is my donation tax-deductible?</a></li>
          <li><a href="#recognition">How will my contribution be recognized?</a></li>
	      <li><a href="#fees">What fees will be deducted from my donation?</a></li>
          <li><a href="#problems">I am having a problem using the donation system -- PayPal is not accepting my donation.</a></li>
          <li><a href="#questions">I have a question about the donation system; where should I direct my question?</a></li>
        </ul>
      </div>
</blockquote>

<p><a name="why"><br></a><b>Why should I donate money to <a href="/projects/<?php echo $group->getUnixName(); ?>"><?php echo $group->getPublicName(); ?> (<?php echo $group->getUnixName(); ?>)</a>?</b>
</p>
<blockquote>
<p>A representative from <a href="/projects/<?php echo $group->getUnixName(); ?>"><?php echo $group->getPublicName(); ?> (<?php echo $group->getUnixName(); ?>)</a> has provided the following information about why you should donate money to them, and how your donation will be used:
</p>
<p><?php echo $comment; ?>
</p></blockquote>

<p><a name="how"><br></a><b>How do I donate money to <a href="/projects/<?php echo $group->getUnixName(); ?>"><?php echo $group->getPublicName(); ?> (<?php echo $group->getUnixName(); ?>)</a>?</b>

<blockquote>
<p>At the <a href="#donate">bottom of this page</a>, you are provided the opportunity to proceed in donating money to <a href="/projects/<?php echo $group->getUnixName(); ?>"><?php echo $group->getPublicName(); ?> (<?php echo $group->getUnixName(); ?>)</a>. BerliOS Developer accepts donations using <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_help-ext&source_page=_help-ext">PayPal</a>.  This process has the following steps:

<ol>
  <li><b>At the <a href="#donate">bottom of this page</a>, click on the button above the amount you want to donate.</b> Go to the Donation Page (you are here, now), read the provided instructions and use one of the PayPal buttons at the bottom of the page select the size of the donation you want to make to the BerliOS Developer Project <a href="/projects/<?php echo $group->getUnixName(); ?>"><?php echo $group->getPublicName(); ?> (<?php echo $group->getUnixName(); ?>)</a>. Buttons are provided for common denominations of &euro;5, &euro;10, &euro;20, &euro;50, &euro;100, and &euro;250.</li>
  <li><b>Enter a comment if you want to appear on the <a href="/project/donations.php?group_id=<?php echo $group_id; ?>">Supporters page for <?php echo $group->getPublicName(); ?> (<?php echo $group->getUnixName(); ?>)</a>, then click the PayPal Donate button.</b> After clicking on one of the denomination buttons, you will be offered the opportunity to verify that you have picked the desired denomination, and to enter a comment that will be attached to this donation. To proceed, click on the PayPal Donate button.</li>
  <li><b>Enter your credit card information (or login to your PayPal account) on the PayPal page, then complete your donation.</b> You will be redirected to PayPal where you may complete your donation either by following the provided instructions (you may either login to a PayPal account and make the donation, or enter your credit card information).</li>
  <li><b>Track your donations.</b> Once your donation has been submitted, PayPal will email you a receipt.  The BerliOS Developer site will recognize your donation within a week. If you entered a comment on the donation page, that comment will appear on the <a href="/project/donations.php?group_id=<?php echo $group_id; ?>">Supporters page for <?php echo $group->getPublicName(); ?> (<?php echo $group->getUnixName(); ?>)</a>. A listing of donations you have made will also appear in the Donations section of the your Developer Profile page.</li>
</ol>
</blockquote>

<p><a name="caution"><br></a><b>A note of caution before you donate.  Are you donating to the right place?</b>
<blockquote>
<p>BerliOS Developer hosts over 1.300 different Open Source projects and has over 7.000 registered users. There may be some overlap among user names and project names, both on the site, and between the site and the rest of the world.  We recommend that you confirm that you are donating money to the desired place before you make your donation (is this developer a member of the right project, or is the project the same one you downloaded the software from?).  If you have questions, please contact the developer or project in question before making your donation.
</blockquote>

<p><a name="outsideus"><br></a><b>I live outside Germany; can I still contribute money to BerliOS Developer Projects?</b>
<blockquote>

<p>Yes, since the BerliOS Donation System accepts donations through PayPal, we are able to accept contributions from many different countries (including the United States, Canada, Germany, Japan, China, United Kingdom, Denmark, and others). PayPal will handle the currency conversion on your behalf.  Additional information regarding currency conversion will be provided by PayPal after you have reached step 3 in the donation process.
</blockquote>

<p><a name="taxdeduct"><br></a><b>Is my donation tax-deductible?</b>
<blockquote>
<p>To the best of our knowledge, no. In Germany, donations are only tax-deductible when they are made to non-profit organizations that are permitted to provide tax exemptions by the government (such as 501(c)3 charities).  Most of the projects hosted on BerliOS Developer are not registered as 501(c)3 charities.  Please contact the project in question if you have specific questions. If you live outside Germany and you believe this donation may be tax-deductible, please consult local tax laws and/or a tax attorney; to the best of our knowledge, donations to <?php echo $group->getPublicName(); ?> are not tax-deductible anywhere in the world.
</blockquote>

<p><a name="recognition"><br></a><b>How will my contribution be recognized?</b>
<blockquote>
<p>A Supporters page (this is a publicly-accessible page) of donors and their comments is maintained for <?php echo $group->getPublicName(); ?>. If you opted-out of display of your donation on the Supporters page, or did not login prior to making your donation, your name will not appear on that page.
<p>You may view a listing of your donations on the <a href="/my/">My Personal Page</a>: <a href="/my/donation_admin.php">Donations</a>: <a href="/my/my_donations.php">My Donations</a> page. You may remove your name from the Supporters page after-the-fact using the option provided (Donation Visibility) on the <a href="/account/">Account Maintenance</a> page.

</blockquote>

<p><a name="fees"><br></a><b>What fees will be deducted from my donation?</b>
<blockquote>
<p>The BerliOS Donation System is conducted as per the <a href="/tos/tos.php">BerliOS Terms of Service agreement</a>; all users of the BerliOS Developer site and this donation service must agree to the provided Terms of Service Agreement, including the specific details related to the Donation System.
<p>Fees are assessed from each donation by PayPal (varying based on the specific nature of the donation, and any currency conversion that must occur). On 2004-04-01 BerliOS does not deducted fees from any donation.
</blockquote>

<p><a name="problems"><br></a><b>I am having a problem using the donation system -- PayPal is not accepting my donation.</b>
<blockquote>
<p>We recommend you check the <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_help-ext&source_page=_help-ext">online help provided by PayPal</a>; if your issue persists, <a href="http://www.paypal.com/cgi-bin/webscr?cmd=_contact_us">contact PayPal for assistance</a>.

<p>When reporting issues to PayPal, please reference: <b>BerliOS: developer.berlios.de</b>
</blockquote>

<p><a name="questions"><br></a><b>I have a question about the donation system; where should I direct my question?</b>
<blockquote>
<p>Questions, concerns, or BerliOS Developer site issues related to the donation system may be directed to the BerliOS team by <a href="/support/?func=addsupport&group_id=1">submitting a Donation Support Request</a>.
</blockquote>

<hr><a name="donate"></a><h2 align="center">Select the amount to donate to <a href="/projects/<?php echo $group->getUnixName(); ?>"><?php echo $group->getPublicName(); ?></a></h2>
<center>

<table width="100%">
<tbody><tr>
<td align="center"><a href="?group_id=<?php echo $group_id; ?>&amt=5"><img src="/images/x-click-but7.gif" alt="[Donate &euro;5,00 EUR]" border="0"></a></td>
<td align="center"><a href="?group_id=<?php echo $group_id; ?>&amt=10"><img src="/images/x-click-but7.gif" alt="[Donate &euro;10,00 EUR]" border="0"></a></td>
<td align="center"><a href="?group_id=<?php echo $group_id; ?>&amt=20"><img src="/images/x-click-but7.gif" alt="[Donate &euro;20,00 EUR]" border="0"></a></td>
<td align="center"><a href="?group_id=<?php echo $group_id; ?>&amt=50"><img src="/images/x-click-but7.gif" alt="[Donate &euro;50,00 EUR]" border="0"></a></td>
<td align="center"><a href="?group_id=<?php echo $group_id; ?>&amt=100"><img src="/images/x-click-but7.gif" alt="[Donate &euro;100,00 EUR]" border="0"></a></td>
<td align="center"><a href="?group_id=<?php echo $group_id; ?>&amt=250"><img src="/images/x-click-but7.gif" alt="[Donate &euro;250,00 EUR]" border="0"></a></td>
</tr>
<tr>
<td align="center">Donate &euro;5,00</td>
<td align="center">Donate &euro;10,00</td>
<td align="center">Donate &euro;20,00</td>
<td align="center">Donate &euro;50,00</td>
<td align="center">Donate &euro;100,00</td>
<td align="center">Donate &euro;250,00</td>
</tr>
</tbody></table>
</center>

<?php
}

$HTML->footer(array());
?>
