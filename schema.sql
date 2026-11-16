DROP TABLE IF EXISTS salle;

CREATE TABLE salle (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL UNIQUE,
    capacite INTEGER NOT NULL CHECK (capacite > 0),
    etage INTEGER NOT NULL,
    videoprojecteur INTEGER DEFAULT 0,
    ecran INTEGER DEFAULT 0,
    tableau_blanc INTEGER DEFAULT 0,
    visio INTEGER DEFAULT 0
);

INSERT INTO salle (nom, capacite, etage, videoprojecteur, ecran, tableau_blanc, visio) VALUES ('Lumière', 12, 1, 1, 1, 1, 0);
INSERT INTO salle (nom, capacite, etage, videoprojecteur, ecran, tableau_blanc, visio) VALUES ('Confluence', 20, 2, 1, 0, 1, 1);
INSERT INTO salle (nom, capacite, etage, videoprojecteur, ecran, tableau_blanc, visio) VALUES ('Fourvière', 6, 0, 0, 1, 1, 0);
INSERT INTO salle (nom, capacite, etage, videoprojecteur, ecran, tableau_blanc, visio) VALUES ('Bellecour', 4, 1, 0, 0, 1, 0);
INSERT INTO salle (nom, capacite, etage, videoprojecteur, ecran, tableau_blanc, visio) VALUES ('Croix-Rousse', 8, 3, 1, 1, 0, 1);
INSERT INTO salle (nom, capacite, etage, videoprojecteur, ecran, tableau_blanc, visio) VALUES ('Part-Dieu', 30, 2, 1, 1, 1, 1);
