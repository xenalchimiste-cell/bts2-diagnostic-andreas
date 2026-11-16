# SalleLib

Projet fait en PHP avec une base de données SQLite.

## Partie 1 - Les salles

### Ce que j'ai fait

- `schema.sql` : crée la table `salle` et ajoute 6 salles de test.
- `db.php` : la connexion à la base, et une fonction `verifier()` qui contrôle ce qu'on tape dans le formulaire (nom vide, capacité pas un nombre ou pas plus grande que 0, étage pas un nombre, nom déjà pris).
- `index.php` : la page d'accueil qui affiche toutes les salles dans un tableau, avec une recherche par capacité minimale.
- `ajouter.php` : pour ajouter une salle.
- `modifier.php` : pour modifier une salle.
- `supprimer.php` : pour supprimer une salle.
- `formulaire.php` : le formulaire utilisé par ajouter et modifier.
- `header.php` et `style.css` : le haut de la page et un peu de style.

Les équipements (vidéoprojecteur, écran, tableau blanc, visioconférence) sont des cases à cocher, enregistrées en 0 ou 1 dans la base.

Si une saisie est fausse, la page affiche un message en rouge et ne plante pas.

### Lancer le projet

Il faut avoir PHP (avec pdo_sqlite) et sqlite3 installés.

1. Créer la base :

```
sqlite3 salles.db < schema.sql
```

2. Lancer le serveur :

```
php -S localhost:8000
```

3. Ouvrir http://localhost:8000 dans le navigateur.

Pour remettre la base à zéro, il suffit de relancer la commande de l'étape 1.
