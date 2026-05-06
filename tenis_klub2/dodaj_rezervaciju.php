<?php
require 'db_connection.php';
$page_title = 'Nova rezervacija – Tenis Klub';
require 'header.php';

$clanovi = mysqli_query($db, "SELECT ID_Clana, Ime, Prezime FROM clan ORDER BY Prezime, Ime");
$tereni  = mysqli_query($db, "SELECT ID_Terena, Naziv, Tip FROM teren WHERE Dostupan=1 ORDER BY ID_Terena");
$cjenik  = mysqli_query($db, "SELECT ID_Cjenika, Doba_dana, Cijena_po_satu FROM cjenik ORDER BY ID_Cjenika");
?>

<div class="wrapper">
  <section>
    <h2 class="sec-title">Nova rezervacija</h2>

    <?php if (isset($_GET['err'])): ?>
      <div class="msg msg-err"><?= htmlspecialchars($_GET['err']) ?></div>
    <?php endif; ?>

    <div class="form-box">
      <form action="spremi_rezervaciju.php" method="POST">

        <div class="form-row">
          <div class="fg">
            <label for="id_clana">Član *</label>
            <select id="id_clana" name="id_clana" required>
              <option value="">— odaberi člana —</option>
              <?php while ($c = mysqli_fetch_assoc($clanovi)): ?>
                <option value="<?= $c['ID_Clana'] ?>">
                  <?= htmlspecialchars($c['Prezime'] . ', ' . $c['Ime']) ?>
                </option>
              <?php endwhile; ?>
            </select>
          </div>

          <div class="fg">
            <label for="id_terena">Teren *</label>
            <select id="id_terena" name="id_terena" required>
              <option value="">— odaberi teren —</option>
              <?php while ($t = mysqli_fetch_assoc($tereni)): ?>
                <option value="<?= $t['ID_Terena'] ?>">
                  <?= htmlspecialchars($t['Naziv'] . ' (' . $t['Tip'] . ')') ?>
                </option>
              <?php endwhile; ?>
            </select>
          </div>
        </div>

        <div class="form-row">
          <div class="fg">
            <label for="id_cjenika">Doba dana *</label>
            <select id="id_cjenika" name="id_cjenika" required>
              <option value="">— odaberi —</option>
              <?php while ($cj = mysqli_fetch_assoc($cjenik)): ?>
                <option value="<?= $cj['ID_Cjenika'] ?>">
                  <?= htmlspecialchars($cj['Doba_dana']) ?>
                  (<?= number_format($cj['Cijena_po_satu'], 2) ?> €/h)
                </option>
              <?php endwhile; ?>
            </select>
          </div>

          <div class="fg">
            <label for="datum">Datum *</label>
            <input type="date" id="datum" name="datum"
                   value="<?= date('Y-m-d') ?>" required>
          </div>
        </div>

        <div class="form-row">
          <div class="fg">
            <label for="pocetak">Početak *</label>
            <input type="time" id="pocetak" name="pocetak" required>
          </div>
          <div class="fg">
            <label for="zavrsetak">Završetak *</label>
            <input type="time" id="zavrsetak" name="zavrsetak" required>
          </div>
        </div>

        <div class="fg">
          <label for="napomena">Napomena</label>
          <input type="text" id="napomena" name="napomena" placeholder="npr. Trening, Turnir...">
        </div>

        <div class="form-actions">
          <a href="index.php" class="btn-cancel">Odustani</a>
          <button type="submit" class="btn-submit">Spremi rezervaciju</button>
        </div>

      </form>
    </div>
  </section>
</div>

<footer>
  <p>© 2025 Tenis Klub &nbsp;·&nbsp; Jan Horak &nbsp;·&nbsp; Tehnička škola Daruvar</p>
</footer>
</body>
</html>
