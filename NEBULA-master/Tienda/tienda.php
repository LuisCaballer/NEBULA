<?php

require 'config/database.php';
$db = new database();
$con = $db->conectar();

$sql = $con->prepare("SELECT id, nombre, precio FROM productos WHERE Activo=1");
$sql->execute();
$resultado = $sql->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda</title>
    <link rel="stylesheet" href="style2.css">
</head>
<body>
    
<main>
    <section>
        <div class="container">
            <img src="img/pc.jpn" alt="">
         </div>

    </section>
    
</main>
    
</body>
</html>