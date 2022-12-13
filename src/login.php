<?php
require 'gestionUsuarios.php';

/**********************************************************************************************************************
 * Este programa, a través del formulario que tienes que hacer debajo, en el área de la vista, realiza el inicio de
 * sesión del usuario verificando que ese usuario con esa contraseña existe en la base de datos.
 * 
 * Para mantener iniciada la sesión dentrás que usar la $_SESSION de PHP.
 * 
 * En el formulario se deben indicar los errores ("Usuario y/o contraseña no válido") cuando corresponda.
 * 
 * Dicho formulario enviará los datos por POST.
 * 
 * Cuando el usuario se haya logeado correctamente y hayas iniciado la sesión, redirige al usuario a la
 * página principal.
 * 
 * UN USUARIO LOGEADO NO PUEDE ACCEDER A ESTE SCRIPT.
 */
session_start();
$mysqli = new mysqli("db", "dwes", "dwes", "dwes", 3306);

if($mysqli->errno){
    echo "No hay conexión con la base de datos";
    return [];
}
//Si el usuario está logeado no lo dejamos entrar.
if (isset( $_SESSION['usuario'] ) ){
    header('location:index.php');
    exit();
}

$errorLog = false;
if($_POST){
    if($_POST['nombre'] && $_POST['clave'] ){
        $nombreValido = htmlspecialchars(trim( $_POST['nombre'] ) );
        $claveValidado = htmlspecialchars(trim( $_POST['clave'] ) );
        $claveEncriptada = password_hash($claveValidado,PASSWORD_BCRYPT);
        $comprobarLogin = obtenerUsuario($nombreValido);
        
        $claveIgual = password_verify($claveValidado,$comprobarLogin[0]['clave']);
        if($claveIgual==true){
            // el usuario y la pwd son correctas
            header('location:index.php');
            //Por lo tanto creamos la sesión de dicho usuario
            $_SESSION['usuario'] = $nombreValido;
        } else {
            // Usuario incorrecto o no existe
            echo "<h1>ERROR: Usuario Incorrecto</h1>";
            $errorLog = true;
            
            }
    }
}






/**********************************************************************************************************************
 * Lógica del programa
 * 
 * Tareas a realizar:
 * - TODO: tienes que realizar toda la lógica de este script
 */

 
/*********************************************************************************************************************
 * Salida HTML
 * 
 * Tareas a realizar en la vista:
 * - TODO: añadir el menú.
 * - TODO: formulario con nombre de usuario y contraseña.
 */

    echo <<<END
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="filter.php">Filtrar imágenes</a></li>
            <li><a href="signup.php">Regístrate</a></li>
            <li><strong>Iniciar Sesión</strong></li>
        </ul>
    END;

    if($errorLog==true){

        echo <<<END
        <h1>Inicia sesión</h1>
        <form action="#" method="POST">
            <p>
                <label for="nombre"> Usuario:</label>
                <input type="text" name="nombre" id="nombre" value="$nombreValido">
            </p>
            <p>
                <label for="clave"> Contraseña:</label>
                <input type="password" name="clave" id="clave">
            </p>
            <p>
            </p>
            <input type="submit" value="Enviar">
        </form>
        END;
    }else{
        
        echo <<<END
        <h1>Inicia sesión</h1>
        <form action="#" method="POST">
            <p>
                <label for="nombre"> Usuario:</label>
                <input type="text" name="nombre" id="nombre">
            </p>
            <p>
                <label for="clave"> Contraseña:</label>
                <input type="password" name="clave" id="clave">
            </p>
            <p>
            </p>
            <input type="submit" value="Enviar">
        </form>
        END;
    }
?>

