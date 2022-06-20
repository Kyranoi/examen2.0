<?php

include_once('db_config.php');

if (!empty($_POST)) {
    pdo($pdo, "INSERT INTO reserveringen (ID, Tafel, Datum, Tijd, Aantal, Klant_ID, Allergieen, Opmerkingen) VALUES (null, ?,?,?,?,?,?,?)", [$_POST['tafel'], $_POST['date'], $_POST['tijd'], $_POST['k_id'], $_POST['aantal'], $_POST['allergieen'], $_POST['opmerkingen'],[$_POST['klantnaam'], $_POST['telefoon']], $_POST['email']]);
    $r_id = $pdo->lastInsertId();

    if($r_id) {
        print_r($_POST);
        pdo($pdo, "INSERT INTO klanten (ID, Naam, Telefoon, Email) VALUES (null, ?,?,?)", [$r_id, $_POST['k_id'], $_POST['naam'], $_POST['telefoon'], $_POST['email']]);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <title>Reserveer</title>
</head>
<body>

<nav class="navbar navbar-expand-lg bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="./index.php">Excellent taste</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="./index.php">Home</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="reserveer.php">Reserveringen</a>
            </li>
            <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Serveren
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="reserveringen.php">Voor kok</a></li>
                <li><a class="dropdown-item" href="reserveer.php">Voor barman</a></li>
                <li><a class="dropdown-item" href="reserveer.php">Voor ober</a></li>
            </ul>

            <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Gegevens
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="reserveringen.php">Drinken</a></li>
                <li><a class="dropdown-item" href="reserveer.php">Eten</a></li>
                <li><a class="dropdown-item" href="reserveer.php">Klanten</a></li>
                <li><a class="dropdown-item" href="reserveer.php">Gerecht hoofdgroep</a></li>
                <li><a class="dropdown-item" href="reserveer.php">Gerecht subgroep</a></li>
            </ul>
        </div>
    </div>
    </nav>
    
    <div class="header">Welkom</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <div class="container"> 
        <h5> Check beschikbaarheid </h5>
        <form action="reserveer.php" method="post">
            <div class="reserveer">
                <input type="hidden" name="r_id" required="required"><br>
                <label class="form-label" style="font-weight: 500;">tafel</label>
                <select name="tafel" class="form-select">
                    <option selected>Kies een tafel</option>
                    <?php echo $options;?>
                </select><br></br>
                <label class="form-label" style="font-weight: 500;">Datum</label>
                <input type="date" name="date" placeholder="Datum" required="required">
                <label class="form-label" style="font-weight: 500;">Tijd</label>
                <input type="time" name="tijd" placeholder="Tijd" required="required"><br>
                <input type="hidden" name="k_id" required="required">
                <label class="form-label" style="font-weight: 500;">Aantal personen</label>
                <input type="number" name="aantal"placeholder="1" min="1" max="5" required="required"></br>
                <label class="form-label" style="font-weight: 500;">allergieen</label>
                <input type="text" name="allergieen" placeholder="Allergieen"><br>
                <label class="form-label" style="font-weight: 500;">Opmerkingen</label>
                <input type="text" name="opmerkingen" placeholder="Opmerkingen"><br>
                <label class="form-label" style="font-weight: 500;">Klantnaam</label>
                <input type="text" name="klantnaam" placeholder="Naam" required="required"><br>
                <label class="form-label" style="font-weight: 500;">Telefoonnummer</label>
                <input type="text" name="telefoon" placeholder="Telefoonnummer" required="required"><br>
                <label class="form-label" style="font-weight: 500;">Email</label>
                <input type="text" name="email" placeholder="Email" required="required"><br>
                <button type="submit">Reserveer</button>
        </form>
    </div>

    <table border="1" width="900px" >

        <tr>
        <th>ID</th>
        <th>Klant_ID</th>
        <th>Tafel</th>
        <th>Datum</th>
        <th>Tijd</th>
        <th>Aantal</th>
        <th>Allergieen</th>
        <th>Opmerkingen</th>
        <th>Naam</th>
        <th>Telefoonnummer</th>
        <th>Email</th>
        </tr>

        <?php 
        $get_datas = $pdo->prepare("SELECT * FROM reserveringen");
        $get_datas->execute();
        if($get_datas->rowCount()>0){
        $i=1;
        while($res=$get_datas->fetch(PDO::FETCH_ASSOC)){
        ?>

        <tr>
        <td align="center"><?php echo $res['ID']; ?></td>
        <td align="center"><?php echo $res['Klant_ID']; ?></td>
        <td align="center"><?php echo $res['Tafel']; ?></td>
        <td align="center"><?php echo $res['Datum']; ?></td>
        <td align="center"><?php echo $res['Tijd']; ?></td>
        <td align="center"><?php echo $res['Aantal']; ?></td>
        <td align="center"><?php echo $res['Allergieen']; ?></td>
        <td align="center"><?php echo $res['Opmerkingen']; ?></td>
        <td><a href="edit.php?ID=<?php echo $res['r_id'];?>">Edit</a><br /><a href="delete.php?ID=<?php echo $res['r_id'];?>">Delete</a></td>
        </tr>

        <?php } }else{
        echo "<tr><td colspan='5'>Records not found</td></tr>";
        } ?>

    </table>

    <script type="text/javascript">
        
        <?php if($_GET['message']){ ?>
            alert('<?php echo $_GET['message'];?>');
        <?php } ?>

    </script>
</body>
</html>