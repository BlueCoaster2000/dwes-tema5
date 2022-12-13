<?php
/*********************************************************************************************************************
 * Este script muestra un formulario a través del cual se pueden buscar imágenes por el nombre y mostrarlas. Utiliza
 * el operador LIKE de SQL para buscar en el nombre de la imagen lo que llegue por $_GET['nombre'].
 * 
 * Evidentemente, tienes que controlar si viene o no por GET el valor a buscar. Si no viene nada, muestra el formulario
 * de búsqueda. Si viene en el GET el valor a buscar (en $_GET['nombre']) entonces hay que preparar y ejecutar una 
 * sentencia SQL.
 * 
 * El valor a buscar se tiene que mantener en el formulario.
 */
function filtra(string $texto):array
{

    //Conectamos a MariaDB
    $mysqli = new mysqli("db", "dwes", "dwes", "dwes", 3306);

    if($mysqli->errno){
        echo "No hay conexión con la base de datos";
        return [];
    }
    //Preparamos la consulta
    $sentencia = $mysqli->prepare("select i.id id, i.nombre nombre, i.ruta ruta, u.nombre usuario from imagen i, usuario u where i.usuario=u.id and i.nombre like ?");
    
    if (!$sentencia){
        echo "Error : ".$mysqli->error;
        $mysqli->close();
        return [];
    }
    
    
    //Vinculamos (bind)
    $valor = "%".$texto."%";
    $vinculo = $sentencia->bind_param("s",$valor);
    
    if (!$vinculo){
        echo "Error al vincular : ".$mysqli->error;
        $sentencia->close();
        $mysqli->close();
        return [];
    }
    
    $ejecucion = $sentencia->execute();
    if (!$ejecucion){
        echo "Error al vincular : ".$mysqli->error;
        $sentencia->close();
        $mysqli->close();
        return [];
    }
    
    
    //Recuperamos las filas obtenidas como resultados 
    
    $resultado = $sentencia->get_result();
    if (!$resultado){
        echo "Error al obtener el resultado : ".$mysqli->error;
        $sentencia->close();
        $mysqli->close();
        return [];
    }
    if ($resultado->num_rows == 0) {
        echo "<h2>No hay imágenes.</h2>";
    } else {
        echo "<h2>Imágenes totales: $resultado->num_rows</h2>";
    }
    
    while (($fila = $resultado->fetch_assoc()) != null) {
        echo <<<END
            <figure>
                <div>{$fila['nombre']} (subida por {$fila['usuario']})</div>
                <img src="{$fila['ruta']}" width="200px">
                <a href="delete.php?id={$fila['id']}">Borrar</a>
            </figure>
        END;
    }
    $resultadoDeLaBusqueda = [];
    while( ($fila = $resultado->fetch_assoc() ) != null ){
        $resultadoDeLaBusqueda[] = $fila;
    }

    return $resultadoDeLaBusqueda;


}
session_start();
/**********************************************************************************************************************
 * Lógica del programa
 * 
 * Tareas a realizar:
 * - TODO: tienes que realizar toda la lógica de este script
 */
$posts = [];
//If para determinar el valor de Texto
    if($_GET && isset($_GET['nombre'])){
        $texto = htmlspecialchars(trim( $_GET['nombre'] ) ); 
    }else{
        $texto = "";
    }

    if (mb_strlen($texto) > 0){
        $posts = filtra($texto);
    }

    
?>

<?php
/*********************************************************************************************************************
 * Salida HTML
 * 
 * Tareas a realizar:
 * - TODO: completa el código de la vista añadiendo el menú de navegación.
 * - TODO: en el formulario falta añadir el nombre que se puso cuando se envió el formulario.
 * - TODO: debajo del formulario tienen que aparecer las imágenes que se han encontrado en la base de datos.
 */
if (!isset( $_SESSION['usuario'] ) ) {
    echo <<<END
        <ul>
            <li><a href = index.php>Home</a></li>
            <li><a href="filter.php">Filtrar imágenes</a></li>
            <li><a href="signup.php">Regístrate</a></li>
            <li><a href="login.php">Iniciar Sesión</a></li>
        </ul>
    END;
} else {
    echo <<<END
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="add.php">Añadir imagen</a></li>
            <li><strong>Filtrar imágenes</stong></li>
            <li><a href="logout.php">Cerrar sesión ({$_SESSION['usuario']})</a></li>
        </ul>
    END;
}

?>
<h1>Galería de imágenes</h1>

<h2>Busca imágenes por filtro</h2>

<form method="get">
    <p>
        <label for="nombre">Busca por nombre</label>
    </p>
    <p>
        <input type="text" id="nombre" name="nombre">
    </p>
    <p>
        <input type="submit" value="Buscar">
    </p>
</form>
