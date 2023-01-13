<?php
session_start();
if (!isset($_SESSION['Username'])) {
    header("Location: inloggen.php");
}
require 'constants.php';
try {
    $dbHandler = new PDO ("mysql:host={$dbhost};dbname={$dbname};charset=utf8;", "{$dbuser}", "{$dbpassword}");
} catch (Exception $ex) {
    echo $ex;
}
if (isset($dbHandler)) {
    $stmt = $dbHandler->prepare("SELECT * FROM Customer;");
    try {
        $stmt->execute();
    } catch (Exception $ex) {
        echo $ex;
    }
    $err = [];
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $statement = $dbHandler->prepare("INSERT INTO Event (CustomerID, EventName, Location, Price, Date, Description) 
                                                VALUES (:CustomerID, :EventName, :Location, :Price, :Date, :Description);");

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
            try {
                $statement->execute();
            } catch (Exception $ex) {
                echo $ex;
            }
        }
        if (count($err) == 0) {
            $success = "Evenement is succesvol toegevoegd";
        }
    }
}
$dbHandler = null;
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
        <h2>E3T</h2>
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
        <h1>Evenement Toevoegen </h1>
        <div id="overrulebox">
            <form name="eventadd" action="<?= filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_SPECIAL_CHARS); ?>"
                  method="POST">
                <div>
                    <label for="EventName">Naam evenement</label>
                    <input type="text" name="EventName">
                </div>
                <div>
                    <label for="Location">Locatie</label>
                    <input type="text" name="Location">
                </div>
                <div>
                    <label for="Price">Prijs</label>
                    <input type="text" name="Price">
                </div>
                <div>
                    <label for="Date">Datum</label>
                    <input type="date" name="Date" required>
                </div>
                <div>
                    <label for="CustomerID">Klant ID</label>
                    <input type="text" name="CustomerID">
                </div>
                <div>
                    <label for="Description">Beschrijving</label>
                    <textarea name="Description"></textarea>
                </div>
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
                    while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
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
</body>
</html>
