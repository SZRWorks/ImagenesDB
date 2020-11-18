<?php
include_once 'php/Conexion.inc.php';

$envio = filter_input(INPUT_POST, 'enviar');
Conexion::abrirConexion();

if (isset($envio)) {
    if (is_uploaded_file($_FILES['image']['tmp_name'])) {
        try{
        $imgContent = file_get_contents($_FILES['image']['tmp_name']);
        //$imgContent = fopen($_FILES['image']['tmp_name'], 'rb');
        $imageProperties = getimageSize($_FILES['image']['tmp_name']);

        $sql = "INSERT INTO images (imagen, type) VALUES (:image, :type)";
        $sentencia = Conexion::getConexion()->prepare($sql);
        $sentencia->bindParam(':image', $imgContent, PDO::PARAM_LOB);
        $sentencia->bindParam(':type', $imageProperties['mime'], PDO::PARAM_STR);
        $insert = $sentencia->execute();

        $aviso = ($insert) ? "Imagen Subida Exitosamente!" : "Algo salio mal, intentalo de nuevo";
        echo $aviso;
        }catch (PDOException $ex){
            echo $ex->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>ImagesDB</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/w3.css">
    </head>
    <body>
        <form class="w3-display-topmiddle" method="POST" enctype="multipart/form-data">
            <p>
                <label for="image">Subir Imagen</label>
                <input class="w3-input w3-border" style="overflow: hidden;" type="file" name="image" accept="image/*" alt="" required=""/>
            </p>
            <p>
                <input class="w3-input" type="submit" name="enviar" value="enviar"/>
            </p>
        </form>
        <div class="w3-container">
            <?php
            foreach (Conexion::getConexion()->query("SELECT * FROM images") as $row) {
                echo '<img src="data:'.$row['type'].';charset=utf8;base64,'
                . base64_encode($row['imagen']) . '" width="500" height="300" alt="..."/>';
            }
            ?>
        </div>
    </body>
</html>
<?php
Conexion::cerrarConexion();
