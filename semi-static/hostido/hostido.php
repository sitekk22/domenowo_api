<?php
  $url = "https://hostido.pl/api/domains?draw=2&columns[0][data]=id&columns[0][name]=id&columns[0][searchable]=false&columns[0][orderable]=true&columns[0][search][value]=&columns[0][search][regex]=false&columns[1][data]=tld&columns[1][name]=tld&columns[1][searchable]=true&columns[1][orderable]=false&columns[1][search][value]=&columns[1][search][regex]=false&columns[2][data]=price_our_register&columns[2][name]=info&columns[2][searchable]=false&columns[2][orderable]=false&columns[2][search][value]=&columns[2][search][regex]=false&columns[3][data]=price_our_register_promo&columns[3][name]=price_our_register_promo&columns[3][searchable]=false&columns[3][orderable]=false&columns[3][search][value]=&columns[3][search][regex]=false&columns[4][data]=price_provider_register_promo_details&columns[4][name]=price_provider_register_promo_details&columns[4][searchable]=false&columns[4][orderable]=false&columns[4][search][value]=&columns[4][search][regex]=false&columns[5][data]=price_our_renew&columns[5][name]=info&columns[5][searchable]=true&columns[5][orderable]=false&columns[5][search][value]=&columns[5][search][regex]=false&columns[6][data]=price_our_transfer&columns[6][name]=info&columns[6][searchable]=false&columns[6][orderable]=false&columns[6][search][value]=&columns[6][search][regex]=false&columns[7][data]=tld&columns[7][name]=info&columns[7][searchable]=false&columns[7][orderable]=false&columns[7][search][value]=&columns[7][search][regex]=false&columns[8][data]=offer_domain_group_id&columns[8][name]=offer_domain_group_id&columns[8][searchable]=true&columns[8][orderable]=false&columns[8][search][value]=&columns[8][search][regex]=true&columns[9][data]=provider&columns[9][name]=provider&columns[9][searchable]=true&columns[9][orderable]=true&columns[9][search][value]=&columns[9][search][regex]=false&columns[10][data]=position&columns[10][name]=position&columns[10][searchable]=false&columns[10][orderable]=true&columns[10][search][value]=&columns[10][search][regex]=false&order[0][column]=8&order[0][dir]=asc&order[1][column]=8&order[1][dir]=asc&start=0&length=-1&search[value]=&search[regex]=false&_=1685779760301";

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
  $file = [];
  $res = curl_get_contents($url, 0, 0);
  $res = explode("},{", $res);
  foreach ($res as $line) {
    $line= explode(",", $line);
    #print_r($line);
    $reg_price = null;
    $ren_price = null;
    $promo_price = null;
    $tld=null;
    foreach ($line as $item){
      if (str_contains($item, "tld")) {
        $tld = explode(":", $item);
        $tld = trim(end($tld),'"');
      }
      if (str_contains($item, "price_our_register\"")) {
        $reg_price  = explode(":", $item);
        $reg_price = trim(end($reg_price),'"');
      }
      if (str_contains($item, "price_our_register_promo") &&!str_contains($item, "price_our_register_promo\":null")) {
        $promo_price = explode(":", $item);
        $promo_price = trim(end($promo_price),'"');
        
        #print_r("\nPROMO:".$promo_price."\n");
      }
      if ($promo_price) {
        $reg_price=$promo_price;
      }
      if (str_contains($item, "price_our_renew")) {
        $ren_price  = explode(":", $item);
        $ren_price = trim(end($ren_price),'"');
      }
      
      if ($reg_price !=null && $ren_price !=null  && $tld != null) {
        #print_r("\ntld:".$tld);
        #print_r("\nreg:".$reg_price);
        #print_r("\nren:".$ren_price."\n");
        $file[] = "$tld $ren_price $reg_price\n";
        break;
      } 
      
    }
    
  }
  file_put_contents("hostido.txt",$file);
  #print_r($file);

?>
