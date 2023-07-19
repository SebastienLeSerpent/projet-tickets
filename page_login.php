<!DOCTYPE html>
<html>
<head>
  <title>Projet Tickets - Page de Connexion</title>
  <meta charset="UTF-8">

  <!-- Inclusion des fichiers CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="styles/styles_globaux.css">
  <link rel="stylesheet" href="styles/styles_formulaires.css">
</head>
<body>
  <!-- Barre de navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">Projet Tickets</a>
  </nav>

  <!-- Conteneur principal -->
  <div class="container">
    <h2 class="text-center">Connexion</h2>

    <!-- Formulaire de connexion -->
    <form id="loginForm" method="POST">
      <div class="form-group">
        <label for="identifiant">Identifiant:</label>
        <input type="text" class="form-control" id="identifiant" name="identifiant" required>
      </div>
      <div class="form-group">
        <label for="motDePasse">Mot de passe:</label>
        <input type="password" class="form-control" id="motDePasse" name="motDePasse" required>
      </div>
      <button type="submit" class="btn btn-primary btn-block">Se connecter</button>
    </form>
    <div class="text-center mt-2">
      <a href="page_inscription.php" class="btn btn-success mt-2">Créer un compte</a>
    </div>
  </div>

  <!-- Scripts JavaScript -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
  <script>
    // Attente du chargement du document
    $(document).ready(function() {
      // Écoute de l'événement de soumission du formulaire
      $('#loginForm').on('submit', function(e) {
        e.preventDefault();

        // Récupération des valeurs des champs d'identifiant et de mot de passe
        var identifiant = $('#identifiant').val();
        var motDePasse = $('#motDePasse').val();

        // Envoi d'une requête AJAX vers le script login.php
        $.ajax({
          type: 'POST',
          url: 'login.php',
          data: { identifiant: identifiant, motDePasse: motDePasse },
          dataType: 'json',
          success: function(response) {
            console.log(response);
            if (response.success) {
              // Réinitialisation des champs et affichage d'une notification de connexion réussie
              $('#identifiant').val('');
              $('#motDePasse').val('');
              Swal.fire({
                title: 'Connexion réussie !',
                icon: 'success',
                onClose: () => {
                  // Redirection vers la page de liste des tickets après la fermeture de la notification
                  setTimeout(() => {
                    window.location.href = 'page_liste_tickets.php';
                  }, 0);
                }
              });
            } else {
              // Réinitialisation des champs et affichage d'une notification d'échec de connexion
              $('#identifiant').val('');
              $('#motDePasse').val('');
              Swal.fire({
                title: 'Échec de la connexion..',
                text: 'Identifiant ou mot de passe incorrect',
                icon: 'error',
              });
            }
          },
          error: function(xhr, status, error) {
            // Réinitialisation des champs et affichage d'une notification d'erreur de connexion
            $('#identifiant').val('');
            $('#motDePasse').val('');
            Swal.fire({
              title: 'Erreur...',
              text: 'Erreur lors de la tentative de connexion',
              icon: 'error',
            });
          }
        });
      });
    });
  </script>
</body>
</html>
