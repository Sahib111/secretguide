<?php


if (!isset($_COOKIE['admin'])) header("Location: login.php"); // user not logged in





if (file_exists("password.php")) include("password.php");


else


{


	$username='admin';


	$password='admin';


}





if(isset($_POST['mode']) && $_POST['mode']=='admin')


{


	$fp = fopen("password.php",'w') or die ("<p>Write permissions required to save login information</p>");


	fputs($fp,"<?php\r\n"."\$password = '".$_POST['adminpassword']."';\r\n\$username = '".$_POST['username']."';\r\n?>");


	fclose ($fp);


	$password = $_POST['adminpassword'];


	$username = $_POST['username'];


}





$title='Control Panel';


include('header.php');





if (file_exists('config.txt') && $_GET['load']==1)


{


  include 'config.txt';	


}


else


{


  include 'inicon.txt';	


}








?>


<form name='settings' method='post' enctype="multipart/form-data" action='build.php' >


<table class="menu" width="100%" bgcolor="#dddddd">


	<tr>


		<td width="50%" valign="top">


			<fieldset>


				<table cellpadding="2" cellspacing="0" width="100%">


					<tr>


						<td class="menu">


							<b>Keyword File</b><br>


							<input type="file" name="keywordfile" size="30" value="<?=$keywordfilename?>"><br>


							<b>Filename Separator</b><br>


							<select name=ssep>


							<?


							  if ($myvars['ssep'] == '-')


							    $s = 'selected';


							  else


							    $s = '';   


							?>


							  <option value='-' <?=$s?>>Hyphen</option>


							  <?


							  if ($myvars['ssep'] == '_')


							    $s = 'selected';


							  else


							    $s = '';   


							?>


							  <option value='_' <?=$s?>>Underscore</option>


							    <?


							  if ($myvars['ssep'] == 'jn')


							    $s = 'selected';


							  else


							    $s = '';   


							?>


							  <option value='jn' <?=$s?>>Joined</option>


							</select><br>


							<b>Index Template</b><br>


							<table width="100%">


								<tr>


									<td>


										<input type="button" class="button" value="Edit" onclick="JavaScript: window.open('template.php?file=index','edit','height=550,width=800,top=50,left=50,location=no,status=no,menubar=no,resizable,scrollbars'); return false;">


									</td>


									<td>


										<input type="button" class="button" value="Preview" onclick="JavaScript: window.open('indextemplate.html','preview','location=no,status=no,menubar=no,resizable,scrollbars'); return false;">


									</td>


									<td>


										<input type="button" class="button" value="Restore Default" onclick="JavaScript: window.open('restore.php?file=index','restore','height=60,width=300,top=200,left=300,location=no,status=no,menubar=no,resizable,scrollbars'); return false;">


									</td>


								</tr>


							</table>


							<b>Results Template</b><br>


							<table width="100%">


								<tr>


									<td>


										<input type="button" class="button" value="Edit" onclick="JavaScript: window.open('template.php?file=result','edit','height=550,width=800,top=50,left=50,location=no,status=no,menubar=no,resizable,scrollbars'); return false;">


									</td>


									<td>


										<input type="button" class="button" value="Preview" onclick="JavaScript: window.open('resultstemplate.html','preview','location=no,status=no,menubar=no,resizable,scrollbars'); return false;">


									</td>


									<td>


										<input type="button" class="button" value="Restore Default" onclick="JavaScript: window.open('restore.php?file=result','restore','height=60,width=300,top=200,left=300,location=no,status=no,menubar=no,resizable,scrollbars'); return false;">


									</td>


								</tr>


							</table>


							<b>Sitemap Template</b><br>


							<table width="100%">


								<tr>


									<td>


										<input type="button" class="button" value="Edit" onclick="JavaScript: window.open('template.php?file=sitemap','edit','height=550,width=800,top=50,left=50,location=no,status=no,menubar=no,resizable,scrollbars'); return false;">


									</td>


									<td>


										<input type="button" class="button" value="Preview" onclick="JavaScript: window.open('sitemaptemplate.html','preview','location=no,status=no,menubar=no,resizable,scrollbars'); return false;">


									</td>


									<td>


										<input type="button" class="button" value="Restore Default" onclick="JavaScript: window.open('restore.php?file=sitemap','restore','height=60,width=300,top=200,left=300,location=no,status=no,menubar=no,resizable,scrollbars'); return false;">


									</td>


								</tr>


							</table>


						</td>


					</tr>


				</table>


			</fieldset>


			<br>


			<fieldset>


				<table cellpadding="2" cellspacing="0" width="100%">


					<tr>


						<td class="menu">


							<b>Title</b><br>


							<input name="title" size="40" value="<?=$myvars['title']?>"><br>


							<b>Description</b><br>


							<textarea name="description" rows="5" cols="30"><?=$myvars['description']?></textarea><br>


							<b>Sponsored URL</b><br>


							<input name="url"  size="40" value="<?=$myvars['url']?>"><br>


						</td>


					</tr>


				</table>


			</fieldset>


		</td>


		<td width="50%" valign="top">


			<fieldset>


				<table cellpadding="2" cellspacing="0" width="100%">


					<tr>


						<td class="menu">


							<b>Index Name</b><br>


							<input name="indname" size="40"  value="<?=$myvars['indname']?>"><br>


							<b>Max Keywords</b><br>


							<input name="keywords" size="10"  value="<?=$myvars['keywords']?>"><br>


							<b>Min Results</b><br>


							<input name="minresults" size="10"  value="<?=$myvars['minresults']?>"><br>


							<b>Max Results</b><br>


							<input name="results" size="10" v value="<?=$myvars['results']?>"><br>


							<b>BG Color</b><br>


							<input name="bgcolor" size="10"  value="<?=$myvars['bgcolor']?>"><br>


							<b>Homepage URL</b><br>


							<input name="homepageurl" size="40"   value="<?=$myvars['homepageurl']?>"><br>


						</td>


					</tr>


				</table>


			</fieldset>


			<br>


			<fieldset>


				<table cellpadding="2" cellspacing="0" width="100%">


				    <tr>


				      <td class="menu"><b>Data Source</b><br>


				      <select name=sse>


				      <?


							  if ($myvars['sse'] == 0)


							    $s = 'selected';


							  else


							    $s = '';   


							?>


				       <option value=0  <?=$s?>>Altavista</option>


				       <?


							  if ($myvars['sse'] == 1)


							    $s = 'selected';


							  else


							    $s = '';   


							?>


				       <option value=1  <?=$s?>>Alltheweb</option>


				       <?


							  if ($myvars['sse'] == 2)


							    $s = 'selected';


							  else


							    $s = '';   


							?>


				       <option value=2 <?=$s?>>MSN Search</option>


				      </select>


				      </td>


				    </tr>


					<tr>


						<td class="menu">


							<b>Output Directory</b><br>


							<select name="sitemapname" size="1">


							<option value="">Select directory</option>


							<?php 


							$formdir = "../";


							if ($handle = opendir($formdir)) 


							{


								while (false !== ($file = readdir($handle)))


								{


									if( ($file!="admin") && ($file!=".") && ($file!="..")&& is_dir("../$file") && is_writable("../$file"))


									{

	                                    if (!file_exists($formdir.$file."/wipe.php"))
	                                    {
		                                    
		                                    if ($myvars['sitemapname'] == $file)
		                                      echo "<option value='$file' selected>$file</option>";
		                                    else
									      	  echo "<option value='$file'>$file</option>";
								      	}


									}


								}


								closedir($handle); 


							}


							?>


							</select> * 777 mode<br>


							<b>Result Pages Type</b><br>


							<table width="100%">


								<tr>


									<td width="50%">


									 <?


							  if ($myvars['pagetype'] == 'html')


							    $s = 'checked';


							  else


							    $s = '';   


							?>


										<input name="pagetype" type="radio" value="html" <?=$s?>> HTML


									</td>


										 <?


							  if ($myvars['pagetype'] == 'php')


							    $s = 'checked';


							  else


							    $s = '';   


							?>


									<td width="50%">


										<input name="pagetype" type="radio" value="php"  <?=$s?>> PHP


									</td>


								</tr>


							</table>


							<b>Result Pages Structure</b><br>


							<table width="100%">


								<tr>		 <?


							  if ($myvars['structure'] == 'tree')


							    $s = 'checked';


							  else


							    $s = '';   


							?>


									<td width="50%">


										<input name="structure" type="radio" value="tree" <?=$s?>> Multiple Folders


									</td>


									 <?


							  if ($myvars['structure'] == 'plain')


							    $s = 'checked';


							  else


							    $s = '';   


							?>


									<td width="50%">


										<input name="structure" type="radio" value="plain"  <?=$s?>> Multiple Indexes


									</td>


								</tr>


							</table>


							<input type=hidden name=removeboldtags value="YES"></input>


							<b>Inital Number of Pages</b><br>


							<input name="initnumpages" type="text" value="<?=$myvars['initnumpages']?>" size="4"></input><br>


							<b>Cron Number of Pages</b><br>


							<input name="cronnumpages" type="text" value="<?=$myvars['cronnumpages']?>" size="4"></input><br>
							
							


						</td>


					</tr>


				</table>


			</fieldset>


			<br>


			<input type=hidden name='disablepopup' value='no'></input>


			


		</td>


	</tr>


	<tr>


		<td width="100%" colspan="2" align="center">


			<hr>


			<input type="button" class="button" value="Create Pages" onclick=' if (validate())  { document.settings.firstrun.value=1; document.settings.submit(); }'></input>


			<input type=hidden name=save value=0></input>
			
			<input type=hidden name=firstrun value=0></input>


			<input type=hidden name=load value=0></input>


			<input type=hidden name=reset value=0></input>

<?
			/* 
			&nbsp;<input type=button style="height: 19px; font-family: Verdana, Arial; color: #000000; font-size: 9pt; font-weight: normal; border: 1 solid #000000; background-color: #C8C4BF;" onclick='if (validate()) { document.settings.save.value=1; document.settings.submit(); }' value='Save Cron Settings'></input>

			
			&nbsp;<input type=button style="height: 19px; font-family: Verdana, Arial; color: #000000; font-size: 9pt; font-weight: normal; border: 1 solid #000000; background-color: #C8C4BF;" onclick='document.settings.load.value=1; document.settings.submit(); ' value='Load Cron Settings'></input>
*/ 
?>

			&nbsp;<input type=button style="height: 19px; font-family: Verdana, Arial; color: #000000; font-size: 9pt; font-weight: normal; border: 1 solid #000000; background-color: #C8C4BF;" onclick='document.settings.reset.value=1; document.settings.submit(); ' value='Reset'></input>




		</td>


	</tr>


	<tr>


		<td width="100%" colspan="2" align="center">


			<hr>


			<input type="button" class="button" value="Wipe directory"  onClick="JavaScript: self.location = 'wipe.php';"><br>&nbsp;


		</td>


	</tr>


	<tr>


		<td width="100%" colspan="2" align="center">


		<fieldset>


			<form name="admsettings" method="post" action="index.php">


			<input type="hidden" name="mode" value="admin">


			<table cellpadding="2" cellspacing="0" width="100%">


				<tr>


					<td class="menu">


						<b>Username</b><br>


						<input name="username" size="40" value="<?php echo $username;?>"><br>


						<b>Password</b><br>


						<input name="adminpassword" size="40" value="<?php echo $password;?>"><br>


					</td>


				</tr>


				<tr>


					<td class="menu" align="center">


					


						<input type="Submit" class="button" value="Update"><br>


					</td>


				</tr>


			</table>


			</form>


			</fieldset>


		</td>


	</tr>


</table>




