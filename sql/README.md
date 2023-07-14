# Installation de la base de données PROJET
Ce guide vous explique comment installer la base de données PROJET pour votre application.

## Prérequis
Avant de commencer, assurez-vous d'avoir les éléments suivants :

* Un serveur web (par exemple, Apache) installé sur votre machine.
* Un système de gestion de base de données (SGBD) tel que MySQL ou MariaDB.
* L'accès en écriture aux répertoires nécessaires pour créer la base de données et importer les fichiers SQL.

## Étapes d'installation
Suivez les étapes ci-dessous pour installer la base de données PROJET :

1. Téléchargement du fichier SQL :

* Téléchargez le fichier SQL PROJET.sql depuis le répertoire sql du projet.

2. Création de la base de données :

* Ouvrez votre client MySQL ou MariaDB.
* Créez une nouvelle base de données avec la commande suivante :

```sql
CREATE DATABASE PROJET;
```

3. Importation du fichier SQL :

* Utilisez la commande suivante pour importer le fichier SQL dans votre base de données :

```css
mysql -u VOTRE_UTILISATEUR -p PROJET < CHEMIN_VERS_PROJET.sql
```

	Remplacez VOTRE_UTILISATEUR par votre nom d'utilisateur MySQL/MariaDB et CHEMIN_VERS_PROJET.sql par le chemin absolu ou relatif vers le fichier PROJET.sql téléchargé.

4. Vérification de l'installation :

* Démarrez votre serveur web et accédez à votre application.
* Assurez-vous que vous pouvez accéder aux fonctionnalités de l'application qui utilisent la base de données PROJET.
