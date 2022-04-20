<?php require_once('./src/layout/header.php'); ?>
<main>
  <div class="card">
    <h2>Welcome to Markup</h2>
    <p>
      TODO: write description
    </p>
    <form id="form" method="GET" action="./check">
      <input id="input" aria-label="Web Page URL" name="url" type="text" required>
      <button type="submit" class="btn">Check Now</button>
    </form>
  </div>
  <iframe id="result" class="card" style="display:none" src="about:blank" title="Result" sandbox></iframe>
  <div class="card">
    <h2>About</h2>
    <p>
      Markup was created by Anvil Solutions
    </p>
    <nav>
      <ul>
        <li><a href="https://fonts.google.com/icons">Material Icons by Google</a></li>
        <li><a href="https://github.com/anvil-solutions/markup">GitHub</a></li>
        <li><a href="http://anvil-solutions.com/en/privacy">Privacy</a></li>
        <li><a href="http://anvil-solutions.com/en/imprint">Imprint</a></li>
    </nav>
  </div>
</main>
<!--script src="./js/main.js" async></script-->
