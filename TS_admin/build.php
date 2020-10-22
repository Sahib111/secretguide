<?php
ob_start();
set_time_limit(0);
import_request_variables('gp');
error_reporting(E_ERROR);

if ($_POST['load'] == 1)
{
  header('Location: index.php?load=1');
  exit;	
}

if ($_POST['reset'] == 1)
{
  header('Location: index.php?reset=1');
  exit;	
}



function getmicrotime()
{ 
	list($usec, $sec) = explode(" ",microtime()); 
	return ((float)$usec + (float)$sec); 
} 

srand(getmicrotime());





if ( ($_POST["save"] == 1 && $cron != 1) || ($firstrun == 1))
{
   $c .= '<?'."\n";	
   $c .= '$'."keywordfilename = \"".$_FILES["keywordfile"]["name"]."\";\n";
   
   foreach($_POST as $k=>$v)
   {
	     if ($k != "save")
	       $c .= '$'."myvars['$k'] = \"".addslashes($v)."\";\n";
   }	
   
   $c .= '?>';
   
   move_uploaded_file($_FILES['keywordfile']['tmp_name'],"keywords.txt");

   
   $f = fopen("config.txt", "w");
   fwrite($f, $c);
   fclose($f);
   
   if ($firstrun != 1 )
   {
     print "<script language='JavaScript'>alert('The configuration has been saved.'+\"\\n\"+'You can now run the cron script.'+\"\\n\"+'Please do not delete the keyword file.', 'Information');location.href='index.php?load=1';</script>";
     exit;
   }
	
}

if ($firstrun == 1)
{
  $_GET["cron"] = 1;
  	
}

if ($_GET["cron"] == 1)
{
   include ('config.txt');
   $cron = 1;
   
   foreach($myvars as $k=>$v)
   {
	   
	   $_POST[$k] = stripslashes($v);   
	   
   }
   
   
   if ($firstrun == 1)
   {
	  $cronnumpages   = $initnumpages;
	  $_POST["cronnumpages"] = $_POST["initnumpages"];
   }
   
   	
	
	
}


if ( (isset($_FILES['keywordfile']['size']) && $_FILES['keywordfile']['size'] > 0) || $cron == 1 )
{
	if (!isset($_COOKIE['admin']) && $cron != 1) header("Location: login.php"); // user not logged in
//	$title='Page Generation';
//	include('header.php');

    if ($cron == 1)
    {
	    copy($keywordfilename, "keywords.txt");
    }
    else
    {
	  move_uploaded_file($_FILES['keywordfile']['tmp_name'],"keywords.txt") or die ("<font color=red>Error opening ". $_FILES['keywordfile']['tmp_name'] ."<br></font>");
	  move_uploaded_file($_FILES['keywordfile']['tmp_name'], $keywordfilename);
    }

	$content = file ("keywords.txt");

	$title = $_POST['title'];
	$description = $_POST['description'];
	$url = $_POST['url'];
	$indname = $_POST['indname'];
	$nkeywords = $_POST['keywords'];
	$nresults = $_POST['results'];
	$minresults = $_POST['minresults'];
	

	
	$nindexes = ceil(count($content)/$nkeywords);
	$bgcolor = $_POST['bgcolor'];
	$homepageurl = $_POST['homepageurl'];
	$sitemapdir = $_POST['sitemapname'];
	$popupurl = $_POST['popupurl'];
	$w = $_POST['w'];
	$h = $_POST['h'];
	if ( isset($_POST['disablepopup']) && ($_POST['disablepopup']=='YES') ) $popup = 0;
	else $popup = 1;

	if ( isset($_POST['pagetype']) && ($_POST['pagetype']=='html') ) $pagetype = 0;
	else $pagetype = 1;

	if ( isset($_POST['structure']) && ($_POST['structure']=='plain') ) $structure = 0;
	else $structure = 1;

	if ( isset($_POST['removeboldtags']) && ($_POST['removeboldtags']=='YES') ) $removeboldtags = 1;
	else $removeboldtags = 0;

	set_time_limit(3600);
	$time_start = getmicrotime();

	if(!file_exists("../$sitemapdir/"))
	{
		mkdir ("../$sitemapdir/") or die("<font color=red>Can not create ../$sitemapdir/<br></font>");
		chmod( "../$sitemapdir/", 0755 ); 
	}
	else if ($cron != 1) 
	{
		echo "Cleaning old files ...<br>";
		$current=1;
		$userdir = "../$sitemapdir/$current/";
		while ($handle = @opendir($userdir)) 
		{
    		while (false !== ($file = readdir($handle)))
	    	{
        		if(($file!=".")&&($file!=".."))
        		{
    	    		echo "File $userdir$file :";
	        		if(unlink($userdir.$file))
	        			echo "deleted.<br>";
	        		else
	    	    		echo "permissions denied.<br>";
		        }
	    	}
	    	closedir($handle);
		    echo "Directory $userdir :";
		    if (rmdir($userdir)) echo'deleted.<br>';
	    	else echo 'permissions denied.<br>';
			$current++;
			$userdir = "../$sitemapdir/$current/";
		}	
	}

	$current=0;
	$newcurrent = 0;

	$fp=fopen('sitemaptemplate.html','r');
	$datasitemap=fread($fp,filesize ('sitemaptemplate.html'));
	fclose($fp);

	$fp=fopen('indextemplate.html','r');
	$dataindex=fread($fp,filesize ('indextemplate.html'));
	fclose($fp);
	
	$fp=fopen('resultstemplate.html','r');
	$dataresults=fread($fp,filesize ('resultstemplate.html'));
	fclose($fp);
	
	
	if ($_POST["sse"] == 0)
	{
	  $pname = 'phptemplate.php';
    }
    else
    if ($_POST["sse"] == 1)
	{
	  $pname =  'phptpl1.php';
    }
    if ($_POST["sse"] == 2)
	{
	  $pname = 'phptpl2.php';
    }
  
	$fp=fopen($pname,'r');
    $phpdata=fread($fp,filesize ($pname));
	fclose($fp);
	
	// read sitemap template
	$tmp = explode('<!--REPEATBEGIN-->',$datasitemap);
	$sitemapheader = $tmp[0];
	$tmp = explode('<!--REPEATEND-->',$tmp[1]);
	$sitemapcode = $tmp[0];
	$sitemapfooter = $tmp[1];
	// make header and footer
	$sitemapheader=str_replace('%BGCOLOR%',$bgcolor,$sitemapheader);
	$sitemapfooter=str_replace('%URL%',$homepageurl,$sitemapfooter);

	$tmp = explode('<!--REPEATBEGIN-->',$dataindex);
	$indexheader = $tmp[0];
	$tmp = explode('<!--REPEATEND-->',$tmp[1]);
	$indexcode = $tmp[0];
	$indexfooter = $tmp[1];
	// make header and footer
	$indexheader=str_replace('%INDEXNAME%', $indname, $indexheader);
	$indexheader=str_replace('%BGCOLOR%', $bgcolor, $indexheader); // %NUMBER% will be changed later
	$indexfooter=str_replace('%URL%', $homepageurl, $indexfooter);
	if ($structure)
		$indexfooter=str_replace('%SITEMAP%', "../index.html", $indexfooter);
	else
		$indexfooter=str_replace('%SITEMAP%', "index.html", $indexfooter);

	$tmp = explode('<!--REPEATBEGIN-->',$dataresults);
	$resultsheader = $tmp[0];
	$tmp = explode('<!--REPEATEND-->',$tmp[1]);
	$resultscode = $tmp[0];
	$resultsfooter = $tmp[1];
	// make header and footer
	$resultsheader=str_replace('%BGCOLOR%', $bgcolor, $resultsheader);
	if ($popup)
	{
		$resultsheader=str_replace('%POPUP%',  ' onunload="popup(\''.$popupurl.'\')"', $resultsheader);
		$resultsheader=str_replace('%POPUPCODE%', '
<script language="JavaScript">
<!--begin
var exit=true;
function popup(filename)
{if(exit)
{var popup = window.open(filename, "","height='.$h.',width='.$w.',top=0,left=0,location=no,menubar=no,resizable=yes,scrollbars=yes,status=no,titlebar=no,toolbar=no,directories=no");
self.focus();}
}
// end -->
</script>', $resultsheader);
	}
	else
	{
		$resultsheader=str_replace('%POPUP%',  '', $resultsheader);
		$resultsheader=str_replace('%POPUPCODE%', '', $resultsheader);
	}
	$resultsfooter=str_replace('%URL%',$homepageurl,$resultsfooter);

	$fpsitemap = fopen("../$sitemapdir/index.html",'w') or die ("<font color=red>Can not create ../$sitemapdir/index.html<br></font>");
	fwrite($fpsitemap,stripslashes($sitemapheader));
    $condi = true;
	for ($i=1 ; $i<=$nindexes && $condi == true; $i++) // process all folders
	{
		//create directory $i
		if(!file_exists("../$sitemapdir/$i/") && $structure)
		{
			echo "<br>Mkdir ../$sitemapdir/$i/  : ";
			mkdir ("../$sitemapdir/$i/",0755) or die("<font color=red>Can not create ../$sitemapdir/$i/<br></font>");
			chmod( "../$sitemapdir/$i/", 0755 ); 
		echo "done<br><br>";
		}
		// add link to the sitemap file
		$tmp=str_replace('%NUMBER%',$i,$sitemapcode);
		$tmp=str_replace('%INDEXNAME%',$indname,$tmp);
		if($structure)
			$tmp=str_replace('%INDEXURL%',"$i/index.html",$tmp);
		else
			$tmp=str_replace('%INDEXURL%',"index$i.html",$tmp);
		fwrite($fpsitemap,stripslashes($tmp));

		if($structure)
			$fpindex = fopen("../$sitemapdir/$i/index.html",'w') or die ("<font color=red>Can not create ../$sitemapdir/$i/index.html<br></font>");
		else
			$fpindex = fopen("../$sitemapdir/index$i.html",'w') or die ("<font color=red>Can not create ../$sitemapdir/index$i.html<br></font>");
		//make index header
		$tmp=str_replace('%NUMBER%',$i,$indexheader);
		fwrite($fpindex,stripslashes($tmp));
		for($j=0; $j<$nkeywords; $j++)
		{			
			if ($cron == 1)
			{
			   if ($newcurrent < $_POST["cronnumpages"])
			     $condi = true;
			   else
			   {
			     $condi = false;  	
			     print "<br>Generated $newcurrent, cron limit is $_POST[cronnumpages].<br>";
			     break;
		       }
			}
			
			
			if ($current < count($content) && $condi)
			{
				$keyword=trim($content[$current]);
				if ($_POST["ssep"] == "-")
				  $keywordlink=str_replace(" ", "-",$keyword);
				else  
				if ($_POST["ssep"] == "_")
				  $keywordlink=str_replace(" ", "_",$keyword);
				else  
				if ($_POST["ssep"] == "jn")
				  $keywordlink=str_replace(" ", "",$keyword);  
				  
				  
				$keywordquery=str_replace(" ", "+",$keyword);
				$keywordcaps= ucwords($keyword);
				echo "Processing keyword $keyword... ";
				
				if ($pagetype)
				{
					// add link to the index file
					$tmp=str_replace('%KEYWORDURL%', "$keywordlink.php", $indexcode);
					$tmp=str_replace('%KEYWORD%', $keyword, $tmp);
					$tmp=str_replace('%KEYWORDPLUS%', $keywordquery, $tmp);
					$tmp=str_replace('%KEYWORDCAPS%', $keywordcaps, $tmp);
					fwrite($fpindex,stripslashes($tmp));

					if($structure)
					{
					   if ($cron == 1 && file_exists("../$sitemapdir/$i/$keywordlink.php") && filesize("../$sitemapdir/$i/$keywordlink.php") > 10)
						{
						  $current++;
						  print "existing found.<br>";
						  continue;
						  
					    }
						else 
					  	  $fpresult = fopen("../$sitemapdir/$i/$keywordlink.php",'w') or die ("<font color=red>Can not create ../$sitemapdir/$i/$keywordlink.php<br></font>");
				  	}
					else
					{
						if ($cron == 1 && file_exists("../$sitemapdir/$keywordlink.php") && filesize("../$sitemapdir/$keywordlink.php") > 10)
						{
						  $current++;
						  print "existing found.<br>";
						  continue;
						  
					    }
						else 
						  $fpresult = fopen("../$sitemapdir/$keywordlink.php",'w') or die ("<font color=red>Can not create ../$sitemapdir/$keywordlink.php<br></font>");
					}
					// make result page header
					$tmp=str_replace('%KEYWORD%', $keyword, $resultsheader);
					$tmp=str_replace('%KEYWORDPLUS%', $keywordquery, $tmp);
					$tmp=str_replace('%KEYWORDCAPS%', $keywordcaps, $tmp);
					$tmp=str_replace('%URL%', $url, $tmp);
					$tmp=str_replace('%TITLE%', $title, $tmp);
					$tmp=str_replace('%DESCRIPTION%', $description, $tmp);
					fwrite($fpresult,$tmp);
					
					
					
					$tmp = str_replace('%MINRESULTS%', $minresults , $phpdata);
					$tmp = str_replace('%RESULTS%', $nresults , $tmp);
					$tmp = str_replace('%TEMPLATE%', $resultscode , $tmp);
					$tmp = str_replace('%KEYWORD%', $keywordquery , $tmp);
					$tmp = str_replace('%BOLDTAGS%', $removeboldtags , $tmp);
					fwrite($fpresult,$tmp);
					// make result page footer
					$tmp=str_replace('%KEYWORD%', $keyword, $resultsfooter);
					$tmp=str_replace('%KEYWORDPLUS%', $keywordquery, $tmp);
					$tmp=str_replace('%KEYWORDCAPS%', $keywordcaps, $tmp);
					$tmp=str_replace('%URL%', $url, $tmp);
					$tmp=str_replace('%TITLE%', $title, $tmp);
					$tmp=str_replace('%DESCRIPTION%', $description, $tmp);
					if($structure)
						$tmp=str_replace('%N%', '', $tmp);
					else
						$tmp=str_replace('%N%', $i, $tmp);
					fwrite($fpresult,stripslashes($tmp));
					fclose ($fpresult);
					if($structure)
						chmod("../$sitemapdir/$i/$keywordlink.php",0755);
					else
						chmod("../$sitemapdir/$keywordlink.php",0755);
				}
				else
				{
				    if ($minresults >= 0 && $minresults <= $nresults)
	                   $tresults = rand($minresults, $nresults);
                    else
	                   $tresults = $nresults;  
					
					// add link to the index file
					$tmp=str_replace('%KEYWORDURL%', "$keywordlink.html", $indexcode);
					$tmp=str_replace('%KEYWORD%', $keyword, $tmp);
					$tmp=str_replace('%KEYWORDPLUS%', $keywordquery, $tmp);
					$tmp=str_replace('%KEYWORDCAPS%', $keywordcaps, $tmp);
					fwrite($fpindex,stripslashes($tmp));

					
					
					
					if($structure)
					{
						
						if ($cron == 1 && file_exists("../$sitemapdir/$i/$keywordlink.html") && filesize("../$sitemapdir/$i/$keywordlink.html") > 10)
						{
						  $current++;
						  print "existing found.<br>";
						  continue;
						  
					    }
						else  
						  $fpresult = fopen("../$sitemapdir/$i/$keywordlink.html",'w') or die ("<font color=red>Can not create ../$sitemapdir/$i/$keywordlink.html<br></font>");
					}
					else
					{
						if ($cron == 1 && file_exists("../$sitemapdir/$keywordlink.html") && filesize("../$sitemapdir/$keywordlink.html") > 10)
						{
					      $current++;
					      print "existing found.<br>";
						  continue;
						  
					    }
						else 
						  $fpresult = fopen("../$sitemapdir/$keywordlink.html",'w') or die ("<font color=red>Can not create ../$sitemapdir/$keywordlink.html<br></font>");
			     	}	
					// make result page header
					$tmp=str_replace('%KEYWORD%', $keyword, $resultsheader);
					$tmp=str_replace('%KEYWORDPLUS%', $keywordquery, $tmp);
					$tmp=str_replace('%KEYWORDCAPS%', $keywordcaps, $tmp);
					$tmp=str_replace('%URL%', $url, $tmp);
					$tmp=str_replace('%TITLE%', $title, $tmp);
					$tmp=str_replace('%DESCRIPTION%', $description, $tmp);
					fwrite($fpresult,$tmp);
                    
					
					if ($_POST["sse"] == 0)
					{  
					
					// make result page body
					$rcontent='';
					$sfp = @fsockopen("www.altavista.com", 80);
					if ($sfp) {
						fputs($sfp, "GET /web/res_text?nbq=$tresults&q=".$keywordquery." HTTP/1.1\n");
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
							$rdescription=str_replace('<strong>','',$rdescription);
							$rdescription=str_replace('</strong>','',$rdescription);
							$rtitle=str_replace('<strong>','',$rtitle);
							$rtitle=str_replace('</strong>','',$rtitle);
						}

						$output=str_replace('%URL%',$url1,$resultscode);
						$output=str_replace('%TITLE%',$rtitle,$output);
						$output=str_replace('%DESCRIPTION%',$rdescription,$output);
						$output=str_replace('%URL2%',$url1,$output);
						fwrite($fpresult,$output);
					}
				    } 
				    else if ($_POST["sse"] == 1)
				    {
					
					      $rcontent='';
					$sfp = @fsockopen("www.alltheweb.com", 80);
					if ($sfp) {
						fputs($sfp, "GET /search?cat=web&q=".$keywordquery."&rys=0&o=0&hits=".$tresults." HTTP/1.1\n");
						fputs($sfp, "Host: www.alltheweb.com\n");
						fputs($sfp, "User-Agent: Wget/1.8.2\n\n");
						while(!feof($sfp)) { $rcontent .= fgets($sfp, 1024); }
						fclose($sfp);
					}
					
					
                    
					$tmp = explode('<a class="res"',$rcontent);
					$num=count($tmp);
					if ($num > $tresults+1) $num=$tresults+1;
					for ($k=1; $k < $num; $k++)
					{
						$url1=explode('href="',$tmp[$k]);
						$url1=explode('"',$url1[1]);
						$url1=$url1[0];
						$rtitle=explode('>',$tmp[$k],2);
						$rtitle=explode('</a>',$rtitle[1]);
						$rtitle=$rtitle[0];
						$rdescription=explode('<span class="resTeaser">',$tmp[$k]);
						$rdescription=explode('<br>',$rdescription[1]);
						$rdescription=$rdescription[0];
						if ($removeboldtags)
						{
							$rdescription=str_replace('<b>','',$rdescription);
							$rdescription=str_replace('</b>','',$rdescription);
							$rtitle=str_replace('<b>','',$rtitle);
							$rtitle=str_replace('</b>','',$rtitle);
							$rdescription=str_replace('<strong>','',$rdescription);
							$rdescription=str_replace('</strong>','',$rdescription);
							$rtitle=str_replace('<strong>','',$rtitle);
							$rtitle=str_replace('</strong>','',$rtitle);
						}

						$output=str_replace('%URL%',$url1,$resultscode);
						$output=str_replace('%TITLE%',$rtitle,$output);
						$output=str_replace('%DESCRIPTION%',$rdescription,$output);
						$output=str_replace('%URL2%',$url1,$output);
						fwrite($fpresult,$output);
					}
					  
				    }
				    else if ($_POST["sse"] == 2)
				    {
				
						$rcontent='';
						//echo "http://search.msn.com/results.aspx?q=".$keywordquery."&first=0&count=".$tresults."<br/>";
						$sfp = fopen("http://search.msn.com/results.aspx?q=".$keywordquery."&first=0&count=".$tresults, "r");
						
						if ($sfp) {
						
							while(!feof($sfp))
							{ 
								$rcontent .= fgets($sfp, 2048); 
								
							}
							fclose($sfp);
							//$abc = explode("<div id=\"results_area\">",$rcontent);
							//echo $abc[0]; 
						}
										
						$x3 = explode("<ul id=\"wg0\" class=\"sb_results\">", $rcontent);
						$rcontent = $x3[1];
						$x3 = explode("<div class=\"qscontainer\">", $rcontent);
						$rcontent = $x3[0];
					    $rcontent = str_replace("class=\"sa_cc\"","style=\"max-width:650px;\"",$rcontent);
						$rcontent = str_replace("class=\"sb_tlst\"","style=\"margin:0 0 0.05em;\"",$rcontent);
						$rcontent = str_replace("class=\"sa_cpt\"","style=\"display:inline-block;height:1px;outline-color:-moz-use-text-color;outline-style:none;outline-width:medium;width:15px;\"",$rcontent);
						$rcontent = str_replace("class=\"sp_pss\"","style=\"color:#525051;list-style-type:none;padding:0;margin:0;\"",$rcontent);
						$rcontent = str_replace("class=\"sb_meta\"","style=\"line-height:1.2em;color:#388222;\"",$rcontent);
						$rcontent = str_replace("<p>","<p style=\"margin:0;padding:0;\">",$rcontent);
						$rcontent = str_replace("<h3>","<h4>",$rcontent);
						$rcontent = str_replace("</h3>","</h4>",$rcontent);
						$rcontent = str_replace("<strong>","",$rcontent);
						$rcontent = str_replace("</strong>","",$rcontent);
						$rcontent = str_replace("<div class=\"sb_tlst\">","<div style=\"list-style-type:none;\">",$rcontent);
						$rcontent = "<div><ul style=\"list-style-type:none;padding:0;margin:0;\">".$rcontent;
					
						
						
						fwrite($fpresult,$rcontent);
						/*
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
									$rdescription=str_replace('<strong>','',$rdescription);
									$rdescription=str_replace('</strong>','',$rdescription);
									$rtitle=str_replace('<strong>','',$rtitle);
									$rtitle=str_replace('</strong>','',$rtitle);
									
								}
		
								$output=str_replace('%URL%',$url1,$resultscode);
								$output=str_replace('%TITLE%',$rtitle,$output);
								$output=str_replace('%DESCRIPTION%',$rdescription,$output);
								$output=str_replace('%URL2%',$url1,$output);
								fwrite($fpresult,$output);
							}
						}*/
						
				    }
					
					// make result page footer
					$tmp=str_replace('%KEYWORD%', $keyword, $resultsfooter);
					$tmp=str_replace('%KEYWORDPLUS%', $keywordquery, $tmp);
					$tmp=str_replace('%KEYWORDCAPS%', $keywordcaps, $tmp);
					$tmp=str_replace('%TITLE%', $title, $tmp);
					$tmp=str_replace('%DESCRIPTION%', $description, $tmp);
					if($structure)
						$tmp=str_replace('%N%', '', $tmp);
					else
						$tmp=str_replace('%N%', $i, $tmp);
					fwrite($fpresult,stripslashes($tmp));
					fclose ($fpresult);
					if($structure)
						chmod("../$sitemapdir/$i/$keywordlink.html",0755);
					else
						chmod("../$sitemapdir/$keywordlink.html",0755);
				}
				$current++;
				$newcurrent++;
				
				echo "done!<br>";
			}
		}
		// make footer of the index file
		fwrite($fpindex,stripslashes($indexfooter)); // write footer
		fclose ($fpindex);
		if($structure)
			chmod("../$sitemapdir/$i/index.html",0755);
		else
			chmod("../$sitemapdir/index$i.html",0755);
	}
	fwrite($fpsitemap,stripslashes($sitemapfooter)); // write footer
	fclose ($fpsitemap);
	chmod("../$sitemapdir/index.html",0755);
	$time_end = getmicrotime();
	$time = number_format($time_end - $time_start,2);
	echo "<br>write $current pages in $time sec<br><br>";
	echo "<a href='../$sitemapdir/index.html'>Check Results</a> <a href='index.php'>Go Back</a><br>&nbsp;<br>";
//	include('footer.htm');

//	$fp = fopen("settings.php","w");
//	fclose($fp);
//	chmod("settings.php",0777);
//	$fp = fopen("keywords.txt","w");
//	fclose($fp);
//	chmod("keywords.txt",0777);
}
else echo '<script Language="JavaScript">window.alert("Please upload keyword file!");history.back(1);</script>';
?>
