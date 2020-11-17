<?php

   //if (isset($contact_details)){
	//	    echo $contact_details;			
//	}   
?>

<html>
    <head>
        <title>Update Contact Details</title>
    </head>
    <body>
        <h2>Update Contact Details</h2>
    
		<?php 
	//		if ((isset($contact_details))){
		//		echo form_open("Home/UpdateSelectedContact/contact_detail");
			//} 
			//else {
				echo form_open("Home/UpdateEntry");
		//	}
		?>	       
        <p><strong>Select a Record to Update:</strong><br/>
                <select name="master_id">
                    <?php if (!isset($_POST['sumbit'])) {
						echo $display_block; }
					?>

                </select>
            <p><input type="submit" name="submit" value="Select a Contact to Update"></p>
            
              <p><a href="<?php echo base_url(); ?>index.php/Home/index/">Back to Home Menu</a></p>
        </form>               

    </body>
</html>