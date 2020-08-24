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
				<link rel="stylesheet" type="text/css" href="'.$estilo['Fichero'].'/'.strtolower($estilo['Nombre']).'_resultado_busqueda.css" title="'.$estilo['Nombre'].'">
			';
		}else{
			// Por defecto mostramos el estilo Responsive
			echo '
				<link rel="stylesheet" type="text/css" href="./Responsive_css/responsive_resultado_busqueda.css" title="Responsive">
			';
		}
		
	 ?>
	
	<title>Resultado búsqueda</title>
</head>
<main>
		<?php 

		echo	'<section>
				<h3>Resultado de búsquedas para: <em style="color:red;">Título("'.$_POST['titulo'].'") &nbsp;&nbsp; Fecha('.$_POST['fecha1'].'<-------->'.$_POST['fecha2'].') &nbsp;&nbsp; País("'.$_POST['pais'].'") &nbsp;&nbsp; Álbum("'.$_POST['album'].'")</em></h3>

				<div class="section_container">
					<article>
						<figure>';
								$tit = $_POST['titulo'];
								$fecha1 = $_POST['fecha1'];
								$fecha2 = $_POST['fecha2'];
								$pais = $_POST['pais'];
								$album = $_POST['album'];
								
								



								$select = "SELECT a.Titulo as aTitulo, f.Fichero, f.IdFoto, f.Fecha, f.Pais, f.Album, p.IdPais, p.NomPais, a.IdAlbum, f.Titulo from fotos f, paises p, albumes a where p.IdPais = f.Pais and f.Album = a.IdAlbum";

								if($tit != ""){
									$select .= " AND f.Titulo like '%".$tit."%' ";									
								}

								if($fecha1 != ""){
									$select .= " AND f.Fecha >= '".$fecha1."' ";
								} 

								if($fecha2 != ""){
									$select .= " AND f.Fecha <= '".$fecha2."' ";
								}

								if($pais != ""){
									$select .= " AND p.NomPais like '%".$pais."%' ";
								} 

								if($album != ""){
									$select .= " AND a.Titulo like '%".$album."%'";
								}
								// Al no estar el usuario logueado, usamos los usuarios globales creados en la base de datos por defecto.
								$link = mysqli_connect('localhost', 'root', '', 'pibd');
								// Cambiamos la codificacion para que no surgan problemas con los acentos.
								mysqli_set_charset($link, 'utf8');
								// Recogemos el resultado
								$resultado = mysqli_query($link, $select);
								// Mostramos a continuación los países en el select

								$ha_entrado = false;
								
								while($fila = mysqli_fetch_assoc($resultado)){
									// Hacemos otro select para el pais.
									$select_pais = "SELECT NomPais from paises where IdPais = '".$fila['Pais']."'";
									$resultado_pais = mysqli_query($link, $select_pais);
									$pais = mysqli_fetch_assoc($resultado_pais);
									echo'
										<div class="section_container">
											<article>
												<h2><a href="detalle_foto.php?foto='.$fila['IdFoto'].'">'.$fila['Titulo'].'</a></h2>
												<figure>
													<a href="detalle_foto.php?foto='.$fila['IdFoto'].'"><img src="'.$fila['Fichero'].'" alt="" width="600" height="400"></a>
												</figure>
												<div>
													<p>Fecha: <time datetime="2018-09-21 ">'.$fila['Fecha'].'</time></p>
													<p>País: '.$pais['NomPais'].'</p>
													<p>Álbum: '.$fila['aTitulo'].'</p>
												</div>
											</article>
										</div>
									';
									$ha_entrado = true;
								}

								if(!$ha_entrado){
									echo'
										<h2>No se ha podido encontrar ningún resultado &nbsp;&nbsp;&nbsp;&nbsp;<a href="formulario_busqueda.php" style="color:red;">¿Realizar otra búsqueda?</a></h2>
									';
								}
								
								
		 ?>
</main>

<?php
	require_once("footer.inc");
?>