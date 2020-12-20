<?php 
include_once 'config.php';
/* DATOS DE USUARIO
• Identificador ( 5 a 10 caracteres, no debe existir previamente, solo letras y números)

• Contraseña ( 8 a 15 caracteres, debe ser segura)

• Nombre ( Nombre y apellidos del usuario

• Correo electrónico ( Valor válido de dirección correo, no debe existir previamente)

• Tipo de Plan (0-Básico |1-Profesional |2- Premium| 3- Máster)
• Estado: (A-Activo | B-Bloqueado |I-Inactivo )
*/
// Inicializo el modelo 
// Cargo los datos del fichero a la session

function isId($user){
    foreach ($_SESSION['tusuarios'] as $id => $datos){
        if($id ==$user){
            return true;
            break;
        }
    }
    return false;
}
function comprobarId($user) {
     if(strlen($user)>=5 && strlen($user)<=10){
         if(preg_match('/[aA-zZ]/',$user) &&preg_match('/[0-10]/',$user)){
             return true;
            }
        }
    return false;
}
function comprobarPass($pass){
    if(strlen($pass)>=8 && strlen($pass)<=15){
        return true;
    }
    return false;
}
function isMail($correo,$user){
    foreach ($_SESSION['tusuarios'] as $id => $datos){
        if( $datos[2]==$correo){
            return true;
            break;
        }    
    }
    return false;
}
function comprobarMail($correo){
    if(filter_var($correo, FILTER_VALIDATE_EMAIL)==true){
        return true;
    }
    return false ;
}
function limpiarEntrada(string $entrada):string{
    $salida = trim($entrada); // Elimina espacios antes y despuÃ©s de los datos
    $salida = stripslashes($salida); // Elimina backslashes \
    $salida = htmlspecialchars($salida); // Traduce caracteres especiales en entidades HTML
    return $salida;
}
function limpiarArrayEntrada(array &$entrada){
    
    foreach ($entrada as $key => $value ) {
        $entrada[$key] = limpiarEntrada($value);
    }
}

function modeloUserInit(){
    
    
   /* $tusuarios = [ 
         "admin"  => ["12345"      ,"Administrado"   ,"admin@system.com"   ,3,"A"],
         "user01" => ["user01clave","Fernando Pérez" ,"user01@gmailio.com" ,0,"A"],
         "user02" => ["user02clave","Carmen García"  ,"user02@gmailio.com" ,1,"B"],
         "yes33" =>  ["micasa23"   ,"Jesica Rico"    ,"yes33@gmailio.com"  ,2,"I"]
        ];*/
    
    if (! isset ($_SESSION['tusuarios'] )){
    $datosjson = @file_get_contents(FILEUSER) or die("ERROR al abrir fichero de usuarios");
    $tusuarios = json_decode($datosjson, true);
    $_SESSION['tusuarios'] = $tusuarios;
   }     
}

// Comprueba usuario y contraseña (boolean)
function modeloOkUser($user,$clave){
    $correcto=false;
    if(isset($_SESSION['tusuarios'][$user])){
        $usedat=$_SESSION['tusuarios'][$user];
        $passUser=$usedat[0];
        $correcto=($clave==$passUser);
    }
    return $correcto && $usedat[3]==3;
}

// Devuelve el plan de usuario (String)
function modeloObtenerTipo($user){
    $tipouser=$_SESSION['tusuarios'][$user][3];
    return PLANES[$tipouser]; // Máster
}
/**
 * funcion llamada en controleuser ctlUserBorrar
 * borra el user pasado por parametro y refresca la pag redirigiendo a verUsuarios
 */
// Borrar un usuario (boolean)
function modeloUserDel($user){
   unset($_SESSION['tusuarios'][$user]); // Borrar el elemento
   header("Refresh:0; url=index.php?orden=VerUsuarios");
   
}
// Añadir un nuevo usuario (boolean)
function modeloUserAdd($userid,$userdat){
   $_SESSION['tusuarios'][$userid]=$userdat;
      modeloUserSave();  
}

// Actualizar un nuevo usuario (boolean)
function modeloUserUpdate ($userid,$userdat){
    
}

// Tabla de todos los usuarios para visualizar
function modeloUserGetAll (){
    // Genero lo datos para la vista que no muestra la contraseña ni los códigos de estado o plan
    // sino su traducción a texto
    $tuservista=[];
    foreach ($_SESSION['tusuarios'] as $clave => $datosusuario){
        $tuservista[$clave] = [$datosusuario[1],
                               $datosusuario[2],
                               PLANES[$datosusuario[3]],
                               ESTADOS[$datosusuario[4]]
                               ];
    }
    return $tuservista;
}
// Datos de un usuario para visualizar
function modeloUserGet ($user){
    
}

// Vuelca los datos al fichero
function modeloUserSave(){   
    $datosjon = json_encode($_SESSION['tusuarios']);
    file_put_contents(FILEUSER, $datosjon) or die ("Error al escribir en el fichero.");
    //fclose(FILEUSER);
}
