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
				<link rel="stylesheet" type="text/css" href="'.$estilo['Fichero'].'/'.strtolower($estilo['Nombre']).'_anyadir_foto_album.css" title="'.$estilo['Nombre'].'">
			';
		}else{
			// Por defecto mostramos el estilo Responsive
			echo '
				<link rel="stylesheet" type="text/css" href="./Responsive_css/responsive_anyadir_foto_album.css" title="Responsive">
			';
		}
		
	 ?>
	 
	<title>Añadir foto a álbum</title>
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
			<main>
				<h3>Añadir foto a Álbum</h3>';
				// Mostraremos todos los posibles mensajes de error en esta parte.
				if(isset($_GET['ea'])){
					echo '
						<div class="fail">
							<p>No puedes añadir una foto existente o añadir una foto sin tener ningún álbum disponible</p>
							<a href="crear_album.php">¿Por qué no pruebas a crear un álbum?</a>
						</div>
						
					';
				}


		echo'	<form action="respuesta_anyadir_foto_album.php" method="post" enctype="multipart/form-data">
					<div class="container_registro">
						<div>
							<label>
								<b>Título de la foto</b>
					  			<br>
								<input type="text" name="titulo_foto" placeholder="Título de la foto" required="required">
								<br><br>
							</label>
						
						</div>

						<div>
							<label>
								<b>Descripción</b>
					  			<br>
					  			<input type="text"  name="descripcion" placeholder="Descripción" required="required">
					  			<br><br>
							</label>
							
						</div>
					 
						<div>
							<label>
								<b>Fecha</b>
					  			<br>
					  			<input type="date" name="fecha_foto" required="required">
					  			<br><br>
							</label>
							
						</div>
			 
					  	<div>
					  		<label>
					  			<b>País</b>
					  			<br>
					  			<select name="pais" id="pais">';
					  				 
					  					// Hacemos una select al servidor y usaremos los paises que nos devuelva para ponerlos en el select
					  					$select = "SELECT * from paises order by NomPais ASC";
					  					// Al no estar el usuario logueado, usamos los usuarios globales creados en la base de datos por defecto.
					  					$link = mysqli_connect('localhost', 'root', '', 'pibd');
					  					// Cambiamos la codificacion para que no surgan problemas con los acentos.
										mysqli_set_charset($link, 'utf8');
										// Recogemos el resultado
										$resultado = mysqli_query($link, $select);
										// Mostramos a continuación los países en el select
										while($fila = mysqli_fetch_assoc($resultado)){
											echo'
												<option value="'.$fila['NomPais'].'">'.$fila['NomPais'].'</option>
											';
										}
										
					  				 
					echo '  			</select>


					  			<br><br>
					  		</label>
							
						</div>


						<div>
					  		<label>
					  			<b>Álbum</b>
					  			<br>
					  			<select name="album" id="pais">';
					  					 $link = mysqli_connect('localhost', 'root', '', 'pibd');					  		
										mysqli_set_charset($link, 'utf8');

										$sql_usu = "SELECT IdUsuario from usuarios where NomUsuario = '".$usuario."'";
										$resultado_usu = mysqli_query($link, $sql_usu);
										$idUsu = mysqli_fetch_assoc($resultado_usu);

					  					$sql_album = "SELECT * from albumes where Usuario = '".$idUsu['IdUsuario']."'";				  			
					  					
										// Recogemos el resultado
										$resultado_album = mysqli_query($link, $sql_album);
										// Mostramos a continuación los países en el select
										while($album = mysqli_fetch_assoc($resultado_album)){
											echo'
												<option value="'.$album['Titulo'].'">'.$album['Titulo'].'</option>
											';
										}
										
					  				 
					echo '  			</select>


					  			<br><br>
					  		</label>
							
						</div>

						<input type="file" name="file" id="file" value="Subir archivo">
						<label for="file">Cargar imagen</label>
						<br><br>
					 	<input type="submit" value="Añadir">
					  <br><br>
					</div>
				</form>
			</main>
		';

	}else{
		header('Location:index.php?usuario_registrado_authorizationError');
	}

 ?>
<?php
	require_once("footer.inc");
?>