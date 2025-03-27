<?php
require_once "./db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $pseudo = htmlspecialchars($_POST['pseudo']);
  $mail = htmlspecialchars($_POST['mail']);
  $mail2 = htmlspecialchars($_POST['mail2']);
  $mdp = $_POST['mdp'];
  $mdp2 = $_POST['mdp2'];

  function register($pseudo, $mail, $mail2, $mdp, $mdp2)
  {
    global $pdo;
    if (empty($pseudo) || empty($mail) || empty($mail2) || empty($mdp) || empty($mdp)) {
      return "Tous les champs doivent etre remplis !!";
    }
    if (strlen($pseudo) > 255) {
      return "Le pseudo est trop long";
    }
    $sql = "SELECT * FROM membre WHERE pseudo = :pseudo";
    $reqPseudo = $pdo->prepare($sql);
    $reqPseudo->execute(compact('pseudo'));
    $pseudoExist = $reqPseudo->fetch();

    if ($pseudoExist) {
      return "Le pseudo est deja pris";
    }
    if ($mail != $mail2) {
      return "Les mails ne correspondent pas !!";
    }
    if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
      return "Le mail n'est pas valide";
    }
    $sql = "SELECT * FROM membre WHERE mail = :mail";
    $reqMail = $pdo->prepare($sql);
    $reqMail->execute(compact("mail"));
    $mailExist = $reqMail->fetch();
    if ($mailExist) {
      return "Le mail existe deja !!!!!!!!!!!!";
    }
    if (strlen($mdp) < 8 && !preg_match("#[0-9]+#", $mdp) && !preg_match("#[a-zA-Z]+#", $mdp)) {
      return "Le mot de passe doit contenir au moins 8 caracteres et un chiffre et une lettre";
    }
    if ($mdp != $mdp2) {
      return "Les mots de passe ne correspondent pas !!";
    }
    $mdp = password_hash($mdp, PASSWORD_DEFAULT);
    $sql = "INSERT INTO membre (pseudo, mail, mdp) VALUES (:pseudo, :mail, :mdp)";
    $req = $pdo->prepare($sql);
    $req->execute(compact('pseudo', 'mail', 'mdp'));
    return "Vous etes inscrit avec succes !! <a href=\"connexion.php\">Me connecter</a>";
  }
  $error = register($pseudo, $mail, $mail2, $mdp, $mdp2);
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inscription</title>
  <link rel="stylesheet" href="./src/input.css">
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
  <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-6">
    <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Inscription</h2>

    <?php if (isset($error)): ?>
      <div class="mb-4 p-4 <?php echo strpos($error, 'succes') !== false ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?> rounded-md">
        <?php echo $error; ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="" class="space-y-4">
      <div>
        <label for="pseudo" class="block text-sm font-medium text-gray-700 mb-1">Pseudo</label>
        <input
          type="text"
          value="<?= $pseudo ?? '' ?>"
          placeholder="Votre pseudo"
          id="pseudo"
          name="pseudo"
          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
      </div>

      <div>
        <label for="mail" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
        <input
          type="email"
          value="<?= $mail ?? '' ?>"
          placeholder="Votre email"
          id="mail"
          name="mail"
          autocomplete="off"
          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
      </div>

      <div>
        <label for="mail2" class="block text-sm font-medium text-gray-700 mb-1">Confirmation email</label>
        <input
          type="email"
          value="<?= $mail2 ?? '' ?>"
          placeholder="Confirmez votre email"
          id="mail2"
          name="mail2"
          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
      </div>

      <div>
        <label for="mdp" class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
        <input
          type="password"
          placeholder="Votre mot de passe"
          id="mdp"
          name="mdp"
          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
      </div>

      <div>
        <label for="mdp2" class="block text-sm font-medium text-gray-700 mb-1">Confirmation mot de passe</label>
        <input
          type="password"
          placeholder="Confirmez votre mot de passe"
          id="mdp2"
          name="mdp2"
          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
      </div>

      <div class="flex items-center justify-between">
        <button
          type="submit"
          name="forminscription"
          class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors duration-200">
          Je m'inscris
        </button>
        <a href="connexion.php" class="text-blue-600 hover:underline">Déjà un compte ? Se connecter</a>
      </div>
    </form>
  </div>
</body>

</html>