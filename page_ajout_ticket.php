<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
  header("Location: page_login.html");
  exit();
}

// Connexion à la base de données pour récupérer les valeurs de la table Priorite
$dsn = 'mysql:host=localhost;dbname=PROJET;charset=utf8';
$username = 'root';
$password = '';

try {
  $conn = new PDO($dsn, $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // Requête pour récupérer toutes les valeurs de la table Priorite
  $queryPriorite = 'SELECT * FROM Priorite';

  // Préparation de la requête
  $stmtPriorite = $conn->prepare($queryPriorite);
  $stmtPriorite->execute();

  // Récupération des valeurs de la table Priorite
  $prioriteOptions = $stmtPriorite->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  echo 'Erreur : ' . $e->getMessage();
  exit();
}

// Connexion à la base de données pour récupérer les valeurs de la table TypeDemande
try {
  // Requête pour récupérer toutes les valeurs de la table TypeDemande
  $queryTypeDemande = 'SELECT * FROM TypeDemande';

  // Préparation de la requête
  $stmtTypeDemande = $conn->prepare($queryTypeDemande);
  $stmtTypeDemande->execute();

  // Récupération des valeurs de la table TypeDemande
  $typeDemandeOptions = $stmtTypeDemande->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  echo 'Erreur : ' . $e->getMessage();
  exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Ajouter un Ticket</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="styles/styles_globaux.css">
  <link rel="stylesheet" href="styles/styles_formulaires.css">
  <script>
    function confirmLogout() {
      $('#logoutModal').modal('show');
    }
  </script>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="page_liste_tickets.php">Projet Tickets</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="page_liste_tickets.php">Liste des Tickets</a>
        </li>
      </ul>
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" href="#">Utilisateur connecté : <?php echo $_SESSION['username']; ?></a>
        </li>
        <li class="nav-item">
          <button class="btn btn-logout btn-danger" onclick="confirmLogout()">Déconnexion</button>
        </li>
      </ul>
    </div>
  </nav>

  <!-- Fenêtre modale de confirmation de déconnexion -->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="logoutModalLabel">Déconnexion</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          Êtes-vous sûr de vouloir vous déconnecter ?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
          <a class="btn btn-logout btn-danger" href="logout.php">Déconnexion</a>
        </div>
      </div>
    </div>
  </div>

  <div class="container">
    <h2 class="text-center">Ajouter un Ticket</h2>
    <form method="POST" action="" enctype="multipart/form-data">
      <div class="form-group">
        <label for="sujet">Sujet :</label>
        <input type="text" class="form-control" id="sujet" name="sujet" required>
      </div>
      <div class="form-group">
        <label for="priorite">Priorité :</label>
        <select class="form-control" id="priorite" name="priorite" required>
          <?php foreach ($prioriteOptions as $option): ?>
            <option value="<?php echo $option['IdPriorite']; ?>"><?php echo $option['NomPriorite']; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group">
        <label for="type_demande">Type de demande :</label>
        <select class="form-control" id="type_demande" name="type_demande" required>
          <?php foreach ($typeDemandeOptions as $option): ?>
            <option value="<?php echo $option['IdTypeDemande']; ?>"><?php echo $option['NomTypeDemande']; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group">
        <label for="message">Message :</label>
        <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
      </div>
      <div class="form-group">
        <label for="piece_jointe">Pièce jointe :</label>
        <input type="file" class="form-control" id="piece_jointe" name="piece_jointe">
      </div>
      <button type="submit" class="btn btn-primary">Ajouter</button>
    </form>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
