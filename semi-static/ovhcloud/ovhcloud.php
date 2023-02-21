<?php
function ovhcloud()
{
  $html = file_get_contents('ovacloud.raw');
  $html = explode('<tr', $html);
  $arr = array();
  $d = 1;
  $r = 1;
  $o = 0;
  for ($i = 2; $i < count($html); $i++) {
    $str = $html[$i];
    $str = explode('data-plancode=', $str);
    $str = explode('td', $str[1]);
    $domena = explode('"', $str[0])[1];
    $arr[] = $domena . " ";
    for ($j = 0; $j < count($str); $j++) {
      if (str_contains($str[$j], 'data-installation-price')) {
        $cena_rej = explode('data-installation-price', $str[$j])[1];
        $cena_rej = (float) filter_var($cena_rej, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) / 100;
      }
      if (str_contains($str[$j], 'data-renew-price')) {
        $cena_odn = explode('data-renew-price', $str[$j])[1];
        $cena_odn = (float) filter_var($cena_odn, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) / 100;
        $o = 1;
      }
      if ($o) {

        $arr[] = $cena_odn . " ";
        $arr[] = $cena_rej;
      }
      $o = 0;
    }
    $arr[] = "\n";
  }
  #print_r($arr);
  file_put_contents("ovhcloud.txt", $arr);
}
ovhcloud();
