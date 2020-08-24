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
				<link rel="stylesheet" type="text/css" href="'.$estilo['Fichero'].'/'.strtolower($estilo['Nombre']).'_respuesta_darme_baja.css" title="'.$estilo['Nombre'].'">
			';
		}else{
			// Por defecto mostramos el estilo Responsive
			echo '
				<link rel="stylesheet" type="text/css" href="./Responsive_css/responsive_respuesta_darme_baja.css" title="Responsive">
			';
		}
		
	 ?>
	
	<title>Darse de Baja</title>
</head>

<main>

	<?php 
		if(isset($_COOKIE['usuario_cookie']) || isset($_SESSION['username_session'])){
			$usuario = '';
			if(isset($_COOKIE['usuario_cookie'])){
				$usuario = $_SESSION['usuario_cookie'];
			}else{
				$usuario = $_SESSION['username_session'];
			}
			
			echo'
				
				<h3>Darse de baja</h3>
				<h3 style="font-size:40px;">Álbumes de '.$usuario.'</h3>
			';
			$link = mysqli_connect('localhost', 'root', '', 'pibd');	
			mysqli_set_charset($link, 'utf8');



			$sql_usuario = "SELECT IdUsuario from usuarios where NomUsuario = '".$usuario."'";
			$resultado = mysqli_query($link, $sql_usuario);
			$idUsu = mysqli_fetch_assoc($resultado);

			$sql_album = "SELECT Titulo, IdAlbum from albumes where Usuario = '".$idUsu['IdUsuario']."' ";
			$resultado_a = mysqli_query($link, $sql_album);

			$fotos_totales = 0;
			echo'<div id="link_album">';
			while($album = mysqli_fetch_assoc($resultado_a)){
				$sql_foto = "SELECT count(*) as NumFotos from fotos where Album = '".$album['IdAlbum']."'";
				$resultado = mysqli_query($link, $sql_foto);
			
				while($foto = mysqli_fetch_assoc($resultado)){
					$fotos_totales += $foto['NumFotos'];
					echo '
					
						<a href="mis_albumes.php"><em>-'.$album['Titulo'].'(Fotos '.$foto['NumFotos'].')</em></a>
					
					
					';
				}
			}
				echo'<h3 style="font-size:25px;">Total fotos: '.$fotos_totales.'</h3>';
			echo'<div id="link_album">';
			
			echo'

				 <form method="post">
					<div class="container_registro">
						<div>
							<label>
								<b>Contraseña para confirmar</b>
					  			<br>
								<input type="password" name="contra" placeholder="Contraseña">
								<br><br>
							</label>
						
						</div>
					 	<input type="submit" value="Enviar">
					  <br><br>
					</div>
				</form>
			';	

			if(isset($_POST['contra'])){
				if($_POST['contra']!=null){
					$sql_usu = "SELECT IdUsuario, Clave from usuarios where NomUsuario = '".$usuario."'";
					$resultado_usu = mysqli_query($link, $sql_usu);
					$idUsu = mysqli_fetch_assoc($resultado_usu);

					if($idUsu['Clave'] === $_POST['contra']){
						
						$sql_album = "SELECT IdAlbum from albumes where Usuario = '".$idUsu['IdUsuario']."'";
						$resultado_album = mysqli_query($link, $sql_album);
						// Iteramos por todos los albumes
						while($idAlbum = mysqli_fetch_assoc($resultado_album)){
							// Hacemos delete de las fotos
							$sql_delete_foto = "DELETE FROM fotos where Album = '".$idAlbum['IdAlbum']."'";
							mysqli_query($link, $sql_delete_foto);
							// Hacemos delete del album 
							$sql_delete_album = "DELETE FROM albumes where IdAlbum = '".$idAlbum['IdAlbum']."'";
							mysqli_query($link, $sql_delete_album);
						}

						// Hacemos delete del usuario finalmente
						$sql_delete_usu = "DELETE FROM usuarios where NomUsuario = '".$usuario."'";
						mysqli_query($link, $sql_delete_usu);
				 		header('Location:index.php?finalize_session');
				 		
					}
					else{
						echo '<p class = "error">La contraseña que has introducido es incorrecta</p>';
						
					}

				}else{
					echo '<p class = "error">Introduce una contraseña</p>';
				}
			}
			
		}else{
			header('Location:index.php?usuario_registrado_authorizationError');
		}

		echo '</div>';
	 ?>	
</main>

<?php
	require_once("footer.inc");
?>