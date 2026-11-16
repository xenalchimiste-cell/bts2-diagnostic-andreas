# sallelib

projet fait en php avec une base de données sqlite

## lancer le projet

il faut avoir php (avec pdo_sqlite) et sqlite3 installés

1 aller dans le dossier du projet :

```
cd nwcours
```

2 créer la base (attention, ça remet les salles de test et efface les autres) :

```
sqlite3 salles.db < schema.sql
```

3 lancer le serveur :

```
php -S localhost:8000
```

4 ouvrir http://localhost:8000 dans le navigateur

pour arrêter le serveur, je fais `ctrl + c` dans le terminal

## partie 1 - les salles

### comment j'ai fait

**1 le choix du langage**

j'ai choisi php parce que c'est simple pour faire des pages web : chaque fichier `.php` est une page, et je n'ai pas besoin d'installer de framework pour la base de données, j'ai pris sqlite parce que toute la base tient dans un seul fichier (`salles.db`) et qu'il n'y a pas de serveur à installer

**2 la base de données**

j'ai commencé par écrire `schema.sql` dedans, je crée la table `salle` avec :

- un `id` qui s'incrémente tout seul ;
- le `nom`, que j'ai mis en `not null` et `unique` pour qu'il soit obligatoire et qu'il n'y ait pas deux salles avec le même nom ;
- la `capacite`, avec un `check (capacite > 0)` pour refuser 0 ou un nombre négatif ;
- l'`etage` ;
- une colonne pour chaque équipement (`videoprojecteur`, `ecran`, `tableau_blanc`, `visio`), qui vaut 1 si la salle l'a et 0 sinon

ensuite, j'ai ajouté 6 salles de test avec des `insert into` j'ai mis un `drop table if exists` au début pour pouvoir relancer le script quand je veux repartir de zéro

**3 la connexion à la base**

après, j'ai fait `db.php` il ouvre la base avec pdo, et je l'inclus en haut de chaque page avec `require 'db.php';`, pour ne pas réécrire la connexion à chaque fois

**4 afficher les salles**

ensuite, j'ai fait `index.php` je récupère toutes les salles avec `select * from salle order by nom` et je les affiche dans un tableau avec une boucle `foreach` pour les équipements, je regarde lesquels valent 1 et je les écris l'un en dessous de l'autre

j'ai aussi fait un petit `style.css` pour mettre des bordures au tableau et les erreurs en rouge

**5 ajouter une salle**

pour ajouter une salle, j'ai fait `ajouter.php` quand j'arrive sur la page, le formulaire est vide quand je clique sur « ajouter », le formulaire est envoyé en post, je récupère les champs et je fais un `insert into` pour les cases à cocher, j'utilise `isset()` : si la case est cochée, elle est envoyée, donc je mets 1, sinon 0 après l'ajout, je renvoie vers la liste avec `header('location: index.php')`

**6 vérifier les saisies**

au début, si je mettais un nom vide ou des lettres dans la capacité, ça faisait une erreur alors j'ai ajouté des `if` avant l'`insert into` pour contrôler :

- que le nom n'est pas vide ;
- que la capacité est un nombre entier plus grand que 0 (avec `ctype_digit`) ;
- que l'étage est un nombre (avec `is_numeric`) ;
- que le nom n'est pas déjà pris par une autre salle

s'il y a des erreurs, je ne touche pas à la base : je réaffiche le formulaire avec les messages en rouge et ce que l'utilisateur avait tapé comme ça, l'application ne plante pas

**7 modifier une salle**

pour modifier une salle, j'ai fait `modifier.php` je récupère l'`id` de la salle dans l'adresse (`modifier.php?id=3`), je la cherche dans la base et je préremplis le formulaire avec ses informations à l'enregistrement, je fais un `update` le formulaire est le même que pour l'ajout, avec les mêmes vérifications

j'ai eu un problème avec la vérification du nom : quand je modifiais une salle sans changer son nom, ça me disait que le nom était déjà pris, parce que la salle se trouvait elle-même pour régler ça, je cherche seulement dans les autres salles avec `id != ?`

**8 supprimer une salle**

pour supprimer une salle, j'ai fait `supprimer.php` il prend l'`id` et fait un `delete`, puis il revient sur la liste

**9 la recherche par capacité**

pour finir, j'ai ajouté un petit formulaire en haut de `index.php` quand je tape un nombre, il est envoyé dans l'adresse (`index.php?min=8`) et je fais `select * from salle where capacite >= ?` pour ne garder que les salles assez grandes si ce n'est pas un nombre, j'affiche un message d'erreur et toute la liste

**10 la sécurité**

dans toutes mes requêtes, j'utilise des `?` avec `prepare()` et `execute()` au lieu de coller directement ce que l'utilisateur tape ça évite les injections sql à l'affichage, j'utilise `htmlspecialchars()` pour qu'un nom avec du html dedans ne casse pas la page

### les fichiers

- `schema.sql` : crée la table et ajoute les salles de test
- `db.php` : la connexion à la base
- `index.php` : la liste des salles et la recherche par capacité
- `ajouter.php` : ajouter une salle
- `modifier.php` : modifier une salle
- `supprimer.php` : supprimer une salle
- `style.css` : le style des pages

## bilan

- jusqu'où je suis allé : j'ai fini la partie 1, je peux lister, ajouter, modifier, supprimer les salles et faire la recherche par capacité
- où j'ai bloqué, et pourquoi : pour la modification de salle, je ne sais pas pourquoi je n'arrivais pas a trouver le bon code
- ce que je maitrise bien : l'interface et la DB
- ce que je veux travailler en priorité : le front
- où en sont mes 2 réalisations e6 : 
