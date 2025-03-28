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
    if (empty($pseudo) || empty($mail) || empty($mail2) || empty($mdp) || empty($mdp) || empty($mdp)) {
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
    if($mdp != $mdp2){
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
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>
  <div align="center">
    <h2>Inscription prof</h2>
    <br /><br />
    <form method="POST" action="">

      <?php
      if (isset($error)) {
        echo "<p style='background:red; width: 300px; font-width:bold; font-size: 17px; border-radius: 12px; color:white; padding:12px;'>" . $error . "</p>";
      }
      ?>

      <table>
        <tr>
          <td align="right">
            <label for="pseudo">Pseudo :</label>
          </td>
          <td>
            <input type="text" value="<?= $pseudo ?? '' ?>" placeholder="Votre pseudo" id="pseudo" name="pseudo" />

          </td>
        </tr>
        <tr>
          <td align="right">
            <label for="mail">Mail :</label>
          </td>
          <td>
            <input type="text" value="<?= $mail ?? '' ?>" placeholder="Votre mail" id="mail" name="mail" autocomplete="off" />
          </td>
        </tr>
        <tr>
          <td align="right">
            <label for="mail2">Confirmation du mail :</label>
          </td>
          <td>
            <input type="text" value="<?= $mail2 ?? '' ?>" placeholder="Confirmez votre mail" id="mail2" name="mail2" />
          </td>
        </tr>
        <tr>
          <td align="right">
            <label for="mdp">Mot de passe :</label>
          </td>
          <td>
            <input type="password" placeholder="Votre mot de passe" id="mdp" name="mdp" />
          </td>
        </tr>
        <tr>
          <td align="right">
            <label for="mdp2">Confirmation du mot de passe :</label>
          </td>
          <td>
            <input type="password" placeholder="Confirmez votre mdp" id="mdp2" name="mdp2" />
          </td>
        </tr>
        <tr>
          <td></td>
          <td align="center">
            <br />

            <input type="submit" name="forminscription" value="Je m'inscris" /> Déjà un compte ?<a
              href="connexion.php">Se connecter</a>
          </td>
        </tr>
      </table>
    </form>

  </div>
</body>

</html>