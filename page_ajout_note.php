<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
  header("Location: page_login.php");
  exit();
}

// Vérifier si l'ID du ticket est passé en paramètre
if (!isset($_GET['id'])) {
  header("Location: page_liste_tickets.php");
  exit();
}

// Récupérer l'ID du ticket depuis les paramètres
$ticketId = $_GET['id'];

// Vérifier si le formulaire de création de note a été soumis
if (isset($_POST['submit'])) {
  // Récupérer les données du formulaire
  $dateNote = date('Y-m-d');
  $messageNote = $_POST['message'];
  $statutNote = $_POST['statut'];

  // Connexion à la base de données
  $dsn = 'mysql:host=localhost;dbname=PROJET;charset=utf8';
  $username = 'root';
  $password = '';

  try {
    $conn = new PDO($dsn, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Requête pour insérer la nouvelle note dans la base de données
    $query = 'INSERT INTO Notes (DateNote, MessageNote, IdTicket, StatutNote, IdCreateurNote) VALUES (:dateNote, :messageNote, :ticketId, :statutNote, 1)';

    // Préparation de la requête
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':dateNote', $dateNote);
    $stmt->bindParam(':messageNote', $messageNote);
    $stmt->bindParam(':ticketId', $ticketId);
    $stmt->bindParam(':statutNote', $statutNote);
    $stmt->execute();

    // Redirection vers la page page_details_ticket.php avec l'ID du ticket
    $redirectUrl = 'page_details_ticket.php?id=' . $ticketId;
    header("Location: $redirectUrl");
    exit();
  } catch (PDOException $e) {
    echo 'Erreur : ' . $e->getMessage();
    exit();
  }
}

// Connexion à la base de données pour récupérer les valeurs de la table StatutNote
$dsn = 'mysql:host=localhost;dbname=PROJET;charset=utf8';
$username = 'root';
$password = '';

try {
  $conn = new PDO($dsn, $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // Requête pour récupérer toutes les valeurs de la table StatutNote
  $queryStatutNote = 'SELECT * FROM StatutNote';

  // Préparation de la requête
  $stmtStatutNote = $conn->prepare($queryStatutNote);
  $stmtStatutNote->execute();

  // Récupération des valeurs de la table StatutNote
  $statutNotes = $stmtStatutNote->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  echo 'Erreur : ' . $e->getMessage();
  exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Ajouter une Note</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="styles/styles_globaux.css">
  <link rel="stylesheet" href="styles/styles_formulaires.css">
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
        <li class="nav-item">
          <a class="nav-link" href="page_ajout_ticket.php">Ajouter un Ticket</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="page_details_ticket.php?id=<?php echo $ticketId; ?>">Détails du Ticket</a>
        </li>
      </ul>
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" href="#">Utilisateur connecté : <?php echo $_SESSION['username']; ?></a>
        </li>
        <li class="nav-item">
          <button class="btn-danger logout-btn" data-toggle="modal" data-target="#confirmLogoutModal">Déconnexion</button>
        </li>
      </ul>
    </div>
  </nav>

  <div class="container">
    <h2 class="text-center">Ajouter une Note</h2>
    <form method="post">
      <div class="form-group">
        <label for="message">Message :</label>
        <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
      </div>
      <div class="form-group">
        <label for="statut">Statut :</label>
        <select class="form-control" id="statut" name="statut" required>
          <?php foreach ($statutNotes as $statutNote) : ?>
            <option value="<?php echo $statutNote['idStatutNote']; ?>"><?php echo $statutNote['NomStatut']; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="text-center">
        <button type="submit" name="submit" class="btn btn-primary">Ajouter</button>
      </div>
    </form>
  </div>

  <!-- Modal de confirmation de déconnexion -->
  <div class="modal fade" id="confirmLogoutModal" tabindex="-1" role="dialog" aria-labelledby="confirmLogoutModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="confirmLogoutModalLabel">Confirmation de déconnexion</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          Êtes-vous sûr de vouloir vous déconnecter ?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
          <button type="button" class="btn btn-danger logout-btn" onclick="logout()">Déconnexion</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script>
    function logout() {
      window.location.href = 'logout.php';
    }
  </script>
</body>
</html>
