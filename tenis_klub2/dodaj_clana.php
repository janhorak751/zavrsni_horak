<?php
$page_title = 'Dodaj člana – Tenis Klub';
require 'header.php';
?>

<div class="wrapper">
  <section>
    <h2 class="sec-title">Dodaj novog člana</h2>

    <?php if (isset($_GET['err'])): ?>
      <div class="msg msg-err"><?= htmlspecialchars($_GET['err']) ?></div>
    <?php endif; ?>

    <div class="form-box">
      <form action="spremi_clana.php" method="POST">

        <div class="form-row">
          <div class="fg">
            <label for="ime">Ime *</label>
            <input type="text" id="ime" name="ime" required placeholder="npr. Marko">
          </div>
          <div class="fg">
            <label for="prezime">Prezime *</label>
            <input type="text" id="prezime" name="prezime" required placeholder="npr. Kovač">
          </div>
        </div>

        <div class="form-row">
          <div class="fg">
            <label for="email">Email *</label>
            <input type="email" id="email" name="email" required placeholder="marko@email.com">
          </div>
          <div class="fg">
            <label for="telefon">Telefon *</label>
            <input type="text" id="telefon" name="telefon" required placeholder="091 123 4567">
          </div>
        </div>

        <div class="fg">
          <label for="datum_upisa">Datum upisa *</label>
          <input type="date" id="datum_upisa" name="datum_upisa"
                 value="<?= date('Y-m-d') ?>" required>
        </div>

        <div class="fg fg-check">
          <label>
            <input type="checkbox" name="godisnja_clanarina" value="1">
            Godišnja članarina plaćena
          </label>
        </div>

        <div class="form-actions">
          <a href="index.php" class="btn-cancel">Odustani</a>
          <button type="submit" class="btn-submit">Spremi člana</button>
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
