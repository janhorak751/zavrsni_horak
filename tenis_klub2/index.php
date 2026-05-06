<?php
require 'db_connection.php';

// ── Dohvat podataka ──────────────────────────────────────────
$clanovi = mysqli_query($db,
    "SELECT * FROM clan ORDER BY Prezime, Ime");

$tereni = mysqli_query($db,
    "SELECT * FROM teren ORDER BY ID_Terena");

$cjenik = mysqli_query($db,
    "SELECT * FROM cjenik ORDER BY ID_Cjenika");

$rezervacije = mysqli_query($db, "
    SELECT r.ID_Rezervacije,
           CONCAT(c.Ime, ' ', c.Prezime) AS Clan,
           t.Naziv       AS Teren,
           t.Tip         AS Tip_terena,
           cj.Doba_dana,
           r.Datum,
           r.Vrijeme_pocetka,
           r.Vrijeme_zavrsetka,
           r.Ukupna_cijena,
           r.Napomena
    FROM   rezervacija r
    JOIN   clan   c  ON r.ID_Clana   = c.ID_Clana
    JOIN   teren  t  ON r.ID_Terena  = t.ID_Terena
    JOIN   cjenik cj ON r.ID_Cjenika = cj.ID_Cjenika
    ORDER  BY r.Datum DESC, r.Vrijeme_pocetka DESC
");

// ── Statistike ───────────────────────────────────────────────
$br_clanova  = mysqli_fetch_assoc(mysqli_query($db, "SELECT COUNT(*) n FROM clan"))['n'];
$br_terena   = mysqli_fetch_assoc(mysqli_query($db, "SELECT COUNT(*) n FROM teren WHERE Dostupan=1"))['n'];
$br_rezerv   = mysqli_fetch_assoc(mysqli_query($db, "SELECT COUNT(*) n FROM rezervacija"))['n'];
$uk_prihod   = mysqli_fetch_assoc(mysqli_query($db, "SELECT COALESCE(SUM(Ukupna_cijena),0) s FROM rezervacija"))['s'];

// ── Poruke ───────────────────────────────────────────────────
$poruka = '';
if (isset($_GET['ok']))  $poruka = '<div class="msg msg-ok">'  . htmlspecialchars($_GET['ok'])  . '</div>';
if (isset($_GET['err'])) $poruka = '<div class="msg msg-err">' . htmlspecialchars($_GET['err']) . '</div>';
?>
<!DOCTYPE html>
<html lang="hr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tenis Klub – Upravljanje</title>
  <link rel="stylesheet" href="style.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800&display=swap" rel="stylesheet">
</head>
<body>

<!-- HEADER -->
<header>
  <div class="header-inner">
    <div class="brand">
      <div class="brand-icon">TC</div>
      <div class="brand-text">
        <span class="brand-name">Tenis Klub</span>
        <span class="brand-sub">Upravljanje klubom</span>
      </div>
    </div>
    <nav>
      <a href="#statistike">Statistike</a>
      <a href="#clanovi">Članovi</a>
      <a href="#tereni">Tereni</a>
      <a href="#cjenik">Cjenik</a>
      <a href="#rezervacije">Rezervacije</a>
    </nav>
  </div>
</header>

<!-- HERO -->
<div class="hero">
  <div class="hero-content">
    <h1>Tenis Klub</h1>
    <p>Sustav za upravljanje članovima, terenima i rezervacijama</p>
    <div class="hero-btns">
      <a href="dodaj_clana.php" class="btn-hero-primary">+ Dodaj člana</a>
      <a href="dodaj_rezervaciju.php" class="btn-hero-secondary">+ Nova rezervacija</a>
    </div>
  </div>
</div>

<div class="wrapper">
  <?= $poruka ?>

  <!-- STATISTIKE -->
  <section id="statistike">
    <h2 class="sec-title">Statistike kluba</h2>
    <div class="cards">
      <div class="card">
        <div class="card-icon">👥</div>
        <div class="card-num"><?= $br_clanova ?></div>
        <div class="card-lbl">Ukupno članova</div>
      </div>
      <div class="card">
        <div class="card-icon">🏟️</div>
        <div class="card-num"><?= $br_terena ?></div>
        <div class="card-lbl">Dostupnih terena</div>
      </div>
      <div class="card">
        <div class="card-icon">📅</div>
        <div class="card-num"><?= $br_rezerv ?></div>
        <div class="card-lbl">Rezervacija</div>
      </div>
      <div class="card card-accent">
        <div class="card-icon">💰</div>
        <div class="card-num"><?= number_format($uk_prihod, 2) ?> €</div>
        <div class="card-lbl">Ukupni prihod</div>
      </div>
    </div>
  </section>

  <!-- CLANOVI -->
  <section id="clanovi">
    <div class="sec-header">
      <h2 class="sec-title">Popis članova</h2>
      <a href="dodaj_clana.php" class="btn-add">+ Dodaj člana</a>
    </div>
    <div class="tbl-wrap">
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Ime i prezime</th>
            <th>Email</th>
            <th>Telefon</th>
            <th>God. članarina</th>
            <th>Datum upisa</th>
            <th>Akcija</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($c = mysqli_fetch_assoc($clanovi)): ?>
          <tr>
            <td class="id"><?= $c['ID_Clana'] ?></td>
            <td><strong><?= htmlspecialchars($c['Ime'] . ' ' . $c['Prezime']) ?></strong></td>
            <td><?= htmlspecialchars($c['Email']) ?></td>
            <td><?= htmlspecialchars($c['Telefon']) ?></td>
            <td>
              <span class="badge <?= $c['Godisnja_clanarina'] ? 'b-yes' : 'b-no' ?>">
                <?= $c['Godisnja_clanarina'] ? 'DA' : 'NE' ?>
              </span>
            </td>
            <td><?= date('d.m.Y', strtotime($c['Datum_upisa'])) ?></td>
            <td>
              <a href="obrisi_clana.php?id=<?= $c['ID_Clana'] ?>"
                 class="btn-del"
                 onclick="return confirm('Obrisati ovog člana?')">Obriši</a>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </section>

  <!-- TERENI -->
  <section id="tereni">
    <h2 class="sec-title">Tereni</h2>
    <div class="tbl-wrap">
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Naziv</th>
            <th>Tip</th>
            <th>Opis</th>
            <th>Dostupnost</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($t = mysqli_fetch_assoc($tereni)): ?>
          <tr>
            <td class="id"><?= $t['ID_Terena'] ?></td>
            <td><strong><?= htmlspecialchars($t['Naziv']) ?></strong></td>
            <td>
              <span class="badge <?= $t['Tip'] === 'Otvoreni' ? 'b-open' : 'b-closed' ?>">
                <?= $t['Tip'] ?>
              </span>
            </td>
            <td><?= htmlspecialchars($t['Opis'] ?? '—') ?></td>
            <td>
              <span class="badge <?= $t['Dostupan'] ? 'b-yes' : 'b-no' ?>">
                <?= $t['Dostupan'] ? 'Dostupan' : 'Nedostupan' ?>
              </span>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </section>

  <!-- CJENIK -->
  <section id="cjenik">
    <h2 class="sec-title">Cjenik</h2>
    <div class="tbl-wrap">
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Doba dana</th>
            <th>Cijena po satu</th>
            <th>Radno vrijeme</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($cj = mysqli_fetch_assoc($cjenik)): ?>
          <tr>
            <td class="id"><?= $cj['ID_Cjenika'] ?></td>
            <td><strong><?= htmlspecialchars($cj['Doba_dana']) ?></strong></td>
            <td class="price"><?= number_format($cj['Cijena_po_satu'], 2) ?> €/h</td>
            <td><?= htmlspecialchars($cj['Opis'] ?? '—') ?></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </section>

  <!-- REZERVACIJE -->
  <section id="rezervacije">
    <div class="sec-header">
      <h2 class="sec-title">Rezervacije</h2>
      <a href="dodaj_rezervaciju.php" class="btn-add">+ Nova rezervacija</a>
    </div>
    <div class="tbl-wrap">
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Član</th>
            <th>Teren</th>
            <th>Tip terena</th>
            <th>Doba dana</th>
            <th>Datum</th>
            <th>Početak</th>
            <th>Završetak</th>
            <th>Ukupno</th>
            <th>Napomena</th>
            <th>Akcija</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($r = mysqli_fetch_assoc($rezervacije)): ?>
          <tr>
            <td class="id"><?= $r['ID_Rezervacije'] ?></td>
            <td><strong><?= htmlspecialchars($r['Clan']) ?></strong></td>
            <td><?= htmlspecialchars($r['Teren']) ?></td>
            <td>
              <span class="badge <?= $r['Tip_terena'] === 'Otvoreni' ? 'b-open' : 'b-closed' ?>">
                <?= $r['Tip_terena'] ?>
              </span>
            </td>
            <td><?= htmlspecialchars($r['Doba_dana']) ?></td>
            <td><?= date('d.m.Y', strtotime($r['Datum'])) ?></td>
            <td><?= substr($r['Vrijeme_pocetka'], 0, 5) ?></td>
            <td><?= substr($r['Vrijeme_zavrsetka'], 0, 5) ?></td>
            <td class="price"><?= number_format($r['Ukupna_cijena'], 2) ?> €</td>
            <td><?= htmlspecialchars($r['Napomena'] ?? '—') ?></td>
            <td>
              <a href="obrisi_rezervaciju.php?id=<?= $r['ID_Rezervacije'] ?>"
                 class="btn-del"
                 onclick="return confirm('Obrisati rezervaciju?')">Obriši</a>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </section>

</div><!-- /wrapper -->

<footer>
  <p>© 2025 Tenis Klub &nbsp;·&nbsp; Jan Horak &nbsp;·&nbsp; Tehnička škola Daruvar</p>
</footer>

</body>
</html>
