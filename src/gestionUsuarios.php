<?php
//Función para dar de alta a un nuevo usuario
function insertarUsuario(string $nombre, string $clave):array
{
    $mysqli = new mysqli("db", "dwes", "dwes", "dwes", 3306);
    if ($mysqli->connect_errno){
        echo "No ha sido posible conectarse a la base de datos";
        exit();
    }
    $sentencia = $mysqli->prepare("insert into usuario (nombre,clave) values (?,?)");
    if (!$sentencia){
        echo "Error : ".$mysqli->error;
        $mysqli->close();
        return [];
    }
    $valor = $nombre;
    $valor2 = $clave;
    $vinculo = $sentencia->bind_param("ss",$valor,$valor2);
    
if (!$vinculo){
    echo "Error al vincular : ".$mysqli->error;
    $sentencia->close();
    $mysqli->close();
    return [];
}
$ejecucion = $sentencia->execute();
if (!$ejecucion){
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

return $resultadoDeLaBusqueda;
}

//Función para determinar si un usuario está en uso
function usuarioEnUso(string $nombre):array
{
    $mysqli = new mysqli("db", "dwes", "dwes", "dwes", 3306);
    if ($mysqli->connect_errno){
        echo "No ha sido posible conectarse a la base de datos";
        exit();
    }
    $sentencia = $mysqli->prepare("select id from usuario where nombre like ?");
    if (!$sentencia){
        echo "Error : ".$mysqli->error;
        $mysqli->close();
        return [];
    }
    $valor = $nombre;
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

//Función para determinar comprobar los login de los usuarios
function obtenerUsuario( string $nombre):array
{
    {
        $mysqli = new mysqli("db", "dwes", "dwes", "dwes", 3306);
        if ($mysqli->connect_errno){
            echo "No ha sido posible conectarse a la base de datos";
            exit();
        }
        $sentencia = $mysqli->prepare("select clave from usuario where nombre = ? ");
        if (!$sentencia){
            echo "Error : ".$mysqli->error;
            $mysqli->close();
            return [];
        }
        $valor2 = $nombre;
        $vinculo = $sentencia->bind_param("s",$valor2);
        
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
}


 //Función para insertar la ruta de la imagen
 function insertarImagen(string $nombre, string $ruta,string $usuario):array
 {
     $mysqli = new mysqli("db", "dwes", "dwes", "dwes", 3306);
     if ($mysqli->connect_errno){
         echo "No ha sido posible conectarse a la base de datos";
         exit();
     }
     $sentencia = $mysqli->prepare("insert into imagen (nombre,ruta,usuario) values (?,?,?)");
     if (!$sentencia){
         echo "Error : ".$mysqli->error;
         $mysqli->close();
         return [];
     }
     $valor1 = $nombre;
     $valor2 = $ruta;
     $valor3 = $usuario;
     $vinculo = $sentencia->bind_param("sss", $valor1, $valor2, $valor3);
     
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
    
    return $resultadoDeLaBusqueda;
 }
?>