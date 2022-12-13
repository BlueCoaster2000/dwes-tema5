<?php
/**********************************************************************************************************************
 * Este script simplemente elimina la imagen de la base de datos y de la carpeta <imagen>
 *
 * La información de la imagen a eliminar viene vía GET. Por GET se tiene que indicar el id de la imagen a eliminar
 * de la base de datos.
 * 
 * Busca en la documentación de PHP cómo borrar un fichero.
 * 
 * Si no existe ninguna imagen con el id indicado en el GET o no se ha inicado GET, este script redirigirá al usuario
 * a la página principal.
 * 
 * En otro caso seguirá la ejecución del script y mostrará la vista de debajo en la que se indica al usuario que
 * la imagen ha sido eliminada.
 */
function obtenerImg(string $id):array
{
    $mysqli = new mysqli("db", "dwes", "dwes", "dwes", 3306);
    if ($mysqli->connect_errno){
        echo "No ha sido posible conectarse a la base de datos";
        exit();
    }
    $sentencia = $mysqli->prepare("select nombre, ruta from imagen where id like ?");
    if (!$sentencia){
        echo "Error : ".$mysqli->error;
        $mysqli->close();
        return [];
    }
    $valor = $id;
    $vinculo = $sentencia->bind_param("s",$valor);
    
if (!$vinculo){
    echo "Error al vincular : ".$mysqli->error;
    $sentencia->close();
    $mysqli->close();
    return [];
}
$ejecucion = $sentencia->execute();
if (!$ejecucion){
    echo "Error al ejecutar : ".$mysqli->error;
    $sentencia->close();
    $mysqli->close();
    return [];
}
$resultado = $sentencia->get_result();
if (!$resultado){
    echo "Error al obtener el resultado : ".$mysqli->error;
    $sentencia->close();
    $mysqli->close();
    return [];
}
$resultadoDeLaBusqueda = [];
while( ($fila = $resultado->fetch_assoc() ) != null ){
    $resultadoDeLaBusqueda[] = $fila;
}

$mysqli->close();
return $resultadoDeLaBusqueda;
}
//Para borrar la imagen de la base de datos
function borrarImg(string $id):array
{
    $mysqli = new mysqli("db", "dwes", "dwes", "dwes", 3306);
    if ($mysqli->connect_errno){
        echo "No ha sido posible conectarse a la base de datos";
        exit();
    }
    $sentencia = $mysqli->prepare("delete from imagen where id like ?");
    if (!$sentencia){
        echo "Error : ".$mysqli->error;
        $mysqli->close();
        return [];
    }
    $valor = $id;
    $vinculo = $sentencia->bind_param("s",$valor);
    
if (!$vinculo){
    echo "Error al vincular : ".$mysqli->error;
    $sentencia->close();
    $mysqli->close();
    return [];
}
$ejecucion = $sentencia->execute();
if (!$ejecucion){
    echo "Error al ejecutar : ".$mysqli->error;
    $sentencia->close();
    $mysqli->close();
    return [];
}
$resultado = $sentencia->get_result();
if (!$resultado){
    
    $sentencia->close();
    $mysqli->close();
    return [];
}
$resultadoDeLaBusqueda = [];
while( ($fila = $resultado->fetch_assoc() ) != null ){
    $resultadoDeLaBusqueda[] = $fila;
}

$mysqli->close();
return $resultadoDeLaBusqueda;
}



session_start();

if(!isset($_SESSION['usuario'])){
    header('location:index.php');
}

if ( empty( $_GET ) ||!isset( $_GET['id'] ) ){
    header('location:index.php');
    
}else{

    $idValidado = htmlspecialchars( trim( $_GET['id'] ) );
    $imgExist = obtenerImg($idValidado);
    $rutaImg = $imgExist[0]['ruta'];
    if(empty($imgExist) ){
        echo "<h2>ERROR: No existe una imagen con ese ID</h2>";
    }else{
        borrarImg($idValidado);
        unlink($rutaImg);
    }

    
}
/**********************************************************************************************************************
 * Lógica del programa
 * 
 * Tareas a realizar:
 * - TODO: tienes que desarrollar toda la lógica de este script.
 */


/*********************************************************************************************************************
 * Salida HTML
 */

?>
<h1>Galería de imágenes</h1>

<p>Imagen eliminada correctamente</p>
<p>Vuelve a la <a href="index.php">página de inicio</a></p>
