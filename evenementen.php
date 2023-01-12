<?php
session_start();
require 'constants.php';

try {
    $dbHandler = new PDO ("mysql:host={$dbhost};dbname={$dbname};charset=utf8;", "{$dbuser}", "{$dbpassword}");
} catch (Exception $ex) {
    $error = "Er is een fout bij het maken van verbinding met de database";
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $search = filter_input(INPUT_GET, "search", FILTER_SANITIZE_SPECIAL_CHARS);
    if (isset($search)) {
        try {

            $stmt = $dbHandler->prepare("SELECT Event.EventID, EventName, Date, GROUP_CONCAT(TalentName SEPARATOR ', ') as TalentName, Location, Price, Event.Description 
                                                    FROM Event
                                                    JOIN eventOccasion ON Event.EventID = eventOccasion.EventID
                                                    JOIN Talentprofile ON eventOccasion.TalentID = Talentprofile.TalentID
                                                    WHERE EventName LIKE :search or Event.Description LIKE :search
                                                    GROUP BY Event.EventID;");
            $search = "%$search%";
            $stmt->bindParam("search", $search, PDO::PARAM_STR);

            $stmt->execute();
        } catch (Exception $ex) {
            echo $ex;
        }
    } else {
        if (isset($dbHandler)) {
            try {
                $stmt = $dbHandler->prepare("SELECT Event.EventID, EventName, Date, GROUP_CONCAT(TalentName SEPARATOR ', ') as TalentName, Location, Price, Event.Description 
                                                        FROM Event
                                                        JOIN eventOccasion ON Event.EventID = eventOccasion.EventID
                                                        JOIN Talentprofile ON eventOccasion.TalentID = Talentprofile.TalentID
                                                        GROUP BY Event.EventID;");
                $stmt->execute();
            } catch (Exception $ex) {
                echo $ex;
            }
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
    <div id="upcoming">
        <h1> Aankomende Evenementen </h1>
        <div id="searchform">
            <form method="GET" action="evenementen.php">
                <input type="text" name="search" id="searchform" placeholder="search...">
            </form>
        </div>
        <div id="agenda2">
            <table>
                <tr>
                    <th>Evenementen</th>
                    <th>Datum</th>
                    <th>Talenten</th>
                    <th>Locatie</th>
                    <th>Prijs</th>
                    <th>Beschrijvijng</th>
                    <?php
                    if (isset($_SESSION['Role'])) {
                        if ($_SESSION['Role'] == "admin") {
                            echo "<th>Verwijderen</th>";
                        }
                    }
                    ?>
                </tr>
                <?php
                while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>$result[EventName]</td>";
                    echo "<td>$result[Date]</td>";
                    echo "<td>$result[TalentName]</td>";
                    echo "<td>$result[Location]</td>";
                    echo "<td>$result[Price]</td>";
                    echo "<td>$result[Description]</td>";
                    if (isset($_SESSION['Role'])) {
                        if ($_SESSION['Role'] == "admin") {
                            echo "<td><a href='eventdelete.php?id=$result[EventID]'>Verwijderen</a></td>";
                        }
                    }
                    echo "</tr>";
                }
                ?>
            </table>
        </div>
        <div id="eventmanage">
            <?php
            if (isset($_SESSION['Role'])) {
                if ($_SESSION['Role'] == "admin") {
                    echo "<a href='eventadd.php'>Evenement toevoegen</a>";
                    echo " - ";
                    echo "<a href='eventlink.php'>Talent toevoegen aan evenement</a>";
                }
            }
            ?>
        </div>
    </div>
    <div id="subfooter">
        <p>Privacy Policy l Algemene voorwaarden l Disclaimer l Cookies</p>
    </div>
</main>
</body>
</html>