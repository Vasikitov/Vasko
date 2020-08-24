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
				<link rel="stylesheet" type="text/css" href="'.$estilo['Fichero'].'/'.strtolower($estilo['Nombre']).'_ver_album.css" title="'.$estilo['Nombre'].'">
			';
		}else{
			// Por defecto mostramos el estilo Responsive
			echo '
				<link rel="stylesheet" type="text/css" href="./Responsive_css/responsive_ver_album.css" title="Responsive">
			';
		}
		
	 ?>
	
	<title>Ver album</title>
</head>
<?php 
	if(isset($_COOKIE['usuario_cookie']) || isset($_SESSION['username_session'])){
		
		$select_album = "SELECT Titulo from albumes where IdAlbum ='".$_GET['album']."' ";
			

			$link = mysqli_connect('localhost', 'root', '', 'pibd');
			
			// Cambiamos la codificacion para que no surgan problemas con los acentos.
			mysqli_set_charset($link, 'utf8');
			// Recogemos el resultado
			$resultado_album = mysqli_query($link, $select_album);
			// Solo va ser un album el seleccionado, asi que no hará falta iterar.
			$album = mysqli_fetch_assoc($resultado_album);

			echo '
			<section>
				<div id="link">
					<a href="mis_albumes.php">Volver a Mis albumes</a>
				</div>
				

				<h2>Album elegido: &nbsp;&nbsp;<em style="border-bottom:2px solid red;">'.$album['Titulo'].'</em></h2>
			';


			// Cambiamos la codificacion para que no surgan problemas con los acentos.
			mysqli_set_charset($link, 'utf8');
			// Solo va ser un album
		 	$resultado_album = mysqli_query($link, $select_album);
			$album = mysqli_fetch_assoc($resultado_album);


		 	$sql_max = "SELECT MAX(Fecha) as FechaMax from fotos where Album = '".$_GET['album']."'";
		 	$sql_min = "SELECT MIN(Fecha) as FechaMin from fotos where Album = '".$_GET['album']."'";
		 	
			// Cambiamos la codificacion para que no surgan problemas con los acentos.
			mysqli_set_charset($link, 'utf8');
			// Recogemos el resultado
			$resultado_max = mysqli_query($link, $sql_max);
			$resultado_min = mysqli_query($link, $sql_min);

			// Fechas max y min de la tabla fotos.
			$max = mysqli_fetch_assoc($resultado_max);
			$min = mysqli_fetch_assoc($resultado_min);
		 	echo'
					<h2>Intervalo de fotos:   <em>'.$max['FechaMax'].'  <------------->  '.$min['FechaMin'].'</em></h2>
		 	';	

		 echo	'<div class="section_container">';
		 
			// Tenemos que buscar el id que le pertenece al titulo del album seleccionado y luego
			// cruzar ese id con la tabla fotos por el id de Album
			$select_foto = "SELECT IdFoto, Titulo, Fecha, Pais, Fichero, Album from fotos order by Fecha DESC";

			// Cambiamos la codificacion para que no surgan problemas con los acentos.
			mysqli_set_charset($link, 'utf8');
			// Recogemos el resultado
			$resultado_foto = mysqli_query($link, $select_foto);

			while($foto = mysqli_fetch_assoc($resultado_foto)){
				// Hacemos otro select para el pais.
				$select_pais = "SELECT NomPais from paises where IdPais = '".$foto['Pais']."'";
				$resultado_pais = mysqli_query($link, $select_pais);
				$pais = mysqli_fetch_assoc($resultado_pais);
				if($foto['Album'] == $_GET['album']){

					echo'
						<article>
							<h2><a href="detalle_foto.php?foto='.$foto['IdFoto'].'">'.$foto['Titulo'].'</a></h2>
							<figure>
								<a href="detalle_foto.php?foto='.$foto['IdFoto'].'"><img src="'.$foto['Fichero'].'" alt="" width="500" height="400"></a>
							</figure>
							<div>
								<p style="height:20px;margin:0;">Fecha: <time datetime="2018-09-21 ">'.$foto['Fecha'].' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;País: '.$pais['NomPais'].'</time></p>
							</div>
						</article>	
				';

				}
			}
		
		echo '
		</div>
</section>	
		'; 
	
	}else{
		header('Location:index.php?usuario_registrado_authorizationError');
	}

 ?>

	
	
	
<?php
	require_once("footer.inc");
?>