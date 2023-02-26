<?php
function cors()
{

  // Allow from any origin
  if (isset($_SERVER['HTTP_ORIGIN'])) {
    // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
    // you want to allow, and if so:
    //header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
      
    header("Access-Control-Allow-Origin: *");
      
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
  }

  // Access-Control headers are received during OPTIONS requests
  if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
      // may also be using PUT, PATCH, HEAD etc
      header("Access-Control-Allow-Methods:POST, OPTIONS");

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
      header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
  }
}

cors();





function get_float(string $string)
{
  return (float) filter_var($string, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
}

function curl_get_contents($url, $header_content, $return_header, $nobody, $post)
{
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
  if ($header_content != "") {
    curl_setopt($ch, CURLOPT_POST, $post);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $header_content);
  }
  curl_setopt($ch, CURLOPT_HEADER, $return_header);
  curl_setopt($ch, CURLOPT_NOBODY, $nobody);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  #curl_setopt($ch, CURLOPT_VERBOSE, true);
  $data = curl_exec($ch);
  curl_close($ch);
  return $data;
}
function prawidloweTLD($tld)
{
  $file = file_get_contents('tlds.txt');
  $file = explode("\n", $file);
  for ($i = 0; $i < count($file); $i++) {
    $str = $file[$i];
    if ($str == $tld) {
      return true;
    }
  }
  return false;
}

function szukanie_z_pliku($rej, $tld)
{
  $rejestrator = explode(".", $rej)[0];
  $file = file_get_contents("semi-static/$rejestrator/$rejestrator.txt");
  $file = explode("\n", $file);
  for ($i = 0; $i < count($file); $i++) {
    $linia = explode(" ", $file[$i]);
    if ($linia[0] == $tld) {
      $json = array("rejestrator" => "$rej", "cena_odn" => (float) $linia[1], "cena_rej" => (float)$linia[2]);
      return json_encode($json);
    }
  }
}


function azpl(string $domena)
{
  $ceny = array("rejestrator" => 'az.pl', "cena_odn" => 0, 'cena_rej' => 0);
  $json = curl_get_contents("https://api-az.online.pro/domains/$domena/search?bundle[]=pl&bundle[]=online", "", 0, 0, 0);
  $splitP = explode('{"fqdn":', $json);

  $array_len = count($splitP);
  $i = 0;
  $splitDomena = array();
  $domena = urldecode($domena);
  $domena_unicode = trim(json_encode($domena), '"');
  while ($i < $array_len) {
    $str = $splitP[$i];
    if (str_contains($str, $domena_unicode)) {
      $splitDomena = explode(",", $str);
      break;
    }
    $i++;
  }
  $len = count($splitDomena);
  $i = 0;
  while ($i < $len) {
    $str = $splitDomena[$i];
    if (str_contains($str, 'Unavailable')) {
      $ceny['dostepna'] = false;
      break;
    }
    if (str_contains($str, "price_register")) {
      $arr = explode(":", $str);
      $cena_rej = end($arr) * 0.0001;
      $cena_rej = substr(number_format((float) $cena_rej, 3, '.', ''), 0, -1);
      $ceny['cena_rej'] = (float)$cena_rej;
    }
    if (str_contains($str, "price_renew")) {
      $arr = explode(":", $str);
      $cena_odn = end($arr) * 0.0001;
      $cena_odn = substr(number_format((float) $cena_odn, 3, '.', ''), 0, -1);
      $ceny['cena_odn'] = (float) $cena_odn;
    }
    $i++;
  }
  return json_encode($ceny);
}


function homepl(string $domena)
{

  $ceny = array("rejestrator" => 'home.pl', "cena_odn" => 0, 'cena_rej' => 0, 'dostepna' => true);
  $json = curl_get_contents("https://onestoreapi.home.pl/domains/$domena/search?bundle[]=pl&bundle[]=online", "", 0, 0, 0);
  $splitP = explode('{"fqdn":', $json);
  $array_len = count($splitP);
  $i = 0;
  $splitDomena = array();

  $domena = urldecode($domena);
  $domena_unicode = trim(json_encode($domena), '"');
  while ($i < $array_len) {
    $str = $splitP[$i];
    if (str_contains($str, $domena_unicode)) {
      $splitDomena = explode(",", $str);
      break;
    }
    $i++;
  }
  $len = count($splitDomena);
  $i = 0;
  while ($i < $len) {
    $str = $splitDomena[$i];
    if (str_contains($str, 'Unavailable')) {
      $ceny['dostepna'] = false;
      break;
    }
    if (str_contains($str, "price_register")) {
      $arr = explode(":", $str);
      $cena_rej = end($arr) * 0.0001;
      $cena_rej = substr(number_format((float) $cena_rej, 3, '.', ''), 0, -1);
      $ceny['cena_rej'] = (float) $cena_rej;
    }
    if (str_contains($str, "price_renew")) {
      $arr = explode(":", $str);
      $cena_odn = end($arr) * 0.0001;
      $cena_odn = substr(number_format((float) $cena_odn, 3, '.', ''), 0, -1);
      $ceny['cena_odn'] = (float) $cena_odn;
    }
    $i++;
  }
  if ($ceny['cena_odn'] == 0 || $ceny['cena_rej'] == 0) {
    $ceny['dostepna'] = false;
  }
  return json_encode($ceny);
}
function krupl($tld)
{
  $json = array("rejestrator" => 'kru.pl', "cena_odn" => 0, 'cena_rej' => 0);

  $html = curl_get_contents("https://www.kru.pl/cennikdomen.php", "", 0, 0, 0);
  $html = explode("<main id=\"main-body\">", $html)[1];
  $html = explode('<table class="cennikTable w100 mx-auto">', $html);
  $html = explode('<tr class="bold">', $html[1]);
  # 1 -> TopLevelDomain; 4 -> rejestracja; 7 -> odnowienie;
  $legenda = array(0 => 1, 1 => 4, 2 => 7);
  $domena = '';
  for ($i = 1; $i < count($html); $i++) {
    if ($domena != $tld) {
      $h = explode('class', $html[$i]);
      $cena_rej = $h[$legenda[1]];
      $cena_rej = get_float($cena_rej);

      $cena_odn = $h[$legenda[2]];
      $cena_odn = get_float($cena_odn);

      $domena = $h[$legenda[0]];
      $domena = explode(">", $domena);
      $domena = explode("<", $domena[1])[0];
    } else break;
  }
  $json['cena_rej'] = $cena_rej;
  $json['cena_odn'] = $cena_odn;
  return json_encode($json);
}

function domenypl($domena)
{
  $domena = urldecode($domena);
  $data = array('add_default_tld' => 'true', 'dispnon' => '', 'domena' => $domena);

  $response = curl_get_contents('https://domeny.pl/wyniki-wyszukiwania.html', $data, 1, 1, 0);
  $response = (explode('HTTP/2 200', $response));
  $response = end($response);
  $response = explode("\n", $response);
  for ($i = 0; $i < count($response); $i++) {
    $str = $response[$i];
    if (str_contains($str, 'set-cookie: client_panel=')) {
      $cookie = explode(';', $str)[0];
      $cookie = trim($cookie, 'set-cookie:');
    }
  }

  $data = array("domains[]" => $domena, "filterListName" => 'mostPopularList', "unavailable" => 'false', "Cookie:" . $cookie);
  $response = curl_get_contents("https://domeny.pl/wyniki-wyszukiwania-2.json", $data, 0, 0, 1);
  $response = explode('price', $response);
  for ($i = 0; $i < count($response); $i++) {
    $str = $response[$i];
    if (str_contains($str, "z\\u0142")) {
      $str = explode(':', $str);
      for ($j = 0; $j < count($str); $j++) {
        if (str_contains($str[$j], "z\\u0142")) {
          $str = explode(':', $str[$j]);
          $str = explode(' z\u0142', $str[0]);
          $cena[] = get_float($str[0]) / 100;
        }
      }
    }
  }
  $json = array("rejestrator" => 'domeny.pl', "cena_odn" => 0, "cena_rej" => 0);
  $json['cena_rej'] = $cena[0];
  $json['cena_odn'] = $cena[1];
  return json_encode($json);
}


#echo(file_get_contents('testy.txt'));



$domena = $_REQUEST['domena'];
#$domena = "żabkaasdas.com";
$domena = urlencode($domena);
$tld = explode('.', $domena);
#
# Umożliwienie wyszukiwania domen z dwoma członami typu com.pl
#
$tldTemp = '';


for ($i = 1; $i < count($tld); $i++) {
  $tldTemp .= $tld[$i];
  $tldTemp .= '.';
}
$tld = '';
for ($i = 0; $i < strlen($tldTemp) - 1; $i++) {
  $tld .= $tldTemp[$i];
}

#echo (file_get_contents('testy.txt'));

if (!prawidloweTLD($tld)) {
  $json1 = json_encode(array("rejestrator" => '', "cena_odn" => 0, 'cena_rej' => 0, 'dostepna' => false, 'prawidloweTLD' => false, 'domena' => $domena));
  $json[] = json_decode($json1);
  echo (json_encode($json));
} else {
  $cennik[] = json_decode(homepl($domena));
  $dostepna = (array) $cennik[0];
  $dostepna = end($dostepna);

  if ($dostepna) {
    $cennik[] = json_decode(krupl($tld));
    $cennik[] = json_decode(domenypl($domena));
    $cennik[] = json_decode(azpl($domena));
    $cennik[] = json_decode(szukanie_z_pliku("thecamels.org", $tld));
    $cennik[] = json_decode(szukanie_z_pliku("ovhcloud.com", $tld));
    $cennik[] = json_decode(szukanie_z_pliku("seohost.pl", $tld));
    $cennik[] = json_decode(szukanie_z_pliku("netmark.pl", $tld));
    $cennik[] = json_decode(szukanie_z_pliku("dhosting.pl", $tld));
    #
    # Walidacja cennika
    #
    $cennik_wal = array();
    for ($i = 0; $i < count($cennik); $i++) {
      if ($cennik[$i] != null && $cennik[$i]>0) {
        $cennik_wal[] = $cennik[$i];
      }
    }

    $json_merge = json_encode($cennik_wal);
    //file_put_contents('testy.txt', $json_merge);
    echo ($json_merge);
  } else {
    $json1 = json_encode(array("rejestrator" => '', "cena_odn" => 0, 'cena_rej' => 0, 'dostepna' => false, 'prawidloweTLD' => true, 'domena' => $domena));
    $json[] = json_decode($json1);
    echo (json_encode($json));
  }
}
echo ("\n");
