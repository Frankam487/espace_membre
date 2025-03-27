<!DOCTYPE html>
<html lang="fr">

<head>
  <title>TUTO PHP - Connexion</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
  <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-6">
    <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Connexion</h2>

    <form method="POST" action="" class="space-y-6">
      <div>
        <label for="mailconnect" class="block text-sm font-medium text-gray-700 mb-1">E-mail</label>
        <input
          type="email"
          name="mailconnect"
          id="mailconnect"
          placeholder="Votre email"
          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
      </div>

      <div>
        <label for="mdpconnect" class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
        <input
          type="password"
          name="mdpconnect"
          id="mdpconnect"
          placeholder="Votre mot de passe"
          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
      </div>

      <div class="flex justify-center">
        <input
          type="submit"
          value="Se connecter !"
          class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition-colors duration-200 cursor-pointer" />
      </div>
    </form>

    <?php if (isset($erreur)): ?>
      <div class="mt-4 p-3 bg-red-100 text-red-700 rounded-md text-center">
        <?php echo $erreur; ?>
      </div>
    <?php endif; ?>
  </div>
</body>

</html>