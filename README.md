# SalleLib (partie 1)

Applis pour lister / ajouter / modifier / supprimer les salles.

## Prérequis

- Node.js
- npm

## Lancer le projet

Dans le dossier :

```
npm install
```

La base sqlite se crée toute seule au premier lancement (à partir de `schema.sql`).

Sinon à la main :

```
sqlite3 salles.db < schema.sql
```

Ensuite :

```
node app.js
```

Ouvrir http://localhost:3000 dans le navigateur.

## Fichiers

- `schema.sql` : tables + 6 salles de test
- `app.js` : serveur express
- `views/` : pages html
- `salles.db` : base (créée au lancement, pas dans git)
