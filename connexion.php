<?php
require_once 'db.php';

function handlePostRequest($pdo)
{
  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    return null;
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
  $reqMail->execute(['mailconnect' => $mailconnect]);

  if ($reqMail->rowCount() === 0) {
    return "Cet e-mail est introuvable.";
  }

  $userinfo = $reqMail->fetch();

  if (!password_verify($mdpconnect, $userinfo['mdp'])) {
    return "Mauvais mot de passe !!";
  }

  session_start();
  $_SESSION['user_id'] = $userinfo['id'];
  $_SESSION['user_mail'] = $userinfo['mail'];


  
}

$erreur = handlePostRequest($pdo);
?>



<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
   <link rel="stylesheet" href="./src/input.css">
</head>

<body class="bg-green-100 pt-[100px] font-family-Poppins">
  <div class="max-w-md mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-4xl font-bold text-green-900 text-center mb-6">Connexion</h2>

    <?php if (isset($erreur)) : ?>
      <p class="bg-red-500 text-white p-2 rounded mb-4"><?= htmlspecialchars($erreur) ?></p>
    <?php endif; ?>

    <form method="POST">
      <label for="mailconnect" class="block font-bold">E-mail :</label>
      <input type="email" name="mailconnect" id="mailconnect" value="<?= htmlspecialchars($_POST['mailconnect'] ?? '') ?>"
        class="w-full border border-green-300 p-2 rounded focus:outline-none focus:border-green-500" required>

      <label for="mdpconnect" class="block font-bold mt-4">Mot de passe :</label>
      <input type="password" name="mdpconnect" id="mdpconnect"
        class="w-full border border-green-300 p-2 rounded focus:outline-none focus:border-green-500" required>

      <button type="submit"
        class="w-full bg-green-500 text-white font-bold p-2 mt-4 rounded hover:bg-green-700 transition">
        Se connecter
      </button>
    </form>
  </div>
</body>

</html>