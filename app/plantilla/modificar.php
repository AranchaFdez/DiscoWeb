<?php

// Guardo la salida en un buffer(en memoria)
// No se envia al navegador
ob_start();
// FORMULARIO DE ALTA DE USUARIOS
?>
<div id='aviso'><b><?= (isset($msg))?$msg:"" ?></b></div>
<form name='MODIFICAR' method="POST" action="index.php?orden=Modificar">
    <h1>Modificar Usuario  </h1>
    <label>Identificador</label><input type="text" name="id" value="<?=(isset($_POST['id']))?$_POST['id']:$user?>" readonly ><br>
    <label>Nombre</label><input type="text" name="user"  value="<?=(isset($_POST['user']))?$_POST['user']:$_SESSION['tusuarios'][$_GET['id']][1]?>"><br>
    <label>Correo electronico</label><input type="email" name="correo"   value="<?=(isset($_POST['correo']))?$_POST['correo']:$_SESSION['tusuarios'][$_GET['id']][2]?>"><br>
    <label>Contraseña</label><input type="password" name="clave"  value="<?=(isset($_POST['clave']))?$_POST['clave']:$_SESSION['tusuarios'][$_GET['id']][0]?>"><br>
 <?php
 if ( $tipouser == "Máster"){
     ?>
    <label>Estado </label><select name="estado"  size="3" >
      <option value="A" <?= ($estado=="A")?"selected= \"selected\"":""; ?>>Activo</option> 
      <option value="B" <?= ($estado=="B")?"selected= \"selected\"":""; ?>>Bloqueado</option>
      <option value="I" <?= ($estado=="I")?"selected= \"selected\"":""; ?>>Desactivado</option>
	</select><br>
<?php }	?>
    <label>Plan </label><select name="plan"  size="3">
      <option value="0" <?= ($plan==0)?"selected= \"selected\"":""; ?>>Basico</option> 
      <option value="1"   <?= ($plan==1)?"selected= \"selected\"":""; ?>>Profesional</option>
      <option value="2"  <?= ($plan==2)?"selected= \"selected\"":""; ?> >Premium</option>
   <?php     if ( $tipouser == "Máster"){
       ?>
      <option value="3" <?= ($plan==3)?"selected= \"selected\"":""; ?>>Master</option>
      <?php }	?>
	</select><br>
    
    <input type="submit" name="modificar" value="Modificar">
    <input type="submit" name="cancelar" value="Cancelar">	
</form>
<?php 
// Vacio el bufer y lo copio a contenido
// Para que se muestre en div de contenido
$contenido = ob_get_clean();
include_once "principal.php";

?>
