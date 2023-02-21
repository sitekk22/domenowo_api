<?php
function curl_get_contents($url, $return_header, $nobody)
{
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
  curl_setopt($ch, CURLOPT_HEADER, $return_header);
  curl_setopt($ch, CURLOPT_NOBODY, $nobody);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  #curl_setopt($ch, CURLOPT_VERBOSE, true);
  $data = curl_exec($ch);
  curl_close($ch);
  return $data;
}
