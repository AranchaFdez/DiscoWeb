<?php
// Guardo la salida en un buffer(en memoria)
// No se envia al navegador
ob_start();
// FORMULARIO DE ALTA DE USUARIOS
?>
<div id='aviso'><b><?= (isset($msg))?$msg:"" ?></b></div>
    <h1>Detalles de <?= $user?></h1>
    	Nombre :<?= $nombre?><br>
     	Correo :<?= $correo?><br>
     	Plan :<?= $plan?><br>
     	Numero de ficheros :<?= 0?><br>
     	Espacio Ocupado :<?= 0?><br>
     	<br>  	
<form name='DETALLES' method="POST" action="index.php?orden=Detalles">	
    <input type="submit" name="atras"  value="Volver">	
</form>
<?php 

// Vacio el bufer y lo copio a contenido
// Para que se muestre en div de contenido
$contenido = ob_get_clean();
include_once "principal.php";

?>