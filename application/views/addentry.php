<?php

//include("Constants.php");
//include("database.php");
//include("ContactClass.php");

//$databaseConnection = new database();

//$mysqli = $databaseConnection->connect_DB(SERVER, USER, PASSWORD, DATABASE);

//$newContact = new contact();
//
//if ($_POST) {             
	//echo $newContact->addContact($mysqli, $_POST['f_name'], $_POST['l_name'],$_POST['address'],$_POST['add_type'],$_POST['city'], $_POST['town'], 
	//$_POST['tel_number'], $_POST['tel_type'], $_POST['email'], $_POST['email_type']);

	//$mysqli->close();
//}

?>

<html>
<head>
<title>Add an Entry</title>
</head>
<body>
<h1>Add an Entry</h1>
                                
	<form method="post" action="<?php echo $_SERVER["PHP_SELF"];?>" 
	<p><strong>First/Last Names:</strong><br/>
	<input type="text" name="f_name" size="30" maxlength="75" value="<?php echo set_value('f_name'); ?>"/>
	<input type="text" name="l_name" size="30" maxlength="75"value="<?php echo set_value('l_name'); ?>"></p>
	<?php echo form_error('f_name'); ?><?php echo form_error('l_name'); ?>
	
	<p><strong>Address:</strong><br/>
	<input type="text" name="address" size="100"  value="<?php echo set_value('address'); ?>" size="50" /></p>
	<?php echo form_error('address'); ?>
	
	<p><strong>City/County:</strong><br/>
	<input type="text" name="city" size="30" maxlength="50" value="<?php echo set_value('city'); ?>"/>
	<input type="text" name="town" size="30" maxlength="30" value="<?php echo set_value('town'); ?>"/>
	<?php echo form_error('city'); ?>
	<?php echo form_error('town'); ?>
	
	<p><strong>Address Type:</strong><br/>
	<input type="radio" name="add_type" value="home" checked> home
	<input type="radio" name="add_type" value="work"> work
	<input type="radio" name="add_type" value="other"> other</p>

	<p><strong>Telephone Number:</strong><br/>
	<input type="text" name="tel_number" size="30" maxlength="25" value="<?php echo set_value('tel_number'); ?>"/>
	<input type="radio" name="tel_type" value="home" checked> home
	<input type="radio" name="tel_type" value="work"> work
	<input type="radio" name="tel_type" value="other"> other</p>
	<?php echo form_error('tel_number'); ?>
	
	<p><strong>Email Address:</strong><br/>
	<input type="text" name="email" size="30" maxlength="150" value="<?php echo set_value('email'); ?>"/>
	<input type="radio" name="email_type" value="home" checked> home
	<input type="radio" name="email_type" value="work"> work
	<input type="radio" name="email_type" value="other"> other</p>
	<?php echo form_error('email'); ?>
	
	<p><strong>Fax Number:</strong><br/>
	<input type="text" name="fax" size="22" maxlength="20" value="<?php echo set_value('fax'); ?>"/>
	<input type="radio" name="fax_type" value="home" checked> home
	<input type="radio" name="fax_type" value="work"> work
	<input type="radio" name="fax_type" value="other"> other</p>
	<?php echo form_error('fax'); ?>

	
	<p><strong>Personal Notes:</strong><br/>
	<input type="text" name="note" size="80" maxlength="80" value="<?php echo set_value('note'); ?>"/>
	<?php echo form_error('note'); ?>
	

	<p><input type="submit" name="submit" value="Add Entry"></p>
	</form>


    <p><a href="<?php echo base_url(); ?>index.php/Home/index/">Back to Home Menu</a></p>
</body>
</html>