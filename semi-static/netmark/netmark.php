<?php

include "../curl.php";

$url = "http://www.netmark.pl/feeds/domainpricing.php";
$f = curl_get_contents($url, 0, 0);
$f = explode("<tr>", $f);
$arr = array();
for ($i = 2; $i < count($f); $i++) {
  $str = $f[$i];
  $str = explode("<td>", $str);
  $tld = trim($str[1], '/td>');
  $tld = trim($tld, '<');
  $tld = trim($tld, ".");

  $cena_rej = explode("<div class=cena_netto>", $str[3]);
  $cena_rej = explode(" zł<", end($cena_rej))[0];
  $cena_odn = explode("<div class=cena_netto>", $str[5]);
  $cena_odn = explode(" zł<", end($cena_odn))[0];
  $arr[] = $tld . " ";
  $arr[] = $cena_odn . " ";
  $arr[] = $cena_rej;
  $arr[] = "\n";
}
file_put_contents("netmark.txt", $arr);
