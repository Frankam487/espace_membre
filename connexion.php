<?php
require_once './db.php';

function handlePostRequest($pdo)
{
  //verification du type de requete
  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    return;
  }
  
  $mailconnect = htmlspecialchars($_POST['mailconnect']);
  $mdpconnect = $_POST['mdpconnect'];
  if (empty($mailconnect) || empty($mdpconnect)) {
    return "tous les champs doivent etre remplir";
  }

  return authenticateUser($pdo, $mailconnect, $mdpconnect);
}
function authenticateUser($pdo, $mailconnect, $mdpconnect)
{
  

  $sql = "SELECT * FROM membre WHERE mail = :mailconnect";
  $reqMail = $pdo->prepare($sql);
  $reqMail->execute(compact('mailconnect'));
  $mailExist = $reqMail->fetch();
  if (!$mailExist) {
    return "se mail est introuvable";
  }
}
$erreur = handlePostRequest($pdo);

?>









<!DOCTYPE html>
<html lang="en">

<head>
  <title>connection</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <meta charset="utf-8">
</head>

<body class="bg-green-100 pt-[100px] font-family-Poppins">
  <div align="center">
    <h2 class="text-4xl font-bold text-green-900 text-center mb-6">Connexion</h2>
    <br /><br />
    <form method="POST" action="" class="bg-white p-6 rounded shadow max-w-lg mx-auto">
      <?php
      if (isset($erreur)) {
        echo '<p class="bg-red-500 w-full border border-green-300 p-2 rounded focus:outline-none focus:border-green-500 text-white font-bold">' . $erreur . '</p>';
      }

      ?>



      <label for="mailconnect" class="">Mail :</label>

      <!-- E-mail : <input type="email" name="mailconnect" placeholder="Mail" /> <br><br> -->
      <input type="mail" placeholder="mail" id="mailconnect" name="mailconnect" value="<?= $mailconnect ?? '' ?>" class="w-full border border-green-300 p-2 rounded focus:outline-none focus:border-green-500" />

      <label for="mdpconnect" class="">Mot de passe :</label>

      <input type="password" placeholder="mot de passe" id="mdpconnect" name="mdpconnect" value="<?= $mdpconnect ?? '' ?>" class="w-full border border-green-300 p-2 rounded focus:outline-none focus:border-green-500" />
      <!-- PassWord : <input type="password" name="mdpconnect" placeholder="Mot de passe" /> -->
      <br /><br />
      <input type="submit" value="Se connecter !" class="w-full border border-green-300 p-2 rounded focus:outline-none focus:border-green-500 bg-green-100 cursor-pointer" />
    </form>

  </div>
</body>

</html>