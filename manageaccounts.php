<?php
    session_start();
    require 'constants.php';
    if(!isset($_SESSION['Role'])){
        header("Location: inloggen.php");
    }
    else{
        if($_SESSION['Role'] != "admin"){
            header("Location: inloggen.php");
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
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
                <h2><a href="index.php">E3T</a></h2>
            </div>
            <nav>
                <a href="talenten.php">Talenten</a>
                <a href="evenementen.php">Evenementen</a>
                <a href="inloggen.php">Inloggen</a>
            </nav>
        </header>
        <main>
            <div id="manageLayout">
                <h1>Accounts</h1>
                <table id="manageTable">
                    <tr>
                        <th class='tablePadding'>Username</th>
                        <th class='tablePadding'>Role</th>
                        <th class='tablePadding'>Bewerk</th>
                        <th class='tablePadding'>Verwijder</th>
                    </tr>
                    <?php
                        try{
                            $dbHandler = new PDO("mysql:host={$dbhost};dbname={$dbname};charset=utf8;", "{$dbuser}", "{$dbpassword}");
                        }
                        catch (Exception $ex){
                            $error = "Er is een verbindings fout met de database";
                        }
                        if(isset($dbHandler)){
                            $stmt = $dbHandler->prepare("SELECT * FROM `Login`");
                            $stmt->bindColumn("LoginID", $id);
                            $stmt->bindColumn("Username", $username);
                            $stmt->bindColumn("Role", $role);
                            try {
                                $stmt->execute();
                            }
                            catch(Exception $ex){
                                $error = "Er is een verbindings fout met de database";
                            }
                            if(!isset($error)){
                                while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
                                    echo "<tr>
                                            <td class='tablePadding'>$username</td>
                                            <td class='tablePadding'>$role</td>
                                            <td class='tablePadding'><a href='editaccount.php?id={$id}'>Bewerk</a></td>
                                            <td class='tablePadding'><a href='deleteaccount.php?id={$id}'>Verwijder</a></td>
                                        </tr>";
                                }
                            }
                        }
                        if(isset($error)){
                            echo "<p class='error'>$error</p>";
                        }
                        $dbHandler = null;
                    ?>
                </table>
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