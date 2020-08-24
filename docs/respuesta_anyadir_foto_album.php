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
<main>
	
	<?php 

		$link = mysqli_connect('localhost', 'root', '', 'pibd');
		// Cambiamos la codificacion para que no surgan problemas con los acentos.
		mysqli_set_charset($link, 'utf8');

		$sql_pais = "SELECT IdPais from paises where NomPais = '".$_POST['pais']."' ";
		$resultado = mysqli_query($link, $sql_pais);
		$pais = mysqli_fetch_assoc($resultado);

		$sql_album = "SELECT IdAlbum from albumes where Titulo = '".$_POST['album']."' ";
		$resultado = mysqli_query($link, $sql_album);
		$album = mysqli_fetch_assoc($resultado);

		// Para la SUBIDA DE FICHERO
		$direccion_fichero;
			if($_FILES["file"]["error"] > 0) { 
			   echo "Error: " . $msgError[$_FILES["file"]["error"]] . "<br />"; 
			 } 
			 else { 
			 	$fichero = $_FILES['file']['name'];
		 		$actual_name = pathinfo($fichero,PATHINFO_FILENAME);
				$original_name = $actual_name;
				$extension = pathinfo($fichero, PATHINFO_EXTENSION);

				$i = 1;
				while(file_exists('./Images/'.$actual_name.".".$extension))
				{           
				    $actual_name = (string)$original_name.$i;
				    $name = $actual_name.".".$extension;
				    $i++;
				}

				$direccion_fichero = "./Images/".$actual_name.".".$extension;


				// Comprobamos si existe algún album, si no existe no se podrá añadir ninguna foto.
				if($album != null){
					$sql = "INSERT INTO fotos (Titulo, Descripcion, Fecha, Pais, Album, FRegistro, Fichero) VALUES ('".$_POST['titulo_foto']."', '".$_POST['descripcion']."', '".$_POST['fecha_foto']."', ".$pais['IdPais'].",  ".$album['IdAlbum'].", now(), '".$direccion_fichero."')";
					

					if(mysqli_query($link, $sql)){
						// Si todo fue bien guardamos la imagen
						move_uploaded_file($_FILES["file"]["tmp_name"], "Images/".$actual_name.".".$extension);
						echo'
							<h3>La foto ha sido insertada correctamente </h3>
							
							<div id="detalle_insercion">
								<p>Título: <b style="color:red;">'.$_POST['titulo_foto'].'</b></p>
								<p>Descripción: <b style="color:red;">'.$_POST['descripcion'].'</b></p>
								<p>Fecha de creación: <b style="color:red;">'.$_POST['fecha_foto'].'</b></p>
								<p>País: <b style="color:red;">'.$_POST['pais'].'</b></p>
								<p>Álbum: <b style="color:red;">'.$_POST['album'].'</b></p>
							</div>
						';

					}

				}else{
					header('Location:anyadir_foto_album.php?ea');
				}

			}

	 ?>

</main>