<?php

  require 'constants.php';

  try{
    $dbHandler = new PDO ("mysql:host={$dbhost};dbname={$dbname};charset=utf8;", "{$dbuser}", "{$dbpassword}");
  }
  catch (Exception $ex){
    echo $ex;
  }
  $date = date("Y-m-d");
  if(isset($dbHandler)) {
    try {
      
      $stmt = $dbHandler->prepare("SELECT * FROM Event WHERE Date >= :date ORDER BY date");
      $stmt->bindParam("date", $date, PDO::PARAM_STR);
      $stmt->execute();
    } catch (Exception $ex) {
      echo $ex;
    }
  }

?>



<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/normalize.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <title>E3T</title>
  </head>
  <body>
    <header>
      <div id="headerContent">
      <h2>E3T</h2>
      </div>
      <nav>
        <a href="talenten.php">Talenten</a>
        <a href="evenementen.php">Evenementen</a>
        <a href="inloggen.php">Inloggen</a>
      </nav>
    </header>
    
    <main>
      <div id="info";>
        <h1>E3T</h1>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
      </div>
      <div id="agenda">
        <h2>Aankomende Evenementen</h2>
          <?php
            if ($stmt->rowCount() > 0) {
              echo "<table>";
              echo "<tr>";
                echo "<th>Evenement</th>";
                echo "<th>Locatie</th>";
                echo "<th>Datum</th>";
              echo "</tr>";
              $rows = $stmt->fetchAll();
              $count = 5;
                foreach ($rows as $row):
                  if ($count > 0):
                    ?>
                    <tr>
                      <td><?php echo $row['Name'];?></td>
                      <td><?php echo $row['Location'];?></td>
                      <td><?php echo $row['Date'];?></td>
                    </tr>
                  <?php
                    $count--;
                  endif;
                endforeach;
              echo "</table>";
            } else {
              echo "<p>*Er zijn op dit moment geen aankomende evenementen*</p>";
            }

            $dbHandler = null;
            
          ?>
        </table>
      </div>
      <div id="info2">
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
      </div>
      </main>
      <footer>
        <div id="item1">
          <h3>Informatie</h3>
          <ul>
            <li>Veelgestelde Vragen</li>
            <li>Over Ons</li>
            <li>Vacetures</li>
            <li>Terms of Service</li>
            <li>Privacy Policy</li>
            <li>Recenties</li>
          </ul>
        </div>
        <div id="item2">
          <h3>Contact</h3>
          <ul>
            <li>Straatnaam 1</li>
            <li>1234PV</li>
            <li>06-12345678</li>
            <li>support@E3T.com</li>
          </ul>
        </div>
        <div id="item3">
          <h3>Social Media</h3>
          <img src="img/logos.png" alt="social media logos">
        </div>
      </footer>
      <div id="subfooter";>
        <p>Privacy Policy  l  Algemene voorwaarden  l  Disclaimer  l  Cookies</p>
      </div>
  </body>
</html>

