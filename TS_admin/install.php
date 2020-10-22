<?php
if (!isset($_COOKIE['admin'])) header("Location: login.php"); // user not logged in
$title='Robot Alert Install';
include('header.php');
if ( isset($_POST['host']) && isset($_POST['email']) && isset($_POST['emailsperday']) )
{

	$host = $_POST['host'];
	$email = $_POST['email'];
	$emailsperday = $_POST['emailsperday'];
	if ( isset($_POST['robotalert']) && $_POST['robotalert']=='Yes' ) $robotalert = 1;
	else $robotalert = 0;
	
	$fpresult = fopen("../404vars.php",'w') or die ("<font color=red>Can not write to ../404vars.php<br>Please set write permission (chmod 777) and try again.</font>");
	fwrite($fpresult,'<?php
$host = "'.$host.'";
$email = "'.$email.'";
$emailsPerDay = '.$emailsperday.';
$checkRobots = '.$robotalert.';
?>
');
	fclose ($fpresult);
	echo "Robot Alert has been installed with the following settings:<br>
	host: $host<br>
	email address: $email<br>
	emails per day: $emailsperday<br>
	robot alert: $robotalert<br>";

	echo "<a href='JavaScript: history.back(1);'>Go Back</a><br>";
}
else echo '<script Language="JavaScript">window.alert("Wrong Call!");history.back(1);</script>';
include('footer.htm');
?>
