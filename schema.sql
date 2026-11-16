-- base SalleLib
-- lancer avec : sqlite3 salles.db < schema.sql

DROP TABLE IF EXISTS salles;

CREATE TABLE salles (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL UNIQUE,
    capacite INTEGER NOT NULL,
    etage INTEGER NOT NULL,
    videoprojecteur INTEGER DEFAULT 0,
    ecran INTEGER DEFAULT 0,
    tableau_blanc INTEGER DEFAULT 0,
    visioconference INTEGER DEFAULT 0
);

INSERT INTO salles (nom, capacite, etage, videoprojecteur, ecran, tableau_blanc, visioconference) VALUES
('Lumière', 8, 1, 1, 1, 0, 0),
('Confluence', 20, 2, 1, 1, 1, 1),
('Studio A', 6, 0, 0, 1, 1, 0),
('Atelier', 12, 1, 1, 0, 1, 0),
('Salle 204', 4, 2, 0, 0, 1, 0),
('Auditorium', 40, 0, 1, 1, 0, 1);
