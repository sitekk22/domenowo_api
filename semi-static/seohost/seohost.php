<?php
$url = 'https://seohost.pl/cennik-domen';
include "../curl.php";
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
  // error was suppressed with the @-operator
  if (0 === error_reporting()) {
    return false;
  }

  throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});
$f = curl_get_contents($url, 0, 0);
$f = explode('id="prices-netto" ', $f)[1];
$f = explode("</tbody>", $f)[0];
$f = explode("<tr>", $f);
$arr = array();
for ($i = 3; $i < count($f); $i++) {
  $str = $f[$i];
  $str = explode("<td>", $str);
  $tld = trim($str[1], '/td>');
  $tld = trim($tld, '<');
  $tld = trim($tld, ".");
  $cena_rej = trim($str[2], " zł</td>");
  $cena_odn = trim($str[3], " zł</td>");
  $arr[] = $tld . " ";
  $arr[] = $cena_odn . " ";
  $arr[] = $cena_rej;
  $arr[] = "\n";
}
file_put_contents('seohost.txt', $arr);
#print_r($f[count($f) - 1]);
