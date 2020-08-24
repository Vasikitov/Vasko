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
					echo 'Usuario correcto';
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
					echo 'Contraseña correcta';
				}else{
					$error_total++;
					$error_password = true;
				}
			}

			// Comprobamos contraseña repetida
			if(isset($_POST['password']) && isset($_POST['rpassword'])){
				if(strcmp($_POST['password'], $_POST['rpassword']) == 0){
					echo 'Corresto contraseña repetida';
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
						echo'Email válido';
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
				// Si todo es correcto, introducimos los datos en el servidor
				$link = mysqli_connect('localhost', 'root', '', 'pibd');
				mysqli_set_charset($link, 'utf8');
				
				$entero_sexo = 0;
				if($_POST['sexo'] == 'Mujer'){
					$entero_sexo = 1;
				}

				$sql_pais = "SELECT IdPais from paises where NomPais = '".$_POST['pais']."' ";
				$resultado = mysqli_query($link, $sql_pais);
				$pais = mysqli_fetch_assoc($resultado);

				$sql = "INSERT INTO usuarios (NomUsuario, Clave, Email, Sexo, FNacimiento, Ciudad, Pais, Foto, FRegistro, Estilo) VALUES('".$_POST['username']."', '".$_POST['password']."' , '".$_POST['email']."' , ".$entero_sexo." , '".$_POST['fecha_nacimiento']."', '".$_POST['ciudad']."', ".$pais['IdPais'].", 'C/asdasad', now(), 1)";
				if(mysqli_query($link, $sql)){
					echo'Se ha creado correctamente';
					//header('Location: respuesta_registro.php');
				}else{
					echo "Error: " . $sql . "<br>" . mysqli_error($link);
					header('Location:registro.php?ur');
				}

			}

?>