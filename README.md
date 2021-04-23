# Emploi du Temps (EDT)

EDT est une application permettant de centraliser les informations nécessaires pour la construction de l'emploi du temps dans le departement MMI à l'IUT de Tarbes.

## Prérequis
1) Avoir XAMPP ou autre dans sa machine
2) Avoir importé la base de données au niveau de phpmyadmin

## Installation

1) Installer [Git](http://git-scm.com/download/win)
2) Installer [Composer](https://getcomposer.org/download/)
3) Créer un clone du repository en tapant la ligne suivant sur le cmd :

```bash
git clone https://github.com/nicofilizzola/emploi_du_temps.git
```

4) Installer les dépendances du projet en tapant la ligne suivante sur le cmd (au niveau du répertoire "emploi_du_temps" qui vient d'être créé)
```bash
composer install
```

5) Installer [Node.js (npm)](https://nodejs.org/en/download/)

6) Exporter les dépendances en utilisant la commande suivante (au niveau du repertoire "emploi_du_temps") :
```bash
npm run watch
```

## Ouvrir dans le navigateur

1) Démarrer le serveur Apache ainsi que le serveur MySQL au niveau de XAMPP

2) Au niveau du répertoire "emploi_du_temps" taper les lignes suivantes sur le cmd :
```bash
symfony serve -d
symfony open:local
```
