
<?php

include("db_config.php");

$id = "";
$tafel   = "";
$datum = "";
$tijd = "";
$aantal = "";
$allergieen = "";
$opmerkingen = "";
$naam = "";
$telefoon = "";
$email = "";

if (isset($_POST['submit'])) {
    $tafel = filter_var($_POST['Tafel'], FILTER_SANITIZE_STRING); // to filter string
    $datum  = filter_var($_POST['Datum'], FILTER_SANITIZE_STRING);
    $tijd  = filter_var($_POST['Tijd'], FILTER_SANITIZE_STRING);
    $aantal  = filter_var($_POST['Aantal'], FILTER_SANITIZE_NUMBER_INT);
    $allergieen  = filter_var($_POST['Allergieen'], FILTER_SANITIZE_STRING);
    $opmerkingen  = filter_var($_POST['Opmerkingen'], FILTER_SANITIZE_STRING);
    $naam  = filter_var($_POST['Naam'], FILTER_SANITIZE_STRING);
    $telefoon  = filter_var($_POST['Telefoon'], FILTER_SANITIZE_NUMBER_INT);
    $email  = filter_var($_POST['Email'], FILTER_SANITIZE_STRING);
    $check_telefoon = $pdo->prepare("select * from klanten where Telefoon = '" . $telefoon . "' and ID not in ('".$id."')"); // to check duplicate
    $check_telefoon->execute();
    if ($check_telefoon->rowCount() > 0) {
        header("Location: index.php?message=Duplicate entry");
    } else {
        $insert_query = $pdo->prepare("INSERT INTO reserveringen (Tafel,Datum,Tijd,Aantal,Allergieen,Opmerkingen) VALUES (:tafel,:datum,:tijd, :aantal, :allergieen, :opmerkingen)") . ("INSERT INTO klanten (Naam,Telefoon,Email) VALUES (:naam,:telefoon,:email)");
            $pdo->beginTransaction();
            $insert_query->bindParam(":tafel", $tafel);
            $insert_query->bindParam(":datum", $datum);
            $insert_query->bindParam(":tijd", $tijd);
            $insert_query->bindParam(":aantal", $aantal);
            $insert_query->bindParam(":allergieen", $allergieen);
            $insert_query->bindParam(":opmerkingen", $opmerkingen);
            $insert_query->bindParam(":naam", $naam);
            $insert_query->bindParam(":telefoon", $telefoon);
            $insert_query->bindParam(":email", $email);
            $insert_query->execute();
            if ($pdo->lastInsertId() > 0) {
                header("Location: reserveer.php"); //success data insertion
                header("Location: reserveer.php?message=Record has been inserted successfully")
            } else {
                header("Location: index.php?message=Failed to insert"); //failure data insertion
            }
            $pdo->commit();
        }
        catch (PDOExecption $e) {
            $dbh->rollback();
            print "Error!: " . $pdo->getMessage() . "</br>"; //exception
        }
    }
}

?>