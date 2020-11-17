<html>
    <head>
        <title>My Records</title>
    </head>
    <body>
        <h2>Login</h2>
        <form method="POST" action="<?php echo base_url(); ?>index.php/Login/Login_validation">
			<p><input type="text" name="emailAddress" size="20" maxlength="30" value=""/>
				<input type="text" name="password" size="30" maxlength="50" value=""/></p>
            <p><input type="submit" name="submit" value="Login"></p>
        </form>               

    </body>
</html>