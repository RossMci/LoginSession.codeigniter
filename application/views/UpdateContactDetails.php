<html>
    <head>
        <title>Update Contact Details</title>
    </head>
    <body>
        <h2>Update Contact Details</h2>
		<?php echo form_open('Home/getSelectedContactDetails/' . $master_id); ?>
		<?php echo $contact_details; ?>
		<p><input type="submit" name="update" value="Update Contact Details"></p>
		<p><a href="<?php echo base_url(); ?>index.php/Home/index/">Back to Home Menu</a></p>
	</form>               
</body>
</html>