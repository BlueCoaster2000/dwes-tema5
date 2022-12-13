<?php
/*********************************************************************************************************************
 * Este script realiza el registro del usuario vía el POST del formulario que hay debajo, en la vista.
 * 
 * Cuando llegue POST hay que validarlo y si todo fue bien insertar en la base de datos el usuario.
 * 
 * Requisitos del POST:
 * - El nombre de usuario no tiene que estar vacío y NO PUEDE EXISTIR UN USUARIO CON ESE NOMBRE EN LA BASE DE DATOS.
 * - La contraseña tiene que ser, al menos, de 8 caracteres.
 * - Las contraseñas tiene que coincidir.
 * 
 * La contraseña la tienes que guardar en la base de datos cifrada mediante el algoritmo BCRYPT.
 * 
 * UN USUARIO LOGEADO NO PUEDE ACCEDER A ESTE SCRIPT.
 */

require 'gestionUsuarios.php';


session_start();
//Si el usuario está logeado no lo dejamos entrar.

if (isset( $_SESSION['usuario'] ) ){
    header('location: index.php');
    exit();
}

//Errores y Booleano para saber si todo ha salido bien
$errorContra="";
$errorNom="";
$registroOk=false;
if($_POST){
    if( isset( $_POST['nombre'] ) && isset( $_POST['clave'] ) && isset( $_POST['repite_clave'] )){
        $nombreValidado = htmlspecialchars( trim( $_POST['nombre'] ) );
        $claveValidado = htmlspecialchars( trim( $_POST['clave'] ) );
        $repite_claveValidado = htmlspecialchars( trim( $_POST['repite_clave'] ) );

        //Validar el usuario
        $enUso = usuarioEnUso($nombreValidado);
        if( !empty($enUso) || mb_strlen($nombreValidado)<0  ){
            $errorNom = "<p>ERROR: nombre no válido</p>";
            var_dump($enUso);
        }else{
                $nombre = $nombreValidado;
                //Validamos la contraseña
                if($claveValidado  == $repite_claveValidado ){
                    
                    
                    if($claveValidado < 8){
                        
                        $errorContra = "<p>Error la contraseña tiene que contener al menos 8 carácteres</p>";
                    }else{
                        $contra = password_hash($claveValidado,PASSWORD_BCRYPT);
                        $errorContra = "";
                        $fin = insertarUsuario($nombreValidado,$contra);
                        if(empty($fin)){
                            $registroOk = true;
                            
                        }else{
                            $registroOk = false;
                            $errorNom = "<h1>Error al insertar el usuario</h1>";
                        }

                    }
                    
                }else{
                    $errorContra = "<p>Error las contraseñas no son iguales</p>";
                    
                }

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
 * - TODO: los errores que se produzcan tienen que aparecer debajo de los campos.
 * - TODO: cuando hay errores en el formulario se debe mantener el valor del nombre de usuario en el campo
 *         correspondiente.
 */

if (!isset( $_SESSION['usuario']) ) {
    echo <<<END
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="filter.php">Filtrar imágenes</a></li>
            <li><a href="login.php">Iniciar Sesión</a></li>
            <li><strong>Regístrate</strong></li>
        </ul>
    END;
} else {
    return <<<END
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="add.php">Añadir imagen</a></li>
            <li><a href="filter.php">Filtrar imágenes</a></li>
            <li><a href="logout.php">Cerrar sesión ({$_SESSION['usuario']})</a></li>
        </ul>
    END;
}
$nombre = isset($nombreValidado) ? $nombreValidado : "";
if($registroOk==false){
    echo <<<END
        <h1>Regístrate</h1>

        <form action="signup.php" method="post">
            <p>
                <label for="nombre">Nombre de usuario</label>
                <input type="text" name="nombre" id="nombre" value="$nombre">
                 $errorNom 
            </p>
            <p>
                <label for="clave">Contraseña</label>
                <input type="password" name="clave" id="clave">
            </p>
            <p>
                <label for="repite_clave">Repite la contraseña</label>
                <input type="password" name="repite_clave" id="repite_clave">
            </p>
                $errorContra

            
            <p>
                <input type="submit" value="Regístrate">
            </p>
        </form>

    END;
}else{
    echo <<<END
    <h1>Usuario registrado Correctamente</h1>
        <a href="index.php">Home</a>
    END;
}

?>




