<?php

function dhosting()
{
  $f = file_get_contents("dhosting.raw");
  if ($f){
  $f = explode("\n", $f);
  $arr = array();
  for ($i = 0; $i < count($f); $i++) {
    $str = $f[$i];
    if (!str_contains($str, "STRONA")) {
      $str = trim($str, 'Domena ');
      $str = trim($str, '.');
      $arr[] = $str;
    }
  }
  $plik = array();
  for ($i = 0; $i < count($arr); $i++) {
    $str = explode(" ", $arr[$i]);
    if (count($str) > 0) {
      $tld = $str[0];
      print_r($str);
      $rej = str_replace(',', '.', $str[1]);
      $odn = str_replace(',', '.', $str[3]);
      $plik[] = $tld . " ";
      $plik[] = $odn . " ";
      $plik[] = $rej . "\n";
    }
  }


  #file_put_contents("dhosting.txt", $plik);
  $reg = file_get_contents("regionalne.raw");
  $reg = explode("\n", $reg);
  $regionalne = array();
  for ($i = 0; $i < count($reg); $i++) {
    $str = $reg[$i];
    if (strlen($str) > 0) {
      $regionalne[] = $str . " ";
      $regionalne[] = "79.00 ";
      $regionalne[] = "9.90\n";
    }
  }
  file_put_contents("regionalne.txt", $regionalne);

  $fun = file_get_contents("funkcjonalne.raw");
  $fun = explode("\n", $fun);
  $funkcjonalne = array();
  for ($i = 0; $i < count($fun); $i++) {
    $str = $fun[$i];
    if (strlen($str) > 0) {
      $funkcjonalne[] = $str . " ";
      $funkcjonalne[] = "99.00 ";
      $funkcjonalne[] = "9.90\n";
    }
  }
  file_put_contents("funkcjonalne.txt", $funkcjonalne);}
}

#dhosting();
