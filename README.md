# Projet Tickets
Ce projet est une application de gestion de tickets qui permet de suivre les demandes de support et les problèmes techniques.

## Installation
Clonez ce dépôt sur votre machine locale :

``` bash
git clone https://github.com/votre-utilisateur/projet-tickets.git
```
### Assurez-vous d'avoir les prérequis suivants installés sur votre machine :

* PHP 
* MySQL 

### Importez la base de données :

* Ouvrez le fichier sql/PROJET.sql.
* Exécutez les requêtes SQL pour créer la structure de la base de données et insérer les données initiales.

### Lancez l'application :

* Démarrez un serveur local ou configurez un serveur web (Apache, Nginx, etc.) pour exécuter le projet.
* Accédez à l'URL correspondant à l'application dans votre navigateur.
```
http://localhost/projet-tickets/page_login.html
```

## Utilisation
### Inscription et connexion : [page_login.html](page_login.html)

* Sur la page d'accueil, vous connecter si vous avez déjà un compte.
* Vous pouvez utiliser l'utilisateur suivant :
  * Identifiant : aaa
  * Mot de passe : aaa

### Liste des tickets : [page_liste_tickets.php](page_liste_tickets.php)

* Une fois connecté, vous serez redirigé vers la liste des tickets.
* Vous pouvez afficher seulement les tickets ouverts, fermés ou tous les tickets avec leur bouton correspondant.
* Vous pouvez afficher les détails d'un ticket en cliquant sur son titre.
* Vous pouvez également ajouter un nouveau ticket en cliquant sur le bouton "Ajouter un Ticket".
* Vous pouvez aussi trier la liste des tickets en fonction d'une colonne par ordre croissant ou décroissant en cliquand sur le titre de la colonne

### Détails d'un ticket : [page_details_ticket.php](page_details_ticket.php)

* Sur la page des détails d'un ticket, vous pouvez voir les informations détaillées du ticket ainsi que les notes associées.
* Vous pouvez modifier le statut du ticket.
* Vous pouvez ajouter une nouvelle note si le ticket est ouvert.

### Ajout d'un ticket : [page_ajout_ticket.php](page_ajout_ticket.php)

* Sur la page d'ajout d'un ticket, vous pouvez saisir un sujet, une priorité, un type de demande, un message et le lien vers une pièce jointe.
* Après avoir soumis le formulaire, le ticket sera ajouté.

### Ajout d'une note : [page_ajout_note.php](page_ajout_note.php)

* Sur la page d'ajout d'une note, vous pouvez saisir un message et sélectionner le statut de la note.
* Après avoir soumis le formulaire, la note sera ajoutée au ticket correspondant.


## Auteurs
Sébastien CARLUER - carluer@et.esiea.fr
