DROP TABLE IF EXISTS salle;

CREATE TABLE salle (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL UNIQUE,
    capacite INTEGER NOT NULL CHECK (capacite > 0),
    etage INTEGER NOT NULL,
    videoprojecteur INTEGER NOT NULL DEFAULT 0 CHECK (videoprojecteur IN (0, 1)),
    ecran           INTEGER NOT NULL DEFAULT 0 CHECK (ecran IN (0, 1)),
    tableau_blanc   INTEGER NOT NULL DEFAULT 0 CHECK (tableau_blanc IN (0, 1)),
    visio           INTEGER NOT NULL DEFAULT 0 CHECK (visio IN (0, 1))
);

INSERT INTO salle (nom, capacite, etage, videoprojecteur, ecran, tableau_blanc, visio) VALUES
  ('Salle 1', 12, 1, 1, 1, 1, 0),
  ('Salle 2', 20, 2, 1, 0, 1, 1),
  ('Salle 3', 6, 0, 0, 1, 1, 0),
  ('Salle 4', 4, 1, 0, 0, 1, 0),
  ('Salle 5', 8, 3, 1, 1, 0, 1),
  ('Salle 6', 30, 2, 1, 1, 1, 1);
