<?php
require 'constants.php';
try{
    $dbHandler = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpassword);
    $dbHandler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch(Exception $ex){
       $error = "Er is een verbinding fout met de database";
  }
  if($_SERVER["REQUEST_METHOD"] == "GET"){
    $search = filter_input(INPUT_GET, "search", FILTER_SANITIZE_SPECIAL_CHARS);
    if(isset($search)){
      try {
        $search = "%$search%";
        $stmt = $dbHandler->prepare("SELECT * FROM Talentprofile WHERE Name LIKE :search OR Description LIKE :search;");
        $stmt->bindParam("search", $search, PDO::PARAM_STR);
        $stmt->bindcolumn("Name", $name);
        $stmt->bindcolumn("Description", $description);
        $stmt -> execute();
      } catch(Exception $ex){
         echo $ex;
      }
    }
  else{
    if(isset($dbHandler)){
      try{
        $stmt = $dbHandler->prepare("SELECT * FROM Talentprofile;");
        $stmt->bindcolumn("Name", $name);
        $stmt->bindcolumn("Description", $description);
        $stmt -> execute();
      } catch(Exception $ex){
          echo $ex;
      }
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
				<div id="searchform">
					<form method="GET" action="talenten.php">
						<input type="text" name="search" id="search"  placeholder="search...">
					</form>
				</div>
				
				<?php
           if(isset($error)){
              echo "<p>$error<p>";
           }
           else{
					while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
           echo "<div class ='purplebar'></div>
					    <div class ='talentprofile'>
                <img src='img/profilepic.png' alt='img' class='talentsprofileimg'>
                  <div class ='talentprofiletext'>
                    <h2>$name</h2>
                    <p>$description</p>
							    </div>
					    </div>";
					}
          if($stmt->rowcount() == 0){
            echo "<div id='result'><p>Geen resultaten</p></div>";
          }
        }
				
				?>

				<div class ="purplebar"></div>
			</main>
			<footer>
			
			</footer>
        </body>
    </html>


