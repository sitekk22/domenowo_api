<?php


$dane = $_POST['dane'];
$email = $_POST['email'];
$temat = $_POST['temat'];
$wiadomosc = $_POST['tresc'];

$wiadomosc = wordwrap($wiadomosc, 70);

$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

$headers .= "From: $dane <$email>" . "\r\n";


mail("kontakt@domenowo.org", $temat, $wiadomosc, $headers);
