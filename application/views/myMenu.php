<?php
?>
<html>
<head>
<title>My Address Book</title>
</head>
<body>
<h1>My Address Book</h1>
<p><strong>Management</strong></p>
<ul>
				<!– Controller/method_within_controller -- >
 <li><a href="<?php echo site_url('Home/addentry'); ?>">Add an Entry</a><br></li>
 <li><a href="<?php echo site_url('Home/DeleteEntry'); ?>">Delete an Entry</a><br></li>
 <li><a href="<?php echo site_url('Home/UpdateEntry'); ?>">Update an Entry</a><br></li>
  <li><a href="<?php echo site_url('Login/logout'); ?>">Log out</a><br></li>
</ul>

<p><strong>Viewing</strong></p>
<ul>
 <li><a href="<?php echo site_url('Home/SelectEntry'); ?>">Select an Entry to view</a></li></ul>
</body>
</html>