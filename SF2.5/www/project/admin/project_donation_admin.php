<?php
/**
  *
  * Project Admin page to manage group's donations
  *
  * BerliOS: The Open Source Mediator
  * Copyright 2000-2004 (c) The BerliOS Crew
  * http://www.berlios.de
  *
  * @version   $Id: project_donation_admin.php,v 1.1 2004/04/02 11:28:01 helix Exp $
  *
  */


require_once('pre.php');
require_once('../../project/admin/project_admin_utils.php');

if ($create) {
	$res = db_query("
		INSERT INTO group_donate
        (group_id,business,item_name,item_number,amount,image_url,
        return,rm,cancel_return,no_note,cn,cs,currency_code,lc,comment)
        VALUES
        ('$group_id','$business','$item_name','$item_number','$amount',
        '$image_url','$return','$rm','$cancel_return','$no_note','$cn',
        '$cs','$currency_code','$lc','$comment')
	");

	if (!$res || db_affected_rows($res) < 1) {
		$feedback .= "Could not insert Project Donation entry: ".db_error();
	} else {
		$feedback .= "Project Donation created";	
		// $group->addHistory('Project Donation created','');

	}
}

if ($update) {
	$res = db_query("
	    UPDATE group_donate SET
        business='$business',
        item_name='$item_name',
        item_number='$item_number',
        amount='$amount',
        image_url='$image_url',
        return='$return',
        rm='$rm',
        cancel_return='$cancel_return',
        no_note='$no_note',
        cn='$cn',
        cs='$cs',
        currency_code='$currency_code',
        lc='$lc',
        comment='$comment'
	    WHERE group_id='".$group_id."'
	");

	if (!$res || db_affected_rows($res) < 1) {
		$feedback .= "Could not update Project's Donation entry: ".db_error();
	} else {
		$feedback .= "Project Donation updated";	
//		$group->addHistory('Project Donation updated','');

	}
}

if ($delete) {
	$res = db_query("
		DELETE FROM group_donate 
		WHERE group_id='$group_id'
	");

	if (!$res || db_affected_rows($res) < 1) {
		$feedback .= "Could not delete Project Donation entry: ".db_error();
	} else {
		$feedback .= "Project Donation deleted";	
//		$group->addHistory('Project Donation deleted','');

	}
}

project_admin_header(array('title'=>'Project Donation Management'));

?>

<h2>Project Donation Management</h2>

<h3>Create/Update/Delete Project Donation</h3>
<p>
To add a Project Donation button on your Project's homepage create your account on <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_help-ext&amp;source_page=_help-ext">PayPal</a> and fill-in your data in the following form.
<p>
Clicking on "Create/Update" will schedule the creation of the PayPal button and "Delete" will remove your data.
<p>

<?php
$res_db = db_query("
	SELECT *
	FROM group_donate 
	WHERE group_id='".$group_id."'
");

if (db_numrows($res_db) > 0) {
  $row = db_fetch_array($res_db);
?>

<form name="update_donate" action="<?php echo $PHP_SELF.'?group_id='.$group_id.'&update=1'; ?>" method="post"> 
<table cellspacing="1" cellpadding="5" border="0" bgcolor="#EAECEF">
<tr>
<td align="right" valign="top"><b>Your Donation Information</b></td>
<td valign="top"><input type="text" size="25" maxlength="255" name="comment" value='<?php echo $row['comment']; ?>'></td>
<td align="left" valign="top">
This information about your project will be presented to your donators.
</td>
</tr><tr>
<td align="right" valign="top"><b>Business</b></td>
<td valign="top"><input type="text" size="25" maxlength="255" name="business" value="<?php echo $row['business']; ?>"></td>
<td align="left" valign="top">
This is your PalPay ID, or email address, where payments will be sent.
</td>
</tr><tr>
<td align="right"><b>Item Name</b></td>
<td><input type="text" size="25" maxlength="127" name="item_name" value="<?php echo $row['item_name']; ?>"></td>
<td align="left" valign="top">
Description of Donation (maximum 127 character).
</td>
</tr><tr>
<td align="right"><b>Item Number</b></td>
<td><input type="text" size="25" maxlength="127" name="item_number" value="<?php echo $row['item_number']; ?>"></td>
<td align="left" valign="top">
Pass-through variable for you to track donations (maximum 127 character).
</td>
</tr><tr>
<td align="right"><b>Amount</b></td>
<td><input type="text" size="6" maxlength="6" name="amount" value="<?php echo $row['amount']; ?>"></td>
<td align="left" valign="top">
The amount of donation.
</td>
</tr><tr>
<td align="right"><b>Image URL</b></td>
<td><input type="text" size="25" maxlength="255" name="image_url" value="<?php echo $row['image_url']; ?>"></td>
<td align="left" valign="top">
The Image URL
</td>
</tr><tr>
<td align="right"><b>Return</b></td>
<td><input type="text" size="25" maxlength="255" name="return" value="<?php echo $row['return']; ?>"></td>
<td align="left" valign="top">
The Return URL
</td>
</tr><tr>
<td align="right"><b>Return Method (rm)</b></td>
<td>
<select name="rm">
<option value="0"<?php if ($row['rm']==0) echo " selected"; ?>>GET (0)</option>
<option value="1"<?php if ($row['rm']==1) echo " selected"; ?>>GET (1)</option>
<option value="2"<?php if ($row['rm']==2) echo " selected"; ?>>POST (2)</option>
</select>
<td align="left" valign="top">
The Return Method
</td>
</tr><tr>
<td align="right"><b>Cancel Return</b></td>
<td><input type="text" size="25" maxlength="256" name="cancel_return" value="<?php echo $row['cancel_return']; ?>"></td>
<td align="left" valign="top">
An internet URL where the user will be returned if donation is cancelled.
</td>
</tr><tr>
<td align="right"><b>Note</b></td>
<td>
<select name="no_note">
<option value="0"<?php if ($row['no_note']==0) echo " selected"; ?>>prompted</option>
<option value="1"<?php if ($row['no_note']==1) echo " selected"; ?>>not prompted</option>
</select>
<td align="left" valign="top">
Including a note with payment.
</td>
</tr><tr>
<td align="right"><b>Note Label (cn)</b></td>
<td><input type="text" size="25" maxlength="30" name="cn" value="<?php echo $row['cn']; ?>"></td>
<td align="left" valign="top">
Label that will appear above the note field (maximum 30 characters)
</td>
</tr><tr>
<td align="right"><b>Background Color (cs)</b></td>
<td>
<select name="cs">
<option value="0"<?php if ($row['cs']==0) echo " selected"; ?>>White</option>
<option value="1"<?php if ($row['cs']==1) echo " selected"; ?>>Black</option>
</select>
</td>
<td align="left" valign="top">
Sets the background color of your payment pages. 
</td>
</tr><tr>
<td align="right"><b>Currency Code</b></td>
<td>
<select name="currency_code">
<option value="EUR"<?php if ($row['currency_code']=='EUR') echo " selected"; ?>>EUR (Euro)</option>
<option value="GBP"<?php if ($row['currency_code']=='GBP') echo " selected"; ?>>GBP (Pound Sterling)</option>
<option value="USD"<?php if ($row['currency_code']=='USD') echo " selected"; ?>>USD (U.S. Dollar)</option>
<option value="CAD"<?php if ($row['currency_code']=='CAD') echo " selected"; ?>>CAD (Canadian Dolar)</option>
<option value="JPY"<?php if ($row['currency_code']=='JPY') echo " selected"; ?>>JPY (Yen)</option>
</select>
</td>
<td align="left" valign="top">
Currency Code.
</td>
</tr><tr>
<td align="right"><b>Country/Language (lc)</b></td>
<td><input type="text" size="5" maxlength="5" name="lc" value="<?php echo $row['lc']; ?>"></td>
<td align="left" valign="top">
Sets the default country and associated language. 
</td>
</tr><tr>
<td>&nbsp;</td>
<td><input type="submit" value="Update"></form>
</td>
<td><form name="delete_donate" action="<?php echo $PHP_SELF.'?group_id='.$group_id.'&delete=1'; ?>" method="post"> 
<input type="submit" value="Delete">
</form>
</td>
</tr>
</table>

<?php
} else {
?>

<form name="create_donate" action="<?php echo $PHP_SELF.'?group_id='.$group_id.'&create=1'; ?>" method="post"> 
<table cellspacing="1" cellpadding="5" border="0" bgcolor="#EAECEF">
<tr>
<td align="right" valign="top"><b>Your Donation Information</b></td>
<td valign="top"><input type="text" size="25" maxlength="255" name="comment"></td>
<td align="left" valign="top">
This information about your project will be presented to your donators.
</td>
</tr><tr>
<td align="right" valign="top"><b>Business</b></td>
<td valign="top"><input type="text" size="25" maxlength="255" name="business"></td>
<td align="left" valign="top">
This is your PalPay ID, or email address, where payments will be sent.
</td>
</tr><tr>
<td align="right"><b>Item Name</b></td>
<td><input type="text" size="25" maxlength="127" name="item_name" value="Donate BerliOS Project: <?php echo $group->getUnixName(); ?>"></td>
<td align="left" valign="top">
Description of Donation (maximum 127 character).
</td>
</tr><tr>
<td align="right"><b>Item Number</b></td>
<td><input type="text" size="25" maxlength="127" name="item_number" value="Donate BerliOS Project: <?php echo $group->getUnixName(); ?>"></td>
<td align="left" valign="top">
Pass-through variable for you to track donations (maximum 127 character).
</td>
</tr><tr>
<td align="right"><b>Amount</b></td>
<td><input type="text" size="6" maxlength="6" name="amount" value="5"></td>
<td align="left" valign="top">
The amount of donation.
</td>
</tr><tr>
<td align="right"><b>Image URL</b></td>
<td><input type="text" size="25" maxlength="255" name="image_url"></td>
<td align="left" valign="top">
The Image URL
</td>
</tr><tr>
<td align="right"><b>Return</b></td>
<td><input type="text" size="25" maxlength="255" name="return" value="https://developer.berlios.de/project/<?php echo $group->getUnixName(); ?>"></td>
<td align="left" valign="top">
The Return URL
</td>
</tr><tr>
<td align="right"><b>Return Method (rm)</b></td>
<td>
<select name="rm">
<option value="0" selected>GET (0)</option>
<option value="1">GET (1)</option>
<option value="2">POST (2)</option>
</select>
<td align="left" valign="top">
The Return Method
</td>
</tr><tr>
<td align="right"><b>Cancel Return</b></td>
<td><input type="text" size="25" maxlength="256" name="cancel_return" value="https://developer.berlios.de/project/<?php echo $group->getUnixName(); ?>"></td>
<td align="left" valign="top">
An internet URL where the user will be returned if donation is cancelled.
</td>
</tr><tr>
<td align="right"><b>Note</b></td>
<td>
<select name="no_note">
<option value="0" selected>prompted</option>
<option value="1">not prompted</option>
</select>
<td align="left" valign="top">
Including a note with payment.
</td>
</tr><tr>
<td align="right"><b>Note Label (cn)</b></td>
<td><input type="text" size="25" maxlength="30" name="cn" value="Your comment"></td>
<td align="left" valign="top">
Label that will appear above the note field (maximum 30 characters)
</td>
</tr><tr>
<td align="right"><b>Background Color (cs)</b></td>
<td>
<select name="cs">
<option value="0" selected>White</option>
<option value="1">Black</option>
</select>
</td>
<td align="left" valign="top">
Sets the background color of your payment pages. 
</td>
</tr><tr>
<td align="right"><b>Currency Code</b></td>
<td>
<select name="currency_code">
<option value="EUR" selected>EUR (Euro)</option>
<option value="GBP">GBP (Pound Sterling)</option>
<option value="USD">USD (U.S. Dollar)</option>
<option value="CAD">CAD (Canadian Dolar)</option>
<option value="JPY">JPY (Yen)</option>
</select>
</td>
<td align="left" valign="top">
Currency Code.
</td>
</tr><tr>
<td align="right"><b>Country/Language (lc)</b></td>
<td><input type="text" size="5" maxlength="5" name="lc" value="us_en"></td>
<td align="left" valign="top">
Sets the default country and associated language. 
</td>
</tr><tr>
<td>&nbsp;</td>
<td><input type="submit" value="Create"></td>
<td>&nbsp;</td>
</tr>
</table>
</form>

<?php
}

project_admin_footer(array());

?>
