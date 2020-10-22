<?php


if (file_exists("password.php")) include("password.php");


else


{


	$username='admin';


	$password='admin';


}





if (isset($_POST['username']) && isset($_POST['password']) &&($_POST['username']==$username) && ($_POST['password']==$password) )


{


	setcookie ("admin", 1);


	header('Location: index.php');


}


else


{


	$title='Admin Login';


	include('header.php');


	echo "


	<table border='0' width='100%' height='100%'>


	<tr><td width='100%' align='center' valign='middle'>


	<p align='center'>


	<form method='post'>


	Username<br>


	<input name='username' size='10'><br>


	Password<br>


	<input type='password' name='password' size='10'><br><br>


	<input class='button' type='Submit' value='Login'>


	</form>


	</p>


	</td></tr>


	</table>";


}


?>


