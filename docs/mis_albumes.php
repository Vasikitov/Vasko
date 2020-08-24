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
	
	<title>Mis albumes</title>
</head>

<?php 
	if(isset($_COOKIE['usuario_cookie']) || isset($_SESSION['username_session'])){

		$usuario = '';
		if(isset($_COOKIE['usuario_cookie'])){
			$usuario = $_COOKIE['usuario_cookie'];
		}else{
			$usuario = $_SESSION['username_session'];
		}

		echo'
			<section>
				<div id="link">
					<a href="usuario_registrado.php">Volver a Mi perfil</a>
				</div>	
				<h2>Mis álbumes</h2>

				<div class="album_flex">
		';
		$link = mysqli_connect('localhost', 'root', '', 'pibd');
		mysqli_set_charset($link, 'utf8');

		$sql_usu = "SELECT IdUsuario from usuarios where NomUsuario = '".$usuario."'";
		$resultado_usu = mysqli_query($link, $sql_usu);
		$idUsu = mysqli_fetch_assoc($resultado_usu); 

		$sql_album = "SELECT Titulo, Descripcion, Foto, IdAlbum from albumes  where Usuario = '".$idUsu['IdUsuario']."'";
			
			
			// Recogemos el resultado
			$resultado = mysqli_query($link, $sql_album);

			$entra = false;
			
			while($fila = mysqli_fetch_assoc($resultado)){
				$entra = true;
				// Hacemos otro select para el pais.
				echo'
						<article>
							<h3><a href="ver_album.php?album='.$fila['IdAlbum'].'">'.$fila['Titulo'].'</a></h3>
							<figure>
								<a href="ver_album.php?album='.$fila['IdAlbum'].'"><img src="'.$fila['Foto'].'" alt="" width="450" height="300"></a>
							</figure>
							<div>
								<h3>Descripción</h3>	
								<p>'.$fila['Descripcion'].'</p>
							</div>
						</article>	
				';
			}
			if(!$entra){
				echo '<p>No hay ningún álbum, por qué no <a href="crear_album.php" style="color:red;font-weight:bold;">insertas uno?</a></p>';
			}
			
			echo '</div>

			</section>';

	}else{
		header('Location:index.php?usuario_registrado_authorizationError');
	}
 ?>	
<?php
	require_once("footer.inc");
?>