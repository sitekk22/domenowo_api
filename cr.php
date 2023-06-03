<?php

function insert_to_db(string $nazwa, $cache_data){
    $cache_data = json_decode(json_encode($cache_data), true);    
    $rej =  $cache_data['rejestrator']; 
    
    $cena_odn =  $cache_data['cena_odn']; 
    $cena_rej =  $cache_data['cena_rej'];
    $dostepna = (bool)$cache_data['dostepna'];
    $dostepna = $dostepna ? 'true' : 'false';
    
    $data = date("Y-m-d H:i:s");
    $dbconn = pg_connect("host=localhost dbname=domeny user=php password=RinertmaN") or die('Nie udalo sie polaczyc z baza, err: '. pg_last_error());
    $query = "INSERT INTO ceny (nazwa, rejestrator, cena_odn, cena_rej, dostepna, data) VALUES ('$nazwa', '$rej', $cena_odn, $cena_rej, $dostepna, '$data');";
    $result = pg_query($query) or die('Query failed: '. pg_last_error());
    pg_free_result($result);
    pg_close($dbconn);

}

#$a =  array("rejestrator"=>"home.pl","cena_odn"=> 160,"cena_rej"=>40,"dostepna" => true);
#insert_to_db($a);
#$a = $a["cena_odn"];
#echo("<span>$a</span>");
function get_from_db(){ 
    $dbconn = pg_connect("host=localhost dbname=domeny user=php password=RinertmaN") or die('Nie udalo sie polaczyc z baza, err: '. pg_last_error());
    $query = 'SELECT * FROM ceny;';
    $result = pg_query($query) or die('Query failed: '. pg_last_error());
    $table = [];
    /*
    while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)){
        foreach ($line as $col_value){
            $table[] = $col_value;
        }
     
       $table[] = "\n";
    }*/
    $table = pg_copy_to($dbconn, 'ceny');
    pg_free_result($result);  
    pg_close($dbconn);
    print_r($table);
}

get_from_db();
