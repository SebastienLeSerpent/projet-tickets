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
### Inscription et connexion :

* Sur la page d'accueil, vous connecter si vous avez déjà un compte.

### Liste des tickets :

* Une fois connecté, vous serez redirigé vers la liste des tickets.
* Vous pouvez afficher les détails d'un ticket en cliquant sur son titre.
* Vous pouvez également ajouter un nouveau ticket en cliquant sur le bouton "Ajouter un Ticket".

### Détails d'un ticket :

* Sur la page des détails d'un ticket, vous pouvez voir les informations détaillées du ticket ainsi que les notes associées.
* Selon vos autorisations, vous pourrez également modifier le statut du ticket ou ajouter une nouvelle note.

### Ajout d'une note :

* Sur la page d'ajout d'une note, vous pouvez saisir un message et sélectionner le statut de la note.
* Après avoir soumis le formulaire, la note sera ajoutée au ticket correspondant.


## Auteurs
Sébastien CARLUER - carluer@et.esiea.fr
