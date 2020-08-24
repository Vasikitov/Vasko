<?php
	require_once("cabecera.inc"); 
	require_once("navegacion.php");
	//require("comprobacion.php");
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
				<link rel="stylesheet" type="text/css" href="'.$estilo['Fichero'].'/'.strtolower($estilo['Nombre']).'_registro.css" title="'.$estilo['Nombre'].'">
			';
		}else{
			// Por defecto mostramos el estilo Responsive
			echo '
				<link rel="stylesheet" type="text/css" href="./Responsive_css/responsive_registro.css" title="Responsive">
			';
		}
		
	 ?>
	
	<title>Registro</title>
</head>
<main>

	<h3>Registrarse</h3>

	<form action="respuesta_registro.php" method="post" enctype="multipart/form-data">
		<div class="container_registro">
			<div>
				<label>
					<b>Nombre de usuario</b>
		  			<br>
					<input type="text" name="username" placeholder="Nombre">
					<br><br>
				</label>
	
				<?php
					if(isset($_GET['error_nombre'])){
						echo '<b style="color:red">El nombre puede contener caracteres a-z, A-Z, 0-9</b>
							  <b style="color:red">y debe ser entre 3-15 caracteres máximo.</b>
						';
					}

					if(isset($_GET['ur'])){
						echo '<b style="color:red">El nombre está repetido</b>
						';
					}
				 ?>
			</div>

			<div>
				<label>
					<b>Contrase&ntilde;a</b>
		  			<br>
		  			<input type="password" name="password" placeholder="Clave">
		  			<br><br>
				</label>
				
				<?php 
					if(isset($_GET['error_password'])){
						echo '	<b style="color:red">La contraseña puede coneter caracteres a-z, A-Z, 0-9 y "_" como carácter especial </b>
								</br>
							  	<b style="color:red">y debe ser entre 6-15 caracteres máximo y contener al menos </b>
							  	</br> 
							  	<b style="color:red">una mayúscula, minúscula y dígito como mínimo.</b>

						';
					}
				 ?>
			</div>
		 
		  	<div>
		  		<label>
		  			<b>Repetir contrase&ntilde;a</b>
		  			<br>
		  			<input type="password" name="rpassword" placeholder="Clave">
		  			<br><br>
		  		</label>
				<?php 
					if(isset($_GET['error_rpassword'])){
						echo '<b style="color:red">No coincide con la contraseña</b>			 
						';
					}
					
				 ?>
			</div>

			<div>
				<label>
					<b>Email</b>
					<br>
					<input type="email" name="email" placeholder="Correo">
					<br><br>
				</label>
				<?php 
					if(isset($_GET['error_email'])){
						echo '<b style="color:red">El email no cumple con el formato</b>			 
						';
					}
					
				 ?>
			</div>

			<div>
				<b>Sexo</b>
				<br>
				<label for="hombre">Hombre</label>
				<input type="radio" name="sexo" id="hombre" value="Hombre">

				<label for="mujer">Mujer</label>
				<input type="radio" name="sexo" id="mujer" value="Mujer">
				<br><br>
				<?php 
					if(isset($_GET['error_sexo'])){
						echo '<b style="color:red">No has seleccionado ninguna de las opciones. Por favor, selecciona alguna.</b>			 
						';
					}
					
				 ?>
			</div>

			<div>
				<label>
					<b>Fecha de nacimiento</b>
		  			<br>
		  			<input type="date" name="fecha_nacimiento" >
		  			<br><br>
				</label>
				<?php 
					if(isset($_GET['error_fecha_nacimiento'])){
						echo '<b style="color:red">La fecha no es correcta</b>			 
						';
					}
					
				 ?>
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
		  		<label>
		  			<b>País</b>
		  			<br>
		  			<select name="pais" id="pais">
		  				<?php 
		  					// Hacemos una select al servidor y usaremos los paises que nos devuelva para ponerlos en el select
		  					$select = "SELECT * from paises";
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
							
		  				 ?>
		  			</select>


		  			<br><br>
		  		</label>
				
			</div>
		 
			<input type="file" name="fichero" id="file" value="Subir archivo">
			<label for="file">Subir archivo</label>
			<br><br>
		 	<input type="submit" name="submit" value="Enviar">
		  <br><br>
		</div>	
	</form>
</main>

<?php
	require_once("footer.inc");
?>