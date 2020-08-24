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
				$url = 'Location:editar_perfil.php?';

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

				$sql_usu = "SELECT NomUsuario from  usuarios where NomUsuario = '".$_POST['username']."'";
				$resultado_usu = mysqli_query($link, $sql_usu);
				$usu = mysqli_fetch_assoc($resultado_usu);

				if($usu == null){

						
						$entero_sexo = 0;
						if($_POST['sexo'] == 'Mujer'){
							$entero_sexo = 1;
						}
						session_start();
						$sql_pais = "SELECT IdPais from paises where NomPais = '".$_POST['pais']."' ";
						$resultado = mysqli_query($link, $sql_pais);
						$pais = mysqli_fetch_assoc($resultado);

						$usuario = null;
						if(isset($_COOKIE['usuario_cookie'])){
							$usuario = $_COOKIE['usuario_cookie'];
						}else{
							$usuario = $_SESSION['username_session'];
						}

						


						// Comprobacion de cambios de perfil con la introducción de contraseña

						if(isset($_POST['passwordToggle'])){
							//=================== Para la SUBIDA DE FICHERO========================
							$direccion_fichero = null;
							if($_FILES['file']['size'] > 0) { 
								// Si el usuario ha seleccionado una imagen, la procesamos
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
							 } 
							 
							
								//SQL para comprobar la contraseña introducida por el usuario
							  	$sql = "SELECT NomUsuario, Foto from usuarios where Clave = '".$_POST['passwordToggle']."'";

							$resultado = mysqli_query($link, $sql);
							$usu_res = mysqli_fetch_assoc($resultado);
							if($usu_res != null){
								if($usu_res['NomUsuario'] == $usuario){

									if(!isset($_POST['borrar_imagen']) && $_FILES['file']['size'] == 0){
										$sql = "UPDATE usuarios SET NomUsuario = '".$_POST['username']."', Email = '".$_POST['email']."', Pais = '".$pais['IdPais']."', Ciudad = '".$_POST['ciudad']."', Sexo = ".$entero_sexo.", FNacimiento = '".$_POST['fecha_nacimiento']."' WHERE NomUsuario = '".$usuario."' ";
									}else{
										// Aqui hacemos la sql FINAL
										$sql = "UPDATE usuarios SET NomUsuario = '".$_POST['username']."', Email = '".$_POST['email']."', Pais = '".$pais['IdPais']."', Ciudad = '".$_POST['ciudad']."', Foto = '".$direccion_fichero."', Sexo = ".$entero_sexo.", FNacimiento = '".$_POST['fecha_nacimiento']."' WHERE NomUsuario = '".$usuario."' ";
									}
									
									if(mysqli_query($link, $sql)){
										if(!isset($_POST['borrar_imagen']) && $_FILES['file']['size'] > 0){
											// En caso de que no se le haya dado al checkbox de borrar
											move_uploaded_file($_FILES["file"]["tmp_name"], "Images/".$actual_name.".".$extension);
										}
										if(isset($_POST['borrar_imagen']) && $_FILES['file']['size'] == 0){
											// En caso de que se le haya dado a borrar, también eliminamos la imagen
											unlink ($usu_res['Foto']);
										}

										if(isset($_POST['borrar_imagen']) && $_FILES['file']['size'] > 0){
											unlink ($usu_res['Foto']);
											move_uploaded_file($_FILES["file"]["tmp_name"], "Images/".$actual_name.".".$extension);
										}
										
										$_COOKIE['usuario_cookie'] = $_POST['username'];
										$_SESSION['username_session'] = $_POST['username'];
									}else{
										echo "Error: " . $sql . "<br>" . mysqli_error($link);
									}

									header('Location: usuario_registrado.php?dm');
								}		
							}else{
								header('Location:editar_perfil.php?error_pass');
							}
						 
					}

				}else{
				header('Location:editar_perfil.php?ur');
				}
	}


			

?>