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
				<link rel="stylesheet" type="text/css" href="'.$estilo['Fichero'].'/'.strtolower($estilo['Nombre']).'_editar_perfil.css" title="'.$estilo['Nombre'].'">
			';
		}else{
			// Por defecto mostramos el estilo Responsive
			echo '
				<link rel="stylesheet" type="text/css" href="./Responsive_css/responsive_editar_perfil.css" title="Responsive">
			';
		}
		
	 ?>
	
	<title>Editar perfil</title>
</head>
<?php 
	if(isset($_COOKIE['usuario_cookie']) || isset($_SESSION['username_session'])){

	echo'	<main>';
	
		if(isset($_COOKIE['usuario_cookie'])){
		
		$select = "SELECT * FROM usuarios WHERE NomUsuario = '". $_COOKIE['usuario_cookie']."'";
		$link = mysqli_connect('localhost', 'root', '', 'pibd');
		// Cambiamos la codificacion para que no surgan problemas con los acentos.
		mysqli_set_charset($link, 'utf8');
		$resultado = mysqli_query($link, $select);
		$fila = mysqli_fetch_assoc($resultado);
		// Hacemos otro select para el pais.
		$select_pais = "SELECT NomPais from paises where IdPais = '".$fila['Pais']."'";
		$resultado_pais = mysqli_query($link, $select_pais);
		$pais = mysqli_fetch_assoc($resultado_pais);

			
		echo'
			<h2>Datos actuales de <b style="color:red;font-size:60px;">'.$fila['NomUsuario'].'</b></h2>

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
				
			 
				
			</div>
				';		
		}elseif(isset($_SESSION['username_session'])){
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

				
			echo'
				<h2>Datos actuales de <b style="color:red;font-size:60px;">'.$fila['NomUsuario'].'</b></h2>

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
					
				 
					
				</div>
					';		
		}
	 

	echo '<h2>Editar datos del perfil</h2>';
		if(isset($_GET['error_pass'])){
			echo'<p style="color:red; font-size:30px;"> La contraseña introducida no es correcta</p>';
		}
	echo' <form action="comprobacion_editar_perfil.php" method="post" enctype="multipart/form-data">
		<div class="container_registro">
			<div>
				<label>
					<b>Nombre de usuario</b>
		  			<br>
					<input type="text" name="username" placeholder="Nombre">
					<br><br>
				</label>';
				if(isset($_GET['error_nombre'])){
						echo '<b style="color:red">El nombre puede contener caracteres a-z, A-Z, 0-9</b>
							  <b style="color:red">y debe ser entre 3-15 caracteres máximo.</b>
						';
					}

				if(isset($_GET['ur'])){
					echo '<b style="color:red">El nombre está repetido</b>
					';
				}
		echo'	</div>

			<div>
				<label>
					<b>Email</b>
					<br>
					<input type="email" name="email" placeholder="Correo">
					<br><br>
				</label>';
				if(isset($_GET['error_email'])){
						echo '<b style="color:red">El email no cumple con el formato</b>			 
						';
					}
		echo'	</div>

			<div>
		  		<label>
		  			<b>País</b>
		  			<br>
		  			<select name="pais" id="pais">
		  				'; 
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
							
		  				
		  	echo'		</select>


		  			<br><br>
		  		</label>
				
			</div>

			<div>
				<label>
					<b>Ciudad</b>
		  			<br>
		  			<input type="text" name="ciudad" placeholder="Ciudad">
		  			<br><br>
				</label>	
			</div>

			<div>
				<b>Sexo</b>
				<br>
				<label for="hombre">Hombre</label>
				<input type="radio" name="sexo" id="hombre" value="Hombre">

				<label for="mujer">Mujer</label>
				<input type="radio" name="sexo" id="mujer" value="Mujer">
				<br><br>';
				if(isset($_GET['error_sexo'])){
						echo '<b style="color:red">No has seleccionado ninguna de las opciones. Por favor, selecciona alguna.</b>			 
						';
					}
		echo'	</div>

				<b>Borrar imagen: </b>
				<label>Borrar<input type = "checkbox" name = "borrar_imagen" value = "Borrar"></label>
				
				<br><br/>
			<div>
				<label>
					<b>Fecha de nacimiento</b>
		  			<br>
		  			<input type="date" name="fecha_nacimiento">
		  			<br><br>
				</label>';
				
				if(isset($_GET['error_fecha_nacimiento'])){
						echo '<b style="color:red">La fecha no es correcta</b>			 
						';
					}
	echo'		</div>
		 
			<input type="file" name="file" id="file" value="Subir archivo">
			<label for="file">Subir imagen</label>
			<br><br>
			<div id="toggle" style="display:none;">
				 <p>Los cambios se aplicarán, estás seguro de que quieres realizar el cambio?</p>
				 <p>Introduzca su contraseña en caso afirmativo o pulse sobre el botón "No" si no desea aplicar ningún cambio.</p>
				 <input type="password" name="passwordToggle" value="">
				 <input type="submit" value="Cambiar">
				 <input type="button" value="No" onclick="toggleYesNo()">
				 

			</div>
		 	<input type="button" value="Actualizar" onclick="toggleYesNo()">
		  <br><br>
		</div>
		

		
	</form>
</main>';

	}else{
		header('Location:index.php?usuario_registrado_authorizationError');
	}
 ?>
<?php
	require_once("footer.inc");
?>

<script type="text/javascript">
	
	function toggleYesNo(){
		var x = document.getElementById("toggle");
	    if (x.style.display === "none") {
	        x.style.display = "block";
	    } else {
	        x.style.display = "none";
	    }
	}

</script>