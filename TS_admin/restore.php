<?php
if (!isset($_COOKIE['admin'])) header("Location: login.php"); // user not logged in
if ( isset($_GET['file']) )
{
	switch ($_GET['file'])
	{
		case 'index':
			$filename='defaultindextemplate.html';
			$filename1='indextemplate.html';
			break;
		case 'result':
			$filename='defaultresultstemplate.html';
			$filename1='resultstemplate.html';
			break;
		case 'sitemap':
			$filename='defaultsitemaptemplate.html';
			$filename1='sitemaptemplate.html';
			break;
 	}
	$fp=fopen($filename,'r');
	$contents=fread( $fp, filesize($filename) );
	fclose($fp);
	$fp=fopen($filename1,'w');
	fwrite($fp,$contents);
	fclose($fp);
	echo "<html>
<head>
<title>".$_GET['file']." template restored</title>
<link href='style.css' type='text/css' rel='stylesheet'>
</head>
<body>
<div align='center'>
<h1>".$_GET['file']." template restored!</h1><br><br>
<a href='#' onclick='window.close();'>Close Window</a>
</form>
</div>
</body>
</html>";
}
else echo 'Nothing to do';
?>
