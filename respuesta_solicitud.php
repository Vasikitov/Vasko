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
				<link rel="stylesheet" type="text/css" href="'.$estilo['Fichero'].'/'.strtolower($estilo['Nombre']).'_respuesta_solicitud.css" title="'.$estilo['Nombre'].'">
			';
		}else{
			// Por defecto mostramos el estilo Responsive
			echo '
				<link rel="stylesheet" type="text/css" href="./Responsive_css/responsive_respuesta_solicitud.css" title="Responsive">
			';
		}
		
	 ?>
	
	<title>Repuesta solicitud</title>
</head>
<main>
	<section>
		<?php 
		// Comprobamos si se ha creado la solicitud 
			if(isset($_POST['nombre_solicitante'])){
				echo '<p class = "success">La solicitud para crear el álbum <b>'.$_POST['titulo_album'].'</b> se ha generado corretamente</p>';	
			}
		 ?>
		<article>
			<?php
				$idUs;
				$select = "SELECT IdAlbum from albumes";
	        	// Al no estar el usuario logueado, usamos los usuarios globales creados en la base de datos por defecto.
				$link = mysqli_connect('localhost', 'root', '', 'pibd');
				// Cambiamos la codificacion para que no surgan problemas con los acentos.
				mysqli_set_charset($link, 'utf8');
				// Recogemos el resultado
				$resultado = mysqli_query($link, $select);
				// Mostramos a continuación los países en el select
				echo'<div id="link_album">';
				
				$select = "SELECT IdUsuario from usuarios where NomUsuario = '".$usuario."'";
	        	// Al no estar el usuario logueado, usamos los usuarios globales creados en la base de datos por defecto.
				$link = mysqli_connect('localhost', 'root', '', 'pibd');
				// Cambiamos la codificacion para que no surgan problemas con los acentos.
				mysqli_set_charset($link, 'utf8');
				// Recogemos el resultado
				$resultado = mysqli_query($link, $select);
				// Mostramos a continuación los países en el select
				echo'<div id="link_album">';
				while($filas = mysqli_fetch_assoc($resultado)){
					$idUs=$filas['IdUsuario'];
				}
				$insert = "INSERT INTO albumes (Titulo, Descripcion, Usuario, Foto) VALUES ('".$_POST['titulo_album']."', '".$_POST['texto_adicional']."', '".$idUs."','./Images/Album_1.jpg')";
	        	// Al no estar el usuario logueado, usamos los usuarios globales creados en la base de datos por defecto.
				$link = mysqli_connect('localhost', 'root', '', 'pibd');
				// Cambiamos la codificacion para que no surgan problemas con los acentos.
				mysqli_set_charset($link, 'utf8');
				// Recogemos el resultado
				$resultado = mysqli_query($link, $insert);
				echo '<h2>'.$_POST['titulo_album'].'</h2> 
					<figure>
						<img src="./Images/album.jpg" alt="" height="400" width="400">
					</figure>

					<div>
						<p><b>Nombre del solicitante:</b> '.$_POST['nombre_solicitante'].'</p>
						<p><b>Título del álbum:</b> '.$_POST['titulo_album'].'</p>
		                <p><b>Texto adicional:</b> '.$_POST['texto_adicional'].'</p>
		                <p><b>Correo electrónico:</b> '.$_POST['email'].'</p>
		                <p><b>Dirección:</b> '.$_POST['calle'].'</p>
		                <p><b>Localidad:</b> '.$_POST['localidad'].'</p>
		                <p><b>Provincia:</b> '.$_POST['provincia'].'</p>
		                <p><b>País:</b> '.$_POST['pais'].'</p>
		                <p><b>Color de la portada:</b><div style="background-color:'.$_POST['color_portada'].';width:5%;margin:auto;border:1px solid;">&nbsp;</div></p>
		                <p><b>Número de copias:</b> '.$_POST['numero_copias'].'</p>
		                <p><b>Resolución de las fotos:</b> '.$_POST['rango'].' DPI.</p>
		                <p><b>Álbum original:</b> '.$_POST['album_portal'].'</p>
		                <p><b>Fecha recepción &aacute;lbum:</b> <time datetime="2018-09-27 00:00">'.$_POST['recepcion_album'].'</time></p>
		                <p><b>Impresión:</b> '.$_POST['c'].'</p>';

		                // Antes de enseñar la cuota a pagar, vamos a calcularla
		                // Nos hace falta NumPaginas,Resolucion,ColorPortada
		                $cuota_num_copias = $_POST['numero_copias'] * 0.30;
		                // La inicializamos y la ponemos en el primero caso directamente
		                $cuota_resolucion = $_POST['numero_copias'] * 0.70;
		                if($_POST['rango'] == 300){
		                	$cuota_resolucion = $_POST['numero_copias'] * 1.55;
		                }elseif($_POST['rango'] == 450){
		                	$cuota_resolucion = $_POST['numero_copias'] * 2.20;
		                }elseif ($_POST['rango'] == 600) {
		                	$cuota_resolucion = $_POST['numero_copias'] * 3.25;
		                }elseif($_POST['rango'] == 750){
		                	$cuota_resolucion = $_POST['numero_copias'] * 4.30;
		                }elseif($_POST['rango'] == 900){
		                	$cuota_resolucion = $_POST['numero_copias'] * 5.35;
		                }
		                // La inicializamos y la ponemos en el primer caso directamente
		                $cuota_impresion = $_POST['numero_copias'] * 4.75;
		                if($_POST['c'] == "Color"){
		                	$cuota_impresion = $_POST['numero_copias'] * 9.99;
		                }
		                $cuota_total = $cuota_num_copias + $cuota_resolucion + $cuota_impresion; 
		               echo '<p><b>Cuota a pagar:</b> '.$cuota_total.' €</p>
					</div>';
			 ?>
		</article>
		<br><br><br>
	</section>

	<form action="usuario_registrado.php" method="post">
		 <input type="submit" value="Volver al perfil">
		<br><br><br>
	</form>
	
</main>

<?php
	require_once("footer.inc");
?>