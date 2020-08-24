<body>
	<div class="flex_containerTit">
    	<p>Página principal</p>
    	<p>URL: file:///Practica%205/index.html</p>
    </div>
    	
    
	<header>
		<a href="index.php"><h1>Myst</h1></a>
	</header>
<!--
	<nav>
		<a href="index.html"><span class="icon-menu"></span>Menú</a>
        <a href="index.html"><span class="icon-home"></span>Inicio</a>
        <a href="formulario_busqueda.html"><span class="icon-search-circled"></span>Buscar</a>
        <a href="registro.html"><span class="icon-doc-text-inv"></span>Registrarse</a>
        <a href="usuario_registrado.html"><span class="icon-user"></span>Mi perfil</a>
	</nav>
	-->
<?php 
    session_start();

    if(isset($_GET['finalize_session'])){
        $_SESSION = array();
        // Si el usuario ha activado la opcion recordarme, borramos la cookie
        if(isset($_COOKIE['usuario_cookie'])){
           $link = mysqli_connect('localhost', 'root', '', 'pibd');
            // Intentamos insertar en la base de datos la ultima conexion del usuario.
            $sql = "UPDATE usuarios SET FRegistro = '".$_COOKIE['date_usuario']." ".$_COOKIE['time_usuario']."' where NomUsuario = '".$_COOKIE['usuario_cookie']."' ";
            // Cambiamos la codificacion para que no surgan problemas con los acentos.
            mysqli_set_charset($link, 'utf8');
            // Insertamos
            mysqli_query($link, "SET FOREIGN_KEY_CHECKS=0");
            mysqli_query($link, $sql);
            mysqli_query($link, "SET FOREIGN_KEY_CHECKS=1");

            mysqli_close($link);

            setcookie('usuario_cookie', "", time()-3600);
            unset($_COOKIE['usuario_cookie']); 
        }

        if(isset($_COOKIE['contraseña_cookie'])){
            setcookie('contraseña_cookie', "", time()-3600);
            unset($_COOKIE['contraseña_cookie']); 
        }

        if(isset($_COOKIE['date_usuario'])){
            setcookie('date_usuario', "", time()-3600);
            unset($_COOKIE['date_usuario']); 
        }

        if(isset($_COOKIE['time_usuario'])){
            setcookie('time_usuario', "", time()-3600);
            unset($_COOKIE['time_usuario']); 
        }

        if(isset($_COOKIE['estilo_pagina'])){
            setcookie('estilo_pagina', "", time()-3600);
            unset($_COOKIE['estilo_pagina']); 
        }
        
        

        session_destroy();
    }
    echo'
        <input type="checkbox" id="activador">
        <nav>
            <ul>
                <li><label for="activador">&equiv;</label></li>
                <li><a href="index.php"><span class="icon-home" aria-hidden="true"></span><span>Inicio</span></a></li>
                <li><a href="formulario_busqueda.php"><span class="icon-search-circled" aria-hidden="true"></span><span>Buscar</span></a></li>';
                
                if(isset($_COOKIE['usuario_cookie']) || isset($_SESSION['username_session'])){
                    echo'
                        <li><a href="usuario_registrado.php"><span class="icon-user" aria-hidden="true"></span><span>Mi perfil</span></a></li>

                        <li><a href="index.php?finalize_session"><span class="icon-user" aria-hidden="true"></span><span>Logout</span></a></li>
                    ';
                }else{
                    echo'
                        <li><a href="registro.php"><span class="icon-doc-text-inv" aria-hidden="true"></span><span>Registro</span></a></li>
                    ';
                }
                
          echo  '</ul>
        </nav>';
 ?>
	
</body>