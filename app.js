const express = require('express');
const sqlite3 = require('sqlite3').verbose();
const fs = require('fs');
const path = require('path');

const app = express();
const PORT = 3000;

app.set('view engine', 'ejs');
app.set('views', path.join(__dirname, 'views'));
app.use(express.urlencoded({ extended: true }));
app.use(express.static(path.join(__dirname, 'public')));

// ouverture de la bdd (on la cree si elle existe pas)
const db = new sqlite3.Database(path.join(__dirname, 'salles.db'));

function initDb(cb) {
  const sql = fs.readFileSync(path.join(__dirname, 'schema.sql'), 'utf8');
  db.get("SELECT name FROM sqlite_master WHERE type='table' AND name='salles'", function (err, row) {
    if (err) {
      console.log('erreur sqlite', err);
      return cb(err);
    }
    if (!row) {
      console.log('creation des tables...');
      db.exec(sql, cb);
    } else {
      cb();
    }
  });
}

function equipementsTexte(salle) {
  var liste = [];
  if (salle.videoprojecteur) liste.push('vidéoprojecteur');
  if (salle.ecran) liste.push('écran');
  if (salle.tableau_blanc) liste.push('tableau blanc');
  if (salle.visioconference) liste.push('visioconférence');
  if (liste.length === 0) return 'aucun';
  return liste.join(', ');
}

function checkCase(body, nom) {
  return body[nom] === 'on' || body[nom] === '1' ? 1 : 0;
}

// verifie les champs du formulaire, retourne un message ou null
function validerSalle(body, idActuel, callback) {
  var nom = (body.nom || '').trim();
  var capacite = body.capacite;
  var etage = body.etage;

  if (nom === '') {
    return callback('Le nom est obligatoire.');
  }

  var cap = Number(capacite);
  if (capacite === '' || capacite === undefined || isNaN(cap) || !Number.isInteger(cap) || cap <= 0) {
    return callback('La capacité doit être un nombre entier supérieur à 0.');
  }

  var etg = Number(etage);
  if (etage === '' || etage === undefined || isNaN(etg) || !Number.isInteger(etg)) {
    return callback("L'étage doit être un nombre entier.");
  }

  // nom deja pris ?
  var sql = 'SELECT id FROM salles WHERE nom = ?';
  var params = [nom];
  if (idActuel) {
    sql += ' AND id != ?';
    params.push(idActuel);
  }

  db.get(sql, params, function (err, row) {
    if (err) {
      return callback('Erreur base de données.');
    }
    if (row) {
      return callback('Ce nom de salle est déjà pris.');
    }
    callback(null, {
      nom: nom,
      capacite: cap,
      etage: etg,
      videoprojecteur: checkCase(body, 'videoprojecteur'),
      ecran: checkCase(body, 'ecran'),
      tableau_blanc: checkCase(body, 'tableau_blanc'),
      visioconference: checkCase(body, 'visioconference')
    });
  });
}

app.get('/', function (req, res) {
  var min = req.query.capacite_min;
  var erreur = req.query.erreur || null;
  var sql = 'SELECT * FROM salles';
  var params = [];

  if (min !== undefined && min !== '') {
    var n = Number(min);
    if (isNaN(n) || n < 0) {
      erreur = 'La capacité minimale doit être un nombre positif.';
    } else {
      sql += ' WHERE capacite >= ?';
      params.push(n);
    }
  }

  sql += ' ORDER BY nom';

  db.all(sql, params, function (err, rows) {
    if (err) {
      return res.status(500).send('Erreur en lisant les salles');
    }
    rows.forEach(function (s) {
      s.equipements = equipementsTexte(s);
    });
    res.render('liste', {
      salles: rows,
      capacite_min: min || '',
      erreur: erreur
    });
  });
});

app.get('/ajouter', function (req, res) {
  res.render('formulaire', {
    titre: 'Ajouter une salle',
    action: '/ajouter',
    salle: {
      nom: '',
      capacite: '',
      etage: '',
      videoprojecteur: 0,
      ecran: 0,
      tableau_blanc: 0,
      visioconference: 0
    },
    erreur: null
  });
});

app.post('/ajouter', function (req, res) {
  validerSalle(req.body, null, function (erreur, data) {
    if (erreur) {
      return res.render('formulaire', {
        titre: 'Ajouter une salle',
        action: '/ajouter',
        salle: {
          nom: req.body.nom || '',
          capacite: req.body.capacite || '',
          etage: req.body.etage || '',
          videoprojecteur: checkCase(req.body, 'videoprojecteur'),
          ecran: checkCase(req.body, 'ecran'),
          tableau_blanc: checkCase(req.body, 'tableau_blanc'),
          visioconference: checkCase(req.body, 'visioconference')
        },
        erreur: erreur
      });
    }

    db.run(
      'INSERT INTO salles (nom, capacite, etage, videoprojecteur, ecran, tableau_blanc, visioconference) VALUES (?, ?, ?, ?, ?, ?, ?)',
      [data.nom, data.capacite, data.etage, data.videoprojecteur, data.ecran, data.tableau_blanc, data.visioconference],
      function (err) {
        if (err) {
          var msg = 'Impossible d\'ajouter la salle.';
          if (err.message && err.message.indexOf('UNIQUE') !== -1) {
            msg = 'Ce nom de salle est déjà pris.';
          }
          return res.render('formulaire', {
            titre: 'Ajouter une salle',
            action: '/ajouter',
            salle: data,
            erreur: msg
          });
        }
        res.redirect('/');
      }
    );
  });
});

app.get('/modifier/:id', function (req, res) {
  db.get('SELECT * FROM salles WHERE id = ?', [req.params.id], function (err, salle) {
    if (err || !salle) {
      return res.redirect('/?erreur=' + encodeURIComponent('Salle introuvable.'));
    }
    res.render('formulaire', {
      titre: 'Modifier la salle',
      action: '/modifier/' + salle.id,
      salle: salle,
      erreur: null
    });
  });
});

app.post('/modifier/:id', function (req, res) {
  var id = req.params.id;
  validerSalle(req.body, id, function (erreur, data) {
    if (erreur) {
      data = data || {
        id: id,
        nom: req.body.nom || '',
        capacite: req.body.capacite || '',
        etage: req.body.etage || '',
        videoprojecteur: checkCase(req.body, 'videoprojecteur'),
        ecran: checkCase(req.body, 'ecran'),
        tableau_blanc: checkCase(req.body, 'tableau_blanc'),
        visioconference: checkCase(req.body, 'visioconference')
      };
      data.id = id;
      return res.render('formulaire', {
        titre: 'Modifier la salle',
        action: '/modifier/' + id,
        salle: data,
        erreur: erreur
      });
    }

    db.run(
      'UPDATE salles SET nom = ?, capacite = ?, etage = ?, videoprojecteur = ?, ecran = ?, tableau_blanc = ?, visioconference = ? WHERE id = ?',
      [data.nom, data.capacite, data.etage, data.videoprojecteur, data.ecran, data.tableau_blanc, data.visioconference, id],
      function (err) {
        if (err) {
          var msg = 'Impossible de modifier la salle.';
          if (err.message && err.message.indexOf('UNIQUE') !== -1) {
            msg = 'Ce nom de salle est déjà pris.';
          }
          data.id = id;
          return res.render('formulaire', {
            titre: 'Modifier la salle',
            action: '/modifier/' + id,
            salle: data,
            erreur: msg
          });
        }
        res.redirect('/');
      }
    );
  });
});

app.post('/supprimer/:id', function (req, res) {
  db.run('DELETE FROM salles WHERE id = ?', [req.params.id], function (err) {
    if (err) {
      return res.redirect('/?erreur=' + encodeURIComponent('Suppression impossible.'));
    }
    res.redirect('/');
  });
});

initDb(function (err) {
  if (err) {
    console.log('pb init bdd', err);
    process.exit(1);
  }
  app.listen(PORT, function () {
    console.log('serveur sur http://localhost:' + PORT);
  });
});
