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
				<link rel="stylesheet" type="text/css" href="'.$estilo['Fichero'].'/'.strtolower($estilo['Nombre']).'_registro.css" title="'.$estilo['Nombre'].'">
			';
		}else{
			// Por defecto mostramos el estilo Responsive
			echo '
				<link rel="stylesheet" type="text/css" href="./Responsive_css/responsive_registro.css" title="Responsive">
			';
		}
		
	 ?>
	
	<title>Crear Álbum</title>
</head>

<main>

	<?php 

		if(isset($_COOKIE['usuario_cookie']) || isset($_SESSION['username_session'])){

			echo'
				
				<h3>Crear Álbum</h3>

				<form action="respuesta_crear_album.php" method="post">
					<div class="container_registro">
						<div>
							<label>
								<b>Título</b>
					  			<br>
								<input type="text" name="title" placeholder="Título" required="required">
								<br><br>
							</label>
						
						</div>

						<div>
							<label>
								<b>Descripción</b>
					  			<br>
					  			<input type="textarea" name="description" placeholder="Descripción">
					  			<br><br>
							</label>
							
						</div>
					 
					 	<input type="submit" value="Crear">
					  <br><br>
					</div>
				</form>
			';	
		}else{
			header('Location:index.php?usuario_registrado_authorizationError');
		}
	 ?>	
</main>

<?php
	require_once("footer.inc");
?>