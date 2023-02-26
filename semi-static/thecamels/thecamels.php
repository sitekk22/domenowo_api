<?php
include "../curl.php";

$url = 'https://thecamels.org/domeny/';
$res = curl_get_contents($url, "", 0, 0, 0);
if ($res){
$res = explode('data-content="all"', $res)[1];
$res = explode('<tr ', $res);
$arr = array();
$linia = "";

for ($i = 1; $i < count($res); $i++) {
  $str = $res[$i];
  $str = explode('data-order="', $str);
  $str = end($str);
  $str = explode(",", $str);
  for ($j = 0; $j < count($str); $j++) {
    $s = $str[$j];
    $s = explode(':', $s);
    for ($x = 0; $x < count($s); $x++) {
      $linia = $s[$x];
      $linia = trim($linia, 'class="btn btn-secondary-with-arrow btn-order"> Zamów </a></td></tr>');
      $linia = trim($linia, ';');
      $linia = explode(';', $linia);
      $linia = end($linia);
      if (str_contains($linia, '.')) {
        $linia = explode("&", $linia);
        $linia = trim($linia[0], " zł");
        //var_dump($linia);
        if (str_contains($linia, "class")) {
          break 3;
        }
        $arr[] = $linia;
      }
    }
  }
}
$cennik = array();
for ($i = 0; $i < count($arr); $i += 3) {
  $cennik[] = trim($arr[$i] . " ", ".");
  #$cennik[] = $arr[$i] . " ";
  $cennik[] = $arr[$i + 2] . " ";
  $cennik[] = $arr[$i + 1];
  $cennik[] = "\n";
}
file_put_contents("thecamels.txt", $cennik);
}
#print_r($cennik);
