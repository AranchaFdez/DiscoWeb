<?php
// ------------------------------------------------
// Controlador que realiza la gestión de usuarios
// ------------------------------------------------
include_once 'config.php';
include_once 'modeloUser.php';

/*
 * Inicio Muestra o procesa el formulario (POST)
 */

function  ctlUserInicio(){
    $msg = "";
    $user ="";
    $clave ="";
    if ( $_SERVER['REQUEST_METHOD'] == "POST"){
        if (isset($_POST['user']) && isset($_POST['clave'])){
            $user =$_POST['user'];
            $clave=$_POST['clave'];
            if ( modeloOkUser($user,$clave)){
                $_SESSION['user'] = $user;
                $_SESSION['tipouser'] = modeloObtenerTipo($user);
                if ( $_SESSION['tipouser'] == "Máster"){
                    $_SESSION['modo'] = GESTIONUSUARIOS;
                    header('Location:index.php?orden=VerUsuarios');
                }
                else {
                  // Usuario normal;
                  // PRIMERA VERSIÓN SOLO USUARIOS ADMISTRADORES
                  $msg="Error: Acceso solo permitido a usuarios Administradores.";
                  unset($_SESSION['user']);
                  // $_SESSION['modo'] = GESTIONFICHEROS;
                  // Cambio de modo y redireccion a verficheros
                }
            }
            else {
                $msg="Error: usuario y contraseña no válidos.";
           }  
        }
    }  
    include_once 'plantilla/facceso.php';
}

// Cierra la sesión y vuelva los datos
function ctlUserCerrar(){
    session_destroy();
    modeloUserSave();
    header('Location:index.php');
}

// Muestro la tabla con los usuario 
function ctlUserVerUsuarios (){
    // Obtengo los datos del modelo
    $usuarios = modeloUserGetAll(); 
    // Invoco la vista 
    include_once 'plantilla/verusuariosp.php';  
}
function ctlUserAlta(){
    $msg="";
    $error=false;
    if ( isset($_POST['alta'])){ 
            limpiarArrayEntrada($_POST);
        
            $userid=$_POST['id'];
            $pass=$_POST['clave'];
            $correo=$_POST['correo'];
            var_dump($_POST);
                if(isId($userid)){
                    $msg="El user existe";
                    $error=true;
                }
                if(!comprobarId($userid)){
                    $msg="El usuario debe contener 5 a 10 caracteres numericos y alfa";
                    $error=true;    
                }
                if(!comprobarPass($pass)){
                    $msg="La clave debe contener 8 a 15 caracteres";
                    $error=true;   
                }
                if(!($pass==$_POST['clave2'])){
                    $msg="Las contraseña no coinciden";
                    $error=true; 
                }
                if(isMail($correo,$userid)){
                    $msg="El correo ya existe";
                    $error=true;  
                }
                if(!comprobarMail($correo)){
                    $msg="Correo no válido";
                    $error=true;   
                }
                if(!$error){
                    $userdat=array ($_POST['clave'],$_POST['user'],$_POST['correo'],$_POST['plan'],"I");
                    modeloUserAdd($userid,$userdat);
                }
            }
    if(isset($_POST['cancelar'])){
        header("Refresh:0; url=index.php?orden=VerUsuarios");
    }  
    include_once 'plantilla/fnuevo.php';   
}
/**
 * declaramos las variables que usamos en la plantilla de modificar
 * si esta declarado el get almacenamos en estas varibles despues las actualizaremos
 * con las recogidas en post para poder mostrar las nuevas preferencias o cambios del usuario
 * insertamos directamente en la sesion los nuevos datos que el usuario quiere cambiar
 */
function ctlUserModificar(){
 $plan="";
 $estado="";
 $user="";
 $tipouser="";
 $msg="";
 
 $tipouser=$_SESSION['tipouser'];
 $error=false;
 if(isset($_GET['id'])){
     $user=$_GET['id'];   
     $plan= $_SESSION['tusuarios'][ $user][3];
     $estado= $_SESSION['tusuarios'][ $user][4];   
 }
 if ( isset($_POST['modificar'])){
     limpiarArrayEntrada($_POST); 
     
     $user=$_POST['id']; 
     $pass=$_POST['clave'];
     $correo=$_POST['correo'];
  
     if($_SESSION['tusuarios'][ $_POST['id']][2]!=$correo){
         if(isMail($correo,$user)){
             $msg="El correo ya existe";
             $error=true;
         }
         if(!comprobarMail($correo)){
             $msg="Correo no válido";
             $error=true;
         }
     }
     if(!comprobarPass($pass)){
         $msg="La clave debe contener 8 a 15 caracteres";
         $error=true;
     }
     if(!$error){
         $_SESSION['tusuarios'][$_POST['id']][0]=$_POST['clave'];
         $_SESSION['tusuarios'][$_POST['id']][1]=$_POST['user'];
         $_SESSION['tusuarios'][$_POST['id']][2]=$_POST['correo'];
     }
       if(isset($_POST['plan'])){
           $_SESSION['tusuarios'][$_POST['id']][3]=$_POST['plan'];
           $plan=$_POST['plan'];
               }
       if(isset($_POST['estado'])){
           $_SESSION['tusuarios'][$_POST['id']][4]=$_POST['estado'];
           $estado=$_POST['estado'];
         }                
}   
    if(isset($_POST['cancelar'])){
        header("Refresh:0; url=index.php?orden=VerUsuarios");
    }    
    include_once 'plantilla/modificar.php';    
}
/**
 * $user es el usuario elegido por el admin
 * llamamos a la funcion de modeloUser modeloUserDel para eleminar ese usuario
 */
function ctlUserBorrar(){
    if(isset($_GET['id'])){
        $user=$_GET['id'];
        modeloUserDel($user);
    }
}
/**
 * declaramos las varibles que usaremos en la plantilla detalles
 * si esta declarada get almacenamos los datos del usuario 
 * si el user selecciona atras redirige la pag
 */
function ctlUserDetalles(){
    $user ="";
    $nombre="";
    $correo="";
    $plan="";
    if(isset($_GET['id'])){
        $user=$_GET['id'];
        $nombre= $_SESSION['tusuarios'][$user][1];
        $correo= $_SESSION['tusuarios'][$user][2];
        $plan=PLANES[$_SESSION['tusuarios'][$user][3]];
    }
    if(isset($_POST["atras"])){
        header("Refresh:0; url=index.php?orden=VerUsuarios");
    }
    include_once 'plantilla/detalles.php';
    
}
function ctlUserRegistro(){      
    $msg="";
    $error=false;
    if ( isset($_POST['alta'])){
        limpiarArrayEntrada($_POST);
        
        $userid=$_POST['id'];
        $pass=$_POST['clave'];
        $correo=$_POST['correo'];
        var_dump($_POST);
        if(isId($userid)){
            $msg="El user existe";
            $error=true;
        }
        if(!comprobarId($userid)){
            $msg="El usuario debe contener 5 a 10 caracteres numericos y alfa";
            $error=true;
        }
        if(!comprobarPass($pass)){
            $msg="La clave debe contener 8 a 15 caracteres";
            $error=true;
        }
        if(!($pass==$_POST['clave2'])){
            $msg="Las contraseña no coinciden";
            $error=true;
        }
        if(isMail($correo,$userid)){
            $msg="El correo ya existe";
            $error=true;
        }
        if(!comprobarMail($correo)){
            $msg="Correo no válido";
            $error=true;
        }
        if(!$error){
            $userdat=array ($_POST['clave'],$_POST['user'],$_POST['correo'],$_POST['plan'],"I");
            modeloUserAdd($userid,$userdat);
        }
    }
   if(isset($_POST['cancelar'])){
         header("Refresh:0; url=index.php");
       }
       
       include_once 'plantilla/registrar.php';
}


    