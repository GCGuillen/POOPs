<?php
    require "header.php";
?>

    <main>
      <div class="wrapper-main">
        <section class="section-default">
          <h1>Signup</h1>
          <?php
            if (isset($_GET['error']))
            {
              if ($_GET['error'] == "emptyfields")
              {
                echo '<p class="signuperror">Fill in all fields!</p>';
              }
              else if ($_GET['error'] == "invaliduidmail")
              {
                echo '<p class="signuperror">Invalide username and e-mail!</p>';
              }
              else if ($_GET['error'] == "invaliduid")
              {
                echo '<p class="signuperror">Invalide username!</p>';
              }
              else if ($_GET['error'] == "invalidmail")
              {
                echo '<p class="signuperror">Invalide e-mail!</p>';
              }
              else if ($_GET['error'] == "passwordcheck")
              {
                echo '<p class="signuperror">Your passwords do not match!</p>';
              }
              else if ($_GET['error'] == "logininformationtaken")
              {
                echo '<p class="signuperror">Either your e-mail or username is already in use!</p>';
              }
            }
            else if ($_GET['signup'] == "success")
            {
              echo '<p class="signupsuccess">Signup succesful!</p>';
            }
           ?>
          <form action="includes/signup.inc.php" method="post">
            <input type="text" name="uid" placeholder="Username">
            <input type="text" name="mail" placeholder="E-mail">
            <input type="password" name="pwd" placeholder="Password">
            <input type="password" name="pwd-repeat" placeholder="Repeat Password">
            <button type="submit" name="signup-submit">Signup</button>
          </form>
        </section>
      </div>
    </main>

<?php
    require "footer.php";
?>
