<!-- Try 4 -->
<?php
    include_once 'php/Conexion.inc.php';

    Conexion::abrirConexion(); 

    if ($_POST)
    {
        if ($_POST["enviar"])
        {
            /// Guardar imagen en el servidor
            /// Solo de manera temporal.
            copy($_FILES['image']['tmp_name'], "temp/temp_img.png");

            /// Obtener la ruta absoluta de la imagen
            $temp_img_route = $_SERVER['DOCUMENT_ROOT']."/ImagenesDB/temp/temp_img.png";

            /// Crear sentencia para
            /// subir la imagen a la DB
            $sql = "INSERT INTO `images` (`imagen`, `type`) VALUES (LOAD_FILE('".$temp_img_route."'), '".$_FILES['image']['type']."')";

            /// Ejecutar sentencia SQL
            $sentencia = Conexion::getConexion()->prepare($sql);
            $insert = $sentencia->execute();

            /// Verificar si la imagen se a subido correctamente
            $aviso = ($insert) ? "Imagen Subida Exitosamente!" : "Algo salio mal, intentalo de nuevo";

            /// Imprimir resultado del aviso
            echo $aviso;

            /// Eliminar imagen temporal del servidor
            unlink("temp/temp_img.png");
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
                <input class="w3-input w3-border" style="overflow: hidden;" type="file" name="image" id="image" accept="image/*" alt="" required=""/>
            </p>
            <p>
                <input class="w3-input" type="submit" name="enviar" id="enviar" value="Enviar"/>
            </p>
        </form>

        <center>

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

