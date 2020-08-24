<?php
	$title="Página principal";
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
				<link rel="stylesheet" type="text/css" href="'.$estilo['Fichero'].'/'.strtolower($estilo['Nombre']).'_respuesta_registro.css" title="'.$estilo['Nombre'].'">
			';
		}else{
			// Por defecto mostramos el estilo Responsive
			echo '
				<link rel="stylesheet" type="text/css" href="./Responsive_css/responsive_respuesta_registro.css" title="Responsive">
			';
		}
		
	 ?>
	
	<title>Respuesta registro</title>
</head>
<main>
	<h3>Inserción realizada correctamente</h3>
	<div class="cont">
		<?php 
			// Haremos primero las comprobaciones del registro de usuario, en caso afirmativo mostraremos
			// el mensaje correspondiente. En caso negativo, devolveremos al usuario a la página de registro
			// diciéndole qué es lo que ha introducido mal o no ha introducido.
			$error_nombre = false;
			$error_password = false;
			$error_rpassword = false;
			$error_email = false;
			$error_sexo = false;
			$error_fecha_nacimiento = false;
			$error_total = 0;
			// Comprobamos el "nombre"
			if(isset($_POST['username'])){
				$regex_char = "/^[A-Za-z0-9]+$/";
				if(preg_match($regex_char, $_POST['username']) === 1 && (strlen($_POST['username']) >= 3 && strlen($_POST['username']) <= 15 )){
					//echo 'Usuario correcto';
				}else{
					$error_nombre = true;
					$error_total++;
				}

			}

			// Comprobamos contraseña
			if(isset($_POST['password'])){
				$regex_char = "/^[A-Za-z0-9-_]+$/";
				$regex_upper = "/[A-Z]/";
				$regex_lower = "/[a-z]/";
				$regex_digit = "/[0-9]/";
				if(preg_match($regex_char, $_POST['password']) === 1 && (strlen($_POST['password']) >= 6 && strlen($_POST['password']) <= 15 ) && preg_match($regex_upper, $_POST['password']) && preg_match($regex_lower, $_POST['password']) && preg_match($regex_digit, $_POST['password'])){
					//echo 'Contraseña correcta';
				}else{
					$error_total++;
					$error_password = true;
				}
			}

			// Comprobamos contraseña repetida
			if(isset($_POST['password']) && isset($_POST['rpassword'])){
				if(strcmp($_POST['password'], $_POST['rpassword']) == 0){
					//echo 'Corresto contraseña repetida';
				}
				else{
					$error_total++;
					$error_rpassword = true;
				}
			}

			// Comprobamos email
			if(isset($_POST['email'])){
				if(trim($_POST['email']) != ''){
					if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
						//echo'Email válido';
					}
					else{
						$error_total++;
						$error_email = true;
					}
				}else{
					$error_total++;
					$error_email = true;
				}
			}

			// Comprobamos el sexo
			if(!isset($_POST['sexo'])){
				$error_total++;
				$error_sexo = true;
			}
				
			

			// Comprobamos la fecha de nacimiento
			if(isset($_POST['fecha_nacimiento'])){
				if($_POST['fecha_nacimiento'] == ''){
					$error_total++;
					$error_fecha_nacimiento = true;
				}else{
					$d = DateTime::createFromFormat('Y-m-d', $_POST['fecha_nacimiento']);
					if($d->format('Y-m-d') != $_POST['fecha_nacimiento']){
						$error_total++;
						$error_fecha_nacimiento = true;
					}
				}
			}


			//Vamos mandando los errores
			$primero = false;
			if($error_total > 0){
				$url = 'Location:registro.php?';

				if($error_nombre == true){
					if(!$primero){
						$url .= "error_nombre";
						$primero = true;
					}else $url .= "&error_nombre";
				} 

				if($error_password == true){
					if(!$primero){
						$url .= "error_password";
						$primero = true;
					}else $url .= "&error_password";
				} 

				if($error_rpassword == true){
					if(!$primero){
						$url .= "error_rpassword";
						$primero = true;
					}else $url .= "&error_rpassword";
				} 

				if($error_email == true){
					if(!$primero){
						$url .= "error_email";
						$primero = true;
					}else $url .= "&error_email";
				} 

				if($error_sexo == true){
					if(!$primero){
						$url .= "error_sexo";
						$primero = true;
					}else $url .= "&error_sexo";
				} 

				if($error_fecha_nacimiento == true){
					if(!$primero){
						$url .= "error_fecha_nacimiento";
						$primero = true;
					}else $url .= "&error_fecha_nacimiento";
				} 	

				header($url);
			}else{
			
					// Para la SUBIDA DE FICHERO
					$direccion_fichero;
					if($_FILES["fichero"]["error"] > 0) { 
					   echo '<form action="registro.php">
							<input type="submit" value="Volver" />
						</form>';
					 } 
					 else { 
					 	$fichero = $_FILES['fichero']['name'];
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
					

						$link = mysqli_connect('localhost', 'root', '', 'pibd');
						mysqli_set_charset($link, 'utf8');
						
						$entero_sexo = 0;
						if($_POST['sexo'] == 'Mujer'){
							$entero_sexo = 1;
						}

					    $direccion_fichero = "./Images/".$actual_name.".".$extension;
					    
						$sql_pais = "SELECT IdPais from paises where NomPais = '".$_POST['pais']."' ";
						$resultado = mysqli_query($link, $sql_pais);
						$pais = mysqli_fetch_assoc($resultado);

						$sql = "INSERT INTO usuarios (NomUsuario, Clave, Email, Sexo, FNacimiento, Ciudad, Pais, Foto, FRegistro, Estilo) VALUES('".$_POST['username']."', '".$_POST['password']."' , '".$_POST['email']."' , ".$entero_sexo." , '".$_POST['fecha_nacimiento']."', '".$_POST['ciudad']."', ".$pais['IdPais'].", '".$direccion_fichero."', now(), 1)";
						if(mysqli_query($link, $sql)){
							//echo'Se ha creado correctamente';
							//header('Location: respuesta_registro.php');

							// Si la sql ha salido bien, movemos la imagen al fichero correspondiente
							move_uploaded_file($_FILES["fichero"]["tmp_name"], "Images/".$actual_name.".".$extension);
							// Datos fichero
							echo '<img src="'.$direccion_fichero.'" alt="" width="300" height="250">';
						    echo '<br></br>';
						   	echo "Nombre original: " . $_FILES["fichero"]["name"] . "<br />"; 
						   	echo "Tipo: " . $_FILES["fichero"]["type"] . "<br />"; 
						   	echo "Tamaño: " . ceil($_FILES["fichero"]["size"] / 1024) . " Kb<br />"; 
						  	echo "Nombre temporal: " . $_FILES["fichero"]["tmp_name"] . "<br />";
						  	echo "Almacenado en: " . "Images/" . $_FILES["fichero"]["name"];

						  	// Datos registro usuario
							echo '<p>Usuario: <b style="color:red;">'.$_POST['username'].'</b></p>'; 
							echo '<p>Contraseña: <b style="color:red;">'.$_POST['password'].'</b></p>';
							echo '<p>Email: <b style="color:red;">'.$_POST['email'].'</b></p>';
							echo '<p>Sexo: <b style="color:red;">'.$_POST['sexo'].'</b></p>';
							echo '<p>Fecha de nacimiento: <b style="color:red;">'.$_POST['fecha_nacimiento'].'</b></p>';
							echo '<p>Ciudad: <b style="color:red;">'.$_POST['ciudad'].'</b></p>';
							echo '<p>País: <b style="color:red;">'.$_POST['pais'].'</b></p>';
						}else{
							echo "Error: " . $sql . "<br>" . mysqli_error($link);
							header('Location:registro.php?ur');
						}
						

				}			
				
			}

?>
	</div>
</main>


<?php
	require_once("footer.inc");
?>