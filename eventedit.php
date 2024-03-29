<?php
session_start();
if (!isset($_SESSION['Username'])) {
    header("Location: inloggen.php");
}
require 'constants.php';
try {
    $dbHandler = new PDO ("mysql:host={$dbhost};dbname={$dbname};charset=utf8;", "{$dbuser}", "{$dbpassword}");
} catch (Exception $ex) {
    $error = "Er is een fout bij het maken van verbinding met de database";
}
if (isset($dbHandler)) {
    try {
        $stmt = $dbHandler->prepare("SELECT CustomerID, Event.EventID, EventName, Date, GROUP_CONCAT(TalentName SEPARATOR ', ') as TalentName, Location, Price, Event.Description 
                                                FROM Event
                                                JOIN eventOccasion ON Event.EventID = eventOccasion.EventID
                                                JOIN Talentprofile ON eventOccasion.TalentID = Talentprofile.TalentID
                                                WHERE Event.EventID = :EventID;");
        $stmt->bindParam("EventID", $_GET['id'], PDO::PARAM_STR);
        $stmt->execute();
    } catch (Exception $ex) {
        echo $ex;
    }
    $customerid = $dbHandler->prepare("SELECT * FROM Customer;");
    try {
        $customerid->execute();
    } catch (Exception $ex) {
        echo $ex;
    }
    $err = [];
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $statement = $dbHandler->prepare("UPDATE Event SET CustomerID = :CustomerID, EventName = :EventName, Location = :Location, Price = :Price, Date = :Date, Description = :Description WHERE EventID = :EventID;");

        if (!$CustomerID = filter_input(INPUT_POST, 'CustomerID', FILTER_VALIDATE_INT)) {
            $err[] = "Vergeten het klant ID toe te voegen";
        }
        if (!$EventName = filter_input(INPUT_POST, 'EventName', FILTER_SANITIZE_SPECIAL_CHARS)) {
            $err[] = "Vergeten de naam van het evenement toe te voegen";
        }
        if (!$Location = filter_input(INPUT_POST, 'Location', FILTER_SANITIZE_SPECIAL_CHARS)) {
            $err[] = "Vergeten locatie toe te voegen aan evenement";
        }
        if (!$Price = filter_input(INPUT_POST, 'Price', FILTER_VALIDATE_FLOAT)) {
            $err[] = "Prijs mag alleen bestaan uit cijfers";
        }
        if (!$Date = filter_input(INPUT_POST, 'Date', FILTER_SANITIZE_SPECIAL_CHARS)) {
            $err[] = "Vergeten de datum van het evenement in te voeren";
        }
        if (!$Description = filter_input(INPUT_POST, 'Description', FILTER_SANITIZE_SPECIAL_CHARS)) {
            $err[] = "Geen beschrijving van het evenement toegevoegd";
        }
        if (count($err) == 0) {
            $statement->bindParam("CustomerID", $CustomerID, PDO::PARAM_STR);
            $statement->bindParam("EventName", $EventName, PDO::PARAM_STR);
            $statement->bindParam("Location", $Location, PDO::PARAM_STR);
            $statement->bindParam("Price", $Price, PDO::PARAM_STR);
            $statement->bindParam("Date", $Date, PDO::PARAM_STR);
            $statement->bindParam("Description", $Description, PDO::PARAM_STR);
            $statement->bindParam("EventID", $_GET['id'], PDO::PARAM_STR);
            try {
                $statement->execute();
            } catch (Exception $ex) {
                echo $ex;
            }
        }
        if (count($err) == 0) {
            $success = "Evenement is succesvol bewerkt";
        }
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
<main id="background">
    <div id="eventstitle">
        <h1> Evenementen </h1>
    </div>
    <div id="purplebarwidth"></div>
    <div id="addcontainer">
        <h1>Evenement Bewerken </h1>
        <div id="overrulebox">
            <form name="eventedit" action="eventedit.php?id=<?php echo $_GET['id']; ?>"
                  method="POST">
                <?php
                while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "
                    <div>
                        <label for='EventName'>Naam evenement</label>
                        <input type='text' name='EventName' placeholder='$result[EventName]'>
                    </div>
                    <div>
                        <label for='Location'>Locatie</label>
                        <input type='text' name='Location' placeholder='$result[Location]'>
                    </div>
                    <div>
                        <label for='Price'>Prijs</label>
                        <input type='text' name='Price' placeholder='$result[Price]'>
                    </div>
                    <div>
                        <label for='Date'>Datum</label>
                        <input type='date' name='Date' required>
                    </div>
                    <div>
                        <label for='CustomerID'>Klant ID</label>
                        <input type='text' name='CustomerID' placeholder='$result[CustomerID]'>
                    </div>  
                    <div>
                        <label for='Description'>Beschrijving</label>
                        <textarea name='Description' placeholder='$result[Description]'></textarea>
                    </div>
                    ";
                }
                ?>
                <input type="hidden" name="eventID" value="$_GET['id']">
                <input type="submit" value="Verzenden">
            </form>
            <?php
            if (isset($success)) {
                echo "<p>$success</p>";
            }
            if (count($err) > 0) {
                echo "<ul>";
                foreach ($err as $error) {
                    echo "<li>$error</li>";
                }
                echo "</ul>";
            }
            ?>
            <div id="box">
                <table>
                    <tr>
                        <th>Klant ID</th>
                        <th>Klant naam</th>
                    </tr>
                    <?php
                    while ($result = $customerid->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        echo "<td>$result[CustomerID]</td>";
                        echo "<td>$result[Name]</td>";
                        echo "</tr>";
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>
    <div id="subfooter">
        <p>Privacy Policy l Algemene voorwaarden l Disclaimer l Cookies</p>
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