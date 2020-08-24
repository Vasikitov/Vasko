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
				<link rel="stylesheet" type="text/css" href="'.$estilo['Fichero'].'/'.strtolower($estilo['Nombre']).'_configurar.css" title="'.$estilo['Nombre'].'">
			';
		}else{
			// Por defecto mostramos el estilo Responsive
			echo '
				<link rel="stylesheet" type="text/css" href="./Responsive_css/responsive_configurar.css" title="Responsive">
			';
		}
		
	 ?>

	<title>Estilos</title>
</head>
<main>
	<?php 

		if(isset($_SESSION['username_session']) || isset($_COOKIE['usuario_cookie'])){
			echo'
				<h3>Configuración de estilos</h3>
				<form action="usuario_registrado.php?estiloenviao" method="post" ">
					<div>
						<label>
							Estilos a elegir: 
							<select name="estilo" id="estilo">';
								 
									// Hacemos una select al servidor y usaremos los paises que nos devuelva para ponerlos en el select
									$select = "SELECT * from estilos";
									// Al no estar el usuario logueado, usamos los usuarios globales creados en la base de datos por defecto.
									$link = mysqli_connect('localhost', 'root', '', 'pibd');
									// Cambiamos la codificacion para que no surgan problemas con los acentos.
									mysqli_set_charset($link, 'utf8');
									// Recogemos el resultado
									$resultado = mysqli_query($link, $select);
									// Mostramos a continuación los países en el select
									while($fila = mysqli_fetch_array($resultado)){
										echo'
											<option value="'.$fila['Nombre'].'">'.$fila['Nombre'].'</option>
										';
									}
								
								 
			echo'				</select>
						</label>
					</div>
					<div class="button">
						<input type="submit" name="acept" value="Aceptar">
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