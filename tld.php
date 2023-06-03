<?php
$rej = ["dhosting", "ovhcloud", "netmark", "seohost", "thecamels"];

$tld_file = file_get_contents("./tlds.txt");
$tld_file = explode("\n", $tld_file);
$tlds = [];

for ($i = 0; $i < count($rej); $i++){
    $f = file_get_contents("semi-static/$rej[$i]/$rej[$i].txt");
    $f = explode("\n", $f);

    $existing_tld = [];
    for ($j=0; $j<count($tld_file);$j++){
        $ftld = explode(" ", $tld_file[$j])[0];
        $existing_tld[] = $ftld;   
    }

    for ($j=0; $j<count($f) ;$j++){
        $line=$f[$j];
        $tld= explode(" ", $line)[0]; 
        if (!in_array($tld,$existing_tld)&&!in_array($tld, $tlds)){
            $tlds[]=$tld."\n";
        }
    }
    #print_r(!in_array("pl", $existing_tld)."$rej[$i]\n");
    
}
file_put_contents("./tlds.txt", $tlds, FILE_APPEND);
print_r($tlds);
?>
