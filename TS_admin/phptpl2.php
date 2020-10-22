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
					$sfp = fopen("http://search.msn.com/results.aspx?q=".$query."&first=0&count=".$tresults, "r");
					if ($sfp) {
					
						while(!feof($sfp)) { $rcontent .= fgets($sfp, 2048); }
						fclose($sfp);
					}
									
					$x3 = explode('<h2>Results</h2>', $rcontent);
					$rcontent = $x3[1];
                    
					$tmp = explode('<li class="first"',$rcontent);
					$num=count($tmp);
					if ($num > $tresults+1) $num=$tresults+1;
					for ($k=1; $k < $num; $k++)
					{
						$url1=explode('<ul><li class="first">',$tmp[$k]);
						$url1=explode('</li>',$url1[1]);
						$url1=$url1[0];
						$rtitle=explode('<h3>',$tmp[$k],2);
						$rtitle=explode('</h3>',$rtitle[1]);
						$rtitle=$rtitle[0];
						if ($rtitle != "")
						{
						$rdescription=explode('<p>',$tmp[$k]);
						$rdescription=explode('</p>',$rdescription[1]);
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
					}
?>
