<?php
session_start();
require_once "db.php";

// Vérifier si l'ID est présent et valide
if (!isset($_GET['id'])) {
  die("ID invalide.");
}

$getid = (int) $_GET['id'];
$requser = $pdo->prepare('SELECT * FROM membre WHERE id = ?');
$requser->execute([$getid]);
$userinfo = $requser->fetch();

if (!$userinfo) {
  die("Utilisateur introuvable.");
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profil - <?= htmlspecialchars($userinfo['pseudo']); ?></title>
</head>

<body>
  <div align="center">
    <h2>Profil de <?= htmlspecialchars($userinfo['pseudo']); ?></h2>
    <br />

    <?php if (!empty($userinfo['avatar'])): ?>
      <img src="membre/avatars/<?= htmlspecialchars($userinfo['avatar']); ?>" width="222" alt="Avatar">
    <?php endif; ?>

    <h3><?= htmlspecialchars($userinfo['mail']); ?></h3>
    <br />

    <?php if (isset($_SESSION['id']) && $_SESSION['id'] == $userinfo['id']): ?>
      <a href="editionprofil.php">Éditer mon profil</a>
      <a href="deconnexion.php">Se déconnecter</a>
    <?php else: ?>
      <p>Vous ne pouvez pas accéder à ce profil.</p>
    <?php endif; ?>
  </div>
</body>

</html>