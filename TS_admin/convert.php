<?
import_request_variables('gp');

$c = '';

$f = file($nm);
for ($i=0;$i<count($f);$i++)
{
  $c .= str_replace(chr(13).chr(10), chr(13).chr(10).chr(13).chr(10).chr(13).chr(10), $f[$i]);
}

$nf = fopen($nm.".new", "w");
fwrite($nf, $c);
fclose($nf);

?>