<?php
session_start();
require_once './db.php';

function handlePostRequest($pdo)
{
  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    return;
  }

  $mailconnect = htmlspecialchars($_POST['mailconnect']);
  $mdpconnect = $_POST['mdpconnect'];

  if (empty($mailconnect) || empty($mdpconnect)) {
    return "Tous les champs doivent être remplis.";
  }

  return authenticateUser($pdo, $mailconnect, $mdpconnect);
}

function authenticateUser($pdo, $mailconnect, $mdpconnect)
{
  $sql = "SELECT * FROM membre WHERE mail = :mailconnect";
  $reqMail = $pdo->prepare($sql);
  $reqMail->execute(compact('mailconnect'));

  if ($reqMail->rowCount() == 0) {
    return "Ce mail est introuvable.";
  }

  $userinfo = $reqMail->fetch();

  if (!password_verify($mdpconnect, $userinfo['mdp'])) {
    return "Mot de passe incorrect.";
  }


  $_SESSION['id'] = $userinfo['id'];
  $_SESSION['pseudo'] = $userinfo['pseudo'];
  $_SESSION['email'] = $userinfo['mail'];

 
  header("Location: profil.php?id=" . $userinfo['id']);
  exit();
}

$erreur = handlePostRequest($pdo);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <title>Connexion</title>
  <meta charset="utf-8">
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="bg-green-100 pt-[100px] font-family-Poppins">
  <div align="center">
    <h2 class="text-4xl font-bold text-green-900 text-center mb-6">Connexion</h2>
    <br><br>
    <form method="POST" action="" class="bg-white p-6 rounded shadow max-w-lg mx-auto">
      <?php if (!empty($erreur)) : ?>
        <p class="bg-red-500 w-full border border-green-300 p-2 rounded focus:outline-none focus:border-green-500 text-white font-bold"><?= $erreur ?></p>
      <?php endif; ?>

      <label for="mailconnect">Mail :</label>
      <input type="email" placeholder="Mail" id="mailconnect" name="mailconnect" value="<?= htmlspecialchars($_POST['mailconnect'] ?? '') ?>" class="w-full border border-green-300 p-2 rounded focus:outline-none focus:border-green-500">

      <label for="mdpconnect">Mot de passe :</label>
      <input type="password" placeholder="Mot de passe" id="mdpconnect" name="mdpconnect" class="w-full border border-green-300 p-2 rounded focus:outline-none focus:border-green-500">

      <br><br>
      <input type="submit" value="Se connecter !" class="w-full border border-green-300 p-2 rounded focus:outline-none focus:border-green-500 bg-green-100 cursor-pointer">
    </form>
  </div>
</body>

</html>