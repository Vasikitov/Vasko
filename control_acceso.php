<?php 

	
	
	// Nos intentamos conectar a la base de datos, en caso negativo que muestre
	// el error correspondiente.

	$link =  mysqli_connect('localhost', 'root', '', 'pibd');
	// usu, pass
	$sql = "SELECT NomUsuario, Clave from usuarios where NomUsuario = '".$_POST['username']."' and Clave = '".$_POST['password']."' ";
	$resultado = mysqli_query($link, $sql);
	$usuarios = mysqli_fetch_assoc($resultado);
	
	if($usuarios == null){
		header("Location: index.php?error_user&error_password");
	}else{
		// Comprobamos también si se ha seleccionado el checkbox de recuerdame.
		if(!empty($_POST['remember'])){
			date_default_timezone_set('Europe/Madrid');
			$date = date('Y-m-d');
			$time = date('H:i:s');

			// Creamos cookie del usuario
			setcookie('usuario_cookie', $_POST['username'], time()+(3600*24)*90);
			setcookie('contraseña_cookie', $_POST['password'], time()+(3600*24)*90);
			
			// Creamos cookie para almacenar el estilo seleccionado
			setcookie('estilo_pagina', "accesible", time()+(3600*24)*90);
			// Creamos cookie del día de su última visita
			setcookie('date_usuario', $date, time()+(3600*24)*90);
			// Creamos cookie del tiempo de su última visita
			setcookie('time_usuario', $time, time()+(3600*24)*90);

		}
		// Si se loguea el usuario, iniciamos la sesion.
		session_start();
		$_SESSION['username_session'] = $_POST['username'];
		$_SESSION['password_session'] = $_POST['password'];
		header('Location: usuario_registrado.php?hi');
	}

 ?>


