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
				<link rel="stylesheet" type="text/css" href="'.$estilo['Fichero'].'/'.strtolower($estilo['Nombre']).'_index.css" title="'.$estilo['Nombre'].'">
			';
		}else{
			// Por defecto mostramos el estilo Responsive
			echo '
				<link rel="stylesheet" type="text/css" href="./Responsive_css/responsive_index.css" title="Responsive">
			';
		}
		
	 ?>
	
	<title>Index</title>
</head>
<main>
	<?php
	$location_acceder = "window.location.href='usuario_registrado.php'";
	$location_salir = "window.location.href='index.php?finalize_session'";

	echo '<div class="flex_container">
		<form action="control_acceso.php" class="formulario_login" method="post">';

		if(!isset($_COOKIE['usuario_cookie']) && !isset($_SESSION['username_session'])){
			echo '
				 <h3>Log In</h3>
				 <label>
						Usuario:
				  		<input type="text" name="username" placeholder="Nombre" required="required">
				  		<br><br>
					</label>
				 
				 <label>
				 		Contraseña:
				  		<input type="password" name="password" placeholder="Clave" required="required">
						<br><br>				
				 </label>
				 <label for="remember">¿Recuérdame?</label>
    			 <input type="checkbox" name="remember" id="remember"><br>
				 <input type="submit" value="Enviar">';
				if(isset($_GET['error_user']) && isset($_GET['error_password'])) {
					echo '<p class = "error">El usuario o la contraseña que has introducido son incorrectos</p>';
				}

				if(isset($_GET['detalle_foto_authorizationError'])){
					echo'
						<p style="color:red;">No puedes acceder a <b>Detalle de foto</b> sin estar registrado y logueado</p>	
					';
				}

				if(isset($_GET['usuario_registrado_authorizationError'])){
					echo'
						<p style="color:red;">No puedes acceder al perfil de <b>Usuario registrado</b> sin estar registrado y logueado</p>	
					';
				}
		}
			// Este if es para actualizar la informacion sobre la ultima entrada del usuario en su cuenta.
			if(isset($_COOKIE['usuario_cookie'])){
				$link =  mysqli_connect('localhost', 'root', '', 'pibd');
				$sql = "SELECT FRegistro from usuarios where NomUsuario = '".$_COOKIE['usuario_cookie']."' ";
				// Cambiamos la codificacion para que no surgan problemas con los acentos.
				mysqli_set_charset($link, 'utf8');
				// Recogemos el resultado
				$resultado = mysqli_query($link, $sql);
				$fregistro = mysqli_fetch_assoc($resultado);
				echo '
					<p>'.$_COOKIE['usuario_cookie'].', su última visita fue el '.$fregistro['FRegistro'].'</p>
					<input type="button" onclick='.$location_acceder.' value="Acceder"></button>
					<input type="button" onclick='.$location_salir.' value="Salir"></button>
				';			
			}
				echo '</form>';

				echo '<form action="index.php" class="formulario_busqueda" method="post">
			    	<h3>Búsqueda rápida</h3>
				    <input type="search" placeholder="Busca">
				    <br><br>
			        <input type="submit" value="Buscar"/>
    			</form>
		</div>';
	 ?>


	 <?php 
	 	// Foto seleccionada INDEX


			 echo '<div class="foto_seleccionada">';
                  if(($a=file('seleccionadas.txt')) == false)
                  {
                      
                  echo 
                 	 '<h2> Foto seleccionada </h2>
                      <p> No se ha podido acceder al fichero </p>

                       ';
                  }else{

                      $a=file('seleccionadas.txt');
                      $num= mt_rand(0, count($a)-1);
                      list($nombre,$comentario,$idfoto) = explode("|",$a[$num]);


                $sentencia = "SELECT IdFoto, Titulo, Fecha, NomPais, Fichero FROM fotos, paises WHERE IdFoto=". $idfoto ." and Pais=IdPais ";

                if(!($resultado = @mysqli_query($link, $sentencia))) {
                  echo "<p>Error al ejecutar la sentencia <b>$sentencia</b>: " . mysqli_error($link);
                 echo '</p>';
                 exit;
                 }
                $fila = mysqli_fetch_assoc($resultado);
                echo '
	                	<h2> Foto seleccionada </h2>

	                     <a href="detalle_foto.php?foto='.$fila['IdFoto'].'"><img src="'.$fila['Fichero'].'" alt="" width="600" height="400"></a>
	                     </a>

						<p><b>Título: </b> '.$fila['Titulo'].'</p>
	                    <p><b>Fecha: </b>'. $fila['Fecha'] . '<br></p>
	                    <p><b>País: </b>'. $fila['NomPais'] .'</p></p>
	                    <p><b>Seleccionada por: </b>'. $nombre . '<br></p>
	                    <p><b>Comentario: </b>'. $comentario . ' <br></p>


	                     <a href="seleccionadas.php" style="font-size:30px;">Ver más</a>


	                     </div>';
                }

	  ?>
		
	<section>
		<h3>Últimas fotos subidas</h3>
		<div class="section_container">
		<?php 
			// Hacemos una select al servidor y usaremos los paises que nos devuelva para ponerlos en el select
			$select = "SELECT * from fotos order by FRegistro DESC limit 5";
			
			
			$link = mysqli_connect('localhost', 'root', '', 'pibd');
			
			// Cambiamos la codificacion para que no surgan problemas con los acentos.
			mysqli_set_charset($link, 'utf8');
			// Recogemos el resultado
			$resultado = mysqli_query($link, $select);
			// Mostramos a continuación los países en el select
			

			while($fila = mysqli_fetch_assoc($resultado)){
				// Hacemos otro select para el pais.
				$select_pais = "SELECT NomPais from paises where IdPais = '".$fila['Pais']."'";
				$resultado_pais = mysqli_query($link, $select_pais);
				$pais = mysqli_fetch_assoc($resultado_pais);

				$select_album = "SELECT  * from albumes where IdAlbum ='".$fila['Album']."' ";
				// Recogemos el resultado
				$resultado_album = mysqli_query($link, $select_album);
				// Solo va ser un album el seleccionado, asi que no hará falta iterar.
				$album = mysqli_fetch_assoc($resultado_album);

				
					echo'
						<article>
							<h2><a href="detalle_foto.php?foto='.$fila['IdFoto'].'">'.$fila['Titulo'].'</a></h2>
							<figure>
								<a href="detalle_foto.php?foto='.$fila['IdFoto'].'"><img src="'.$fila['Fichero'].'" alt="" width="600" height="400"></a>
							</figure>
							<div>
								<p>Fecha: <time datetime="2018-09-21 ">'.$fila['Fecha'].'</time></p>
								<p>País: '.$pais['NomPais'].'</p>
							</div>
						</article>	
					';
									
			}
			
		 ?>
		</div>
	</section>
	 
</main>

<?php
	require_once("footer.inc");
?>