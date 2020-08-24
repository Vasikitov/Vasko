<?php
	require_once("cabecera.inc"); 
	require_once("navegacion.php");
?>

<head>
	<?php
		// Pondremos esto para que cada vez que se cargue la página que sea, que muestre el estilo
		// de cada usuario. 
		$usuario = null;	
		if(isset($_SESSION['username_session'])){
			$usuario = $_SESSION['username_session'];	
		}

		if(isset($_COOKIE['usuario_cookie'])){
			$usuario = $_COOKIE['usuario_cookie'];
		}

		$link =  mysqli_connect('localhost', 'root', '', 'pibd');
		$select = "SELECT Estilo from usuarios where NomUsuario = '".$usuario."' ";
		$resultado = mysqli_query($link,$select);

		if($usu_estilo = mysqli_fetch_assoc($resultado)){
			$select = "SELECT Fichero,Nombre from estilos where IdEstilo ='".$usu_estilo['Estilo']."' ";
			$resultado = mysqli_query($link,$select);
			$estilo = mysqli_fetch_assoc($resultado);
			echo '
				<link rel="stylesheet" type="text/css" href="'.$estilo['Fichero'].'/'.strtolower($estilo['Nombre']).'_usuario_registrado.css" title="'.$estilo['Nombre'].'">
			';
		}else{
			// Por defecto mostramos el estilo Responsive
			echo '
				<link rel="stylesheet" type="text/css" href="./Responsive_css/responsive_usuario_registrado.css" title="Responsive">
			';
		}
		
	 ?>
	
	<title>Crear Álbum</title>
</head>

<main>
	
	<?php 
		$ruta = './Images/Album_1.jpg';
		$usuario = null;

		if(isset($_SESSION['username_session'])){
			$usuario = $_SESSION['username_session'];	
		}

		if(isset($_COOKIE['usuario_cookie'])){
			$usuario = $_COOKIE['usuario_cookie'];
		}
		
		$link =  mysqli_connect('localhost', 'root', '', 'pibd');
		// Cambiamos la codificacion para que no surgan problemas con los acentos.
		mysqli_set_charset($link, 'utf8');
		$sql_idUsu = "SELECT IdUsuario from usuarios where NomUsuario = '".$usuario."'";
		$resultado = mysqli_query($link, $sql_idUsu);
		$id_Usu = mysqli_fetch_assoc($resultado);
		$sql = "INSERT INTO albumes (Titulo, Descripcion, Usuario, Foto) VALUES ('".$_POST['title']."', '".$_POST['description']."', ".$id_Usu['IdUsuario']." , '".$ruta."')";
		if(mysqli_query($link, $sql)){
			header('Location:usuario_registrado.php?ar='.$_POST['title'].' ');
		}else{
			echo "Error: " . $sql . "<br>" . mysqli_error($link);
		}

		
	 ?>

</main>