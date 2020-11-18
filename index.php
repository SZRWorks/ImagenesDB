<!-- Try 4 -->
<?php
    include_once 'php/Conexion.inc.php';

    Conexion::abrirConexion(); 
    
    //PHP 7 chad, no permite el acceso directo a superglobal variables
    $enviar = filter_input(INPUT_POST, 'enviar');

    if (filter_input_array(INPUT_POST))
    {
        if (isset($enviar))
        {
            /// Guardar imagen en el servidor
            /// Solo de manera temporal.
            copy($_FILES['image']['tmp_name'], "temp/temp_img.png");

            /// Obtener la ruta absoluta de la imagen, 
            //aqui denuevo otro filtro
            $temp_img_route = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT')."/ImagenesDB/temp/temp_img.png";

            /// Crear sentencia para
            /// subir la imagen a la DB
            $sql = "INSERT INTO `images` (`imagen`, `type`) VALUES (LOAD_FILE('".$temp_img_route."'), '".$_FILES['image']['type']."')";

            /// Ejecutar sentencia SQL
            $sentencia = Conexion::getConexion()->prepare($sql);
            $insert = $sentencia->execute();

            /// Verificar si la imagen se a subido correctamente
            $aviso = ($insert) ? "Imagen Subida Exitosamente!" : "Algo salio mal, intentalo de nuevo";

            /// Imprimir resultado del aviso
            echo '<div class="w3-blue w3-padding"><p class="w3-margin-left">'.$aviso."</p></div>";

            /// Eliminar imagen temporal del servidor
            unlink("temp/temp_img.png");
        }
    }
?>
<!DOCTYPE html>
<html>
    <style>
        img {
            width: 100%;
            height: 480px;
            padding: 16px;
        }
    </style>
    <head>
        <title>ImagesDB</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/w3.css">
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <!-- Sidebar -->
        <div class="w3-sidebar w3-bar-block w3-border-right" style="display:none; width: 300px;" id="mySidebar">
            <button onclick="w3_close()" class="w3-bar-item w3-large" style="cursor: pointer;">Cerrar &times;</button>
            <!-- Formulario -->
            <div class="w3-container">
                <form class="" method="POST" enctype="multipart/form-data">
                    <p>
                        <label for="image">Subir Imagen</label>
                        <input class="w3-input w3-border" style="overflow: hidden;" type="file" name="image" id="image" accept="image/*" alt="" required=""/>
                    </p>
                    <p>
                        <input class="w3-input" type="submit" name="enviar" id="enviar" value="Enviar"/>
                    </p>
                </form>
            </div>
        </div>
        <!-- Navbar -->
        <div class="w3-bar w3-teal">
            <button class="w3-bar-item w3-btn w3-xlarge w3-round-large" style="margin-top: 8px;" onclick="w3_open()">☰</button>
            <h4 class="w3-bar-item ">Imagenes DB</h4>
        </div>
        <!--Imagenes-->
        <div class="w3-row">
            <?php
            foreach (Conexion::getConexion()->query("SELECT * FROM images") as $row) {
                echo '<div class="w3-third"><div class="w3-card"><img src="data:'.$row['type'].';charset=utf8;base64,'
                . base64_encode($row['imagen']) . '" class="w3-animate-bottom" alt="..." /></div></div>';
            }
            ?>
        </div>
    </body>
    <script type="text/javascript" src="js/code.js"></script>
</html>
<?php

Conexion::cerrarConexion();

