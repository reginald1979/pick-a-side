<?php
	include_once 'api/config/database.php';
	include_once 'api/objects/user.php';
	
	session_start();
	// get database connection
	$database = new Database();
	$db = $database->getConnection();
	$message = "";
	$user = new User($db);
	
	if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
		header("Location: poll_setup.php");
	}
	
	if (isset($_POST["submit"])) {
		$username = $_POST["username"];
		$password = $_POST["password"];
		
		if($user->login($username, $password)) {
			$_SESSION['loggedin'] = true;
			header("Location: poll_setup.php");
		}
		else {
			$message = "* Incorrect login info, <br />you need to login to view this page";
		}
		
	}
	
 
?>
<html>
<head>
<title>
</title>
<link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div style="width:100%;">
<img src="images/logo.png" style="display:block; margin: 0 auto; width:50%" />
</div>
<div style="width:100%;">
<p style="margin: 16px auto; width:50%; text-align:center; font-size:22px;">Login to start a poll</p>
<div style="margin: 16px auto; width:50%; text-align:center; padding-top:1px;">
<form method="post" action="admin.php">
	<table>
		<tr><td style="text-align:right; font-size:22px;">User Name:</td><td><input type="text" style="font-size:22px;" name="username"></td></tr>
		<tr><td style="text-align:right; font-size:22px;">Password:</td><td><input type="password" style="font-size:22px;" name="password"></td></tr>
		<tr><td style="text-align:center; font-size:22px;" colspan="2"><span style="color:red; font-size:22px;"><?php echo $message ?></span></td></tr>
		<tr><td colspan="2" style="text-align:center; font-size:22px;"><input type="submit" name="submit" id="submit" style="font-size:22px;" value="Login" class="btn btn-success"/></td></tr>
	</table>
</form>
</div>
</div>

</body>
</html>