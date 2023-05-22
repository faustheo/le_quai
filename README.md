le_quai

# Description

Le quai antique est une application responsive destiné à répondre aux besoins d'un restaurateur pour faciliter l'interaction avec ses clients, cette application
a pour objectif de permettre à un administrateur de définir les heures d'ouverture et de fermeture, d'ajouter des images à la galerie du site, de définir le
Nombres de place disponible au sein de l'établissement, de gérer les menus, la carte et de classer les plats par catégories.
Je travaille actuellement sur docker afin de faciliter le partage de mon projet, en attendant, j'espère que vous apprécierez mon travail à sa juste valeur :)

Configuration de l'environnement
Voici les étapes pour installer et exécuter le projet localement :

# Installer GitBash `https://git-scm.com/download/win`

# Installer Xampp `https://www.apachefriends.org/fr/download.html`

# Installer Cmder : Nous vous recommandons de télécharger Cmder pour l'installation de Composer `https://cmder.app/`.

# Installer Composer : Une fois Cmder installée, lancez la console et tapez `composer.phar`

# Accédez au disque local C: : Utilisez la commande `cd c:/`

# Aller dans le dossier Xampp : Utilisez la commande `cd xampp`

# Créer un dossier pour l'application : Utilisez la commande `mkdir app`

# Téléchargez le dépôt Git : Clonez le dépôt dans le répertoire nouvellement créé en utilisant la commande : `git clone <https://github.com/faustheo/le_quai.git>`

# Extraire et déplacer les fichiers : Si lors de la décompression, le fichier se trouve dans : le-quai-master/le-quai-master, déplacez le-quai-master directement dans app.

# Lancer votre éditeur de code.

# Mettre à jour les dépendances : Ouvrez la console (sélectionnez bash si vous utilisez VS Code) et tapez :  `composer update`

# Créer la base de données avec : `php bin/console doctrine:database:create`

# Configurer Apache :

	. Allez au panneau de contrôle de Xampp, cliquez sur le bouton de configuration d'Apache et cherchez PHP (php.ini).
	. Ouvrez le fichier.
	. Utilisez Ctrl + F pour lancer une recherche.
	. Tapez intl.
	. Vous devriez trouver : `;extension=intl.`
	. Supprimez le `;` juste avant `extension=intl`
	. Sauvegardez et fermez le fichier.

# Démarrer le serveur local : Lancez la commande suivante pour démarrer le serveur local : `php -S 127.0.0.1:8000 -t public`

# Rejoindre le serveur : Pour rejoindre le serveur, entrez ce lien dans votre navigateur : http://127.0.0.1:8000/

# Note si vous rencontrez des problèmes avec la base de donnée
	. Veillez à éxécuter la commande : `php bin/console doctrine:database:create`
	. Suivi de `php bin/console doctrine:migration:migrate`
	. Dans le dernier des cas si la base de données ne fonctionne toujours pas éxécutez la commande : `php bin/console doctrine:schema:update --force`
