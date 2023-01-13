<?php
session_start();
if (!isset($_SESSION['Username'])) {
    header("Location: inloggen.php");
}
require 'constants.php';
try {
    $dbHandler = new PDO ("mysql:host={$dbhost};dbname={$dbname};charset=utf8;", "{$dbuser}", "{$dbpassword}");
} catch (Exception $ex) {
    $error = "Kan geen verbinding maken met de database";
}
if (isset($dbHandler)) {
    $err = [];

    $alltalents = $dbHandler->prepare("SELECT * FROM Talentprofile;");
    try {
        $alltalents->execute();
    } catch (Exception $ex) {
        echo $ex;
    }
    $allevents = $dbHandler->prepare("SELECT * FROM Event;");
    try {
        $allevents->execute();
    } catch (Exception $ex) {
        $error = "Kan geen events vinden";
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $stmt = $dbHandler->prepare("INSERT INTO eventOccasion (TalentID, EventID) VALUES (:TalentID, :EventID)");
        if (!$TalentID = filter_input(INPUT_POST, 'TalentID', FILTER_VALIDATE_INT)) {
            $err[] = "Vergeten het klant ID toe te voegen";
        }
        if (!$EventID = filter_input(INPUT_POST, 'EventID', FILTER_VALIDATE_INT)) {
            $err[] = "Vergeten het Event ID toe te voegen";
        }
        if (count($err) == 0) {
            $stmt->bindParam("TalentID", $TalentID, PDO::PARAM_STR);
            $stmt->bindParam("EventID", $EventID, PDO::PARAM_STR);
            try {
                $stmt->execute();
            } catch (Exception $ex) {
                echo $ex;
            }
        }
        if (count($err) == 0) {
            $success = "Talent is succesvol gelinkt aan het evenement";
        }
        else {
            echo $err;
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
    <div id="linkcontainer">
        <h1>Talent toevoegen aan evenement</h1>
        <?php
        if (isset($success)) {
            echo "<p>$success</p>";
        }
        ?>
        <div id="overrulebox">
            <form name="eventlink"
                  action="<?= filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_SPECIAL_CHARS); ?>"
                  method="POST">
                <div>
                    <label for="TalentID">Talent ID</label>
                    <input type="text" name="TalentID">
                </div>
                <div>
                    <label for="EventID">Event ID</label>
                    <input type="text" name="EventID">
                </div>
                <input type="submit" value="Verzenden">
            </form>
            <div id="box">
                <table>
                    <tr>
                        <th>Talent ID</th>
                        <th>Talentnaam</th>
                    </tr>
                    <?php
                    while ($result = $alltalents->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        echo "<td>$result[TalentID]</td>";
                        echo "<td>$result[TalentName]</td>";
                        echo "</tr>";
                    }
                    ?>
                </table>
                <table>
                    <tr>
                        <th>Event ID</th>
                        <th>Event naam</th>
                    </tr>
                    <?php
                    while ($result = $allevents->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        echo "<td>$result[EventID]</td>";
                        echo "<td>$result[EventName]</td>";
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
