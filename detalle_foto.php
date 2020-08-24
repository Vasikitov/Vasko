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
				<link rel="stylesheet" type="text/css" href="'.$estilo['Fichero'].'/'.strtolower($estilo['Nombre']).'_detalle_foto.css" title="'.$estilo['Nombre'].'">
			';
		}else{
			// Por defecto mostramos el estilo Responsive
			echo '
				<link rel="stylesheet" type="text/css" href="./Responsive_css/responsive_detalle_foto.css" title="Responsive">
			';
		}
		
	 ?>

	<title>Detalle de foto</title> 
</head>
<main>
	
	

	<?php 

		// Comprobamos si el usuario esta registrado o no
		if(isset($_COOKIE['usuario_cookie']) || isset($_SESSION['username_session'])){

			$select_foto = "SELECT Titulo, Fecha, Album, Descripcion, Fichero, Pais from fotos where IdFoto ='".$_GET['foto']."' ";

			$link = mysqli_connect('localhost', 'root', '', 'pibd');

			// Cambiamos la codificacion para que no surgan problemas con los acentos.
			mysqli_set_charset($link, 'utf8');
			// Recogemos el resultado
			$resultado_foto = mysqli_query($link, $select_foto);


			if($foto = mysqli_fetch_assoc($resultado_foto)){
				// Solo para saber a qué album volver 
				echo'
				<div>
					<div id="link">
						<a href="mis_albumes.php" >Volver a Mis albumes</a>
					</div>
				
					<div id="link">
						<a href="ver_album.php?album='.$foto['Album'].'">Volver a Ver Álbum</a>
					</div>	
				</div>	
				';

				// Hacemos otro select para el pais.
				$select_pais = "SELECT NomPais from paises where IdPais = '".$foto['Pais']."'";
				$resultado_pais = mysqli_query($link, $select_pais);
				$pais = mysqli_fetch_assoc($resultado_pais);

				// Hacemos select de album también
				$select_album = "SELECT IdAlbum, Titulo, Foto, Usuario from albumes where IdAlbum ='".$foto['Album']."'  ";
				$resultado_album = mysqli_query($link, $select_album);
				// Tenemos ya al album
				$album = mysqli_fetch_assoc($resultado_album);

				// Tenemos que hacer otra select, para saber de quien es el album y por tanto, la foto.
				$select_usuario = "SELECT IdUsuario, NomUsuario from usuarios where IdUsuario ='".$album['Usuario']."'  ";
				$resultado_usuario = mysqli_query($link, $select_usuario);
				$usuario = mysqli_fetch_assoc($resultado_usuario);

				echo	'<section>
					<div class="section_container">
						<div>
							<h2>'.$foto['Titulo'].'</h2>
							<figure>
								<img src="'.$foto['Fichero'].'" alt="" width="600" height="400">
							</figure>
						</div>

						<div id="datos_foto">
							<h2>Datos de: <em>'.$foto['Titulo'].'</em></h2>
							<p><b>Fecha:</b> <time datetime="2018-09-21 ">'.$foto['Fecha'].'</time></p>
							<p><b>Pais:</b> '.$pais['NomPais'].'</p>
			                <p><b>Álbum:</b> '.$album['Titulo'].'</p>
			                <p><b>Usuario:</b> '.$usuario['NomUsuario'].'</p>
			                <h2>Descripción</h2>
			                <p style="margin-left:10%;margin-right:10%;">'.$foto['Descripcion'].'</p>
						</div>
					</div>
				</section>';
			}

		}else{
				header('Location:index.php?detalle_foto_authorizationError');
		}
	 ?>
	 
</main>
<?php
	require_once("footer.inc");
?>