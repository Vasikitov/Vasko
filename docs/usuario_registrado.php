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
	
	<title>Usuario registrado</title>
</head>
<main>
<?php 
	$usuario = null;
	if(isset($_COOKIE['usuario_cookie'])){
		$usuario = $_COOKIE['usuario_cookie'];
	}else{
		$usuario = $_SESSION['username_session'];
	}	
		$select = "SELECT * FROM usuarios WHERE NomUsuario = '". $_SESSION['username_session']."'";
		$link = mysqli_connect('localhost', 'root', '', 'pibd');
		// Cambiamos la codificacion para que no surgan problemas con los acentos.
		mysqli_set_charset($link, 'utf8');
		$resultado = mysqli_query($link, $select);
		$fila = mysqli_fetch_assoc($resultado);
		// Hacemos otro select para el pais.
		$select_pais = "SELECT NomPais from paises where IdPais = '".$fila['Pais']."'";
		$resultado_pais = mysqli_query($link, $select_pais);
		$pais = mysqli_fetch_assoc($resultado_pais);

			if(isset($_GET['hi'])){
				echo'
					<h2>Buenos días <b style="color:red;font-size:60px;">'.$fila['NomUsuario'].'</b>, has accedido correctamente a Myst.</h2>
				';
			}

		echo'	<h2>Datos personales</h2>

			<div class="datos_perfil_container">
				<figure>
					<img src="'.$fila['Foto'].'" alt="" width="300" height="250">
				</figure>
			
			
				

						<ul>
							<li>Nombre: '.$fila['NomUsuario'].'</li>
							<li>Email: '.$fila['Email'].'</li>
							<li>País: '.$pais['NomPais'].'</li>
							<li>Ciudad: '.$fila['Ciudad'].'</li>
							';
							if($fila['Sexo'] == 0){
								echo'<li>Sexo: Hombre</li>';
							}else{
								echo'<li>Sexo: Mujer</li>';
							}
							
			echo			'<li>Fecha de nacimiento: '.$fila['FNacimiento'].'</li>
						</ul>
				
			 
				
			</div>';
			
			if(isset($_POST['estilo'])){
				// Primero usaremos la tabla estilos para sacar el id en concreto del estilo seleccionado, y luego lo cambiamos en la tabla usuarios
				$select_estilo = "SELECT IdEstilo from estilos where Nombre = '".$_POST['estilo']."' ";
				$resultado_estilo = mysqli_query($link,$select_estilo);
				// En la variable $estilo tenemos el IdEstilo
				$estilo = mysqli_fetch_assoc($resultado_estilo);

				$update_estilo_usuario = "UPDATE usuarios set Estilo = '".$estilo['IdEstilo']."' where NomUsuario = '".$usuario."' ";
			 	mysqli_query($link, $update_estilo_usuario);
			 	//Recargamos la página automáticamente, justo después de cambiar el estilo
			 	header('Location:usuario_registrado.php?estilo_mensaje='.$_POST['estilo'].' ');
			}

			// ---------------Para mostrar los mensajes--------
			if(isset($_POST['title'])){
				echo '<p class="success">El álbum <b>'.$_POST['title'].'</b> se ha creado correctamente</p>';
			}

			if(isset($_GET['estilo_mensaje'])){
				echo'<p class="success">El estilo se ha configurado al siguiente: <b>'.$_GET['estilo_mensaje'].'</b></p>';
			}

			if(isset($_GET['dm'])){
				echo'<p class="success">Tus datos de perfil han sido modificados</b></p>';
			}

			if(isset($_GET['ar'])){
				echo'<p class="success">El álbum <b>'.$_GET['ar'].'</b> ha sido insertado en la base de datos</b></p>';
				echo'<p class="success"><a class="success" href="anyadir_foto_album.php" style="border-bottom:2px solid red;">¿Por qué no insertas tu primera foto?</a></p>';
			}

	echo	'<div class="flex-container">
				<a href="editar_perfil.php">Editar perfil</a>
				<a href="respuesta_darme_baja.php">Darme de baja</a>
				<a href="crear_album.php">Crear álbum</a>
				<a href="solicitar_album.php">Solicitar álbum</a>
				<a href="mis_albumes.php">Mis albumes</a>
				<a href="anyadir_foto_album.php">Añadir foto a álbum</a>
				<a href="configurar.php">Configurar estilo</a>
			</div>';
			//Comprobamos que se haya creado al menos el titulo del album ya que lo pusimos required		
	
 ?>
</main>

<?php
	require_once("footer.inc");
?>