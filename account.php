<?php
    session_start();
    require 'constants.php';
    if(!isset($_SESSION['Username'])){
        header("Location: inloggen.php");
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
                <div id="accountMenu">
                    <h1>Account</h1>
                    <ul>
                        <?php
                            if($_SESSION['Role'] == "admin"){
                                echo "<li><a href='registeraccount.php'>Account aanmaken</a></li>";
                                echo "<li><a href='manageaccounts.php'>Accounts beheren</a></li>";
                                echo "<li><a href='registertalent.php'>Talentprofiel aanmaken</a></li>";
                                echo "<li><a href='manageprofile.php'>Talentprofielen beheren</a></li>";
                            }
                            if($_SESSION['Role'] == "talent"){
                                try{
                                    $dbHandler = new PDO ("mysql:host={$dbhost};dbname={$dbname};charset=utf8;", "{$dbuser}", "{$dbpassword}");
                                }
                                catch (Exception $ex){
                                    $error = "Er is een verbindings fout met de database";
                                }
                                if(!isset($error)) {
                                    $stmt = $dbHandler->prepare("SELECT TalentID FROM `Login` WHERE LoginID = :id");
                                    $stmt->bindParam("id", $_SESSION['LoginID'], PDO::PARAM_INT);
                                    $stmt->bindColumn("TalentID", $talentID, PDO::PARAM_INT);
                                    try{
                                        $stmt->execute();
                                    }
                                    catch(Exception $ex){
                                        $error = "Er is een verbindings fout met de database";
                                    }
                                    if(!isset($error)){
                                        $stmt->fetch(PDO::FETCH_ASSOC);
                                        if(isset($talentID)){
                                            echo "<li><a href='profile.php?id=$talentID'>Eigen profiel</a></li>";
                                            echo "<li><a href='editownprofile.php'>Profiel bewerken</a></li>";
                                        }
                                    }
                                }
                            }
                        ?>
                        <li><a href='logout.php'>Log uit</a></li>
                    </ul>
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