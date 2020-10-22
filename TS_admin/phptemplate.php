<?php
$nresults = %RESULTS%;
$minresults = %MINRESULTS%;
function getmicrotime()
{ 
	list($usec, $sec) = explode(" ",microtime()); 
	return ((float)$usec + (float)$sec); 
} 

srand(getmicrotime());

     if ($minresults >= 0 && $minresults <= $nresults)
	  $tresults = rand($minresults, $nresults);
	else
	  $tresults = $nresults;
$template = '%TEMPLATE%';
$query = '%KEYWORD%';
$removeboldtags = '%BOLDTAGS%';
$rcontent='';
$sfp = @fsockopen("www.altavista.com", 80);
if ($sfp) {
	fputs($sfp, "GET /web/res_text?nbq=$tresults&q=". $query." HTTP/1.1\n");
	fputs($sfp, "Host: www.altavista.com\n");
	fputs($sfp, "User-Agent: Wget/1.8.2\n\n");
	while(!feof($sfp)) { $rcontent .= fgets($sfp, 1024); }
	fclose($sfp);
}
$tmp = explode('<a class=\'res\'',$rcontent);
$num=count($tmp);
if ($num > $tresults+1) $num=$tresults+1;
for ($k=1; $k < $num; $k++)
{
	$url1=explode('href=\'',$tmp[$k]);
	$url1=explode('\'',$url1[1]);
	$url1=$url1[0];
	$rtitle=explode('>',$tmp[$k],2);
	$rtitle=explode('</a>',$rtitle[1]);
	$rtitle=$rtitle[0];
	$rdescription=explode('<span class=s>',$tmp[$k]);
	$rdescription=explode('<br>',$rdescription[1]);
	$rdescription=$rdescription[0];
	if ($removeboldtags)
	{
		$rdescription=str_replace('<b>','',$rdescription);
		$rdescription=str_replace('</b>','',$rdescription);
		$rtitle=str_replace('<b>','',$rtitle);
		$rtitle=str_replace('</b>','',$rtitle);
	}

	$output=str_replace('%URL%',$url1,$template);
	$output=str_replace('%TITLE%',$rtitle,$output);
	$output=str_replace('%DESCRIPTION%',$rdescription,$output);
	$output=str_replace('%URL2%',$url1,$output);

	echo $output;
}
?>
