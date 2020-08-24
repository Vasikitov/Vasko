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
				<link rel="stylesheet" type="text/css" href="'.$estilo['Fichero'].'/'.strtolower($estilo['Nombre']).'_formulario_busqueda.css" title="'.$estilo['Nombre'].'">
			';
		}else{
			// Por defecto mostramos el estilo Responsive
			echo '
				<link rel="stylesheet" type="text/css" href="./Responsive_css/responsive_formulario_busqueda.css" title="Responsive">
			';
		}
		
	 ?>

	<title>Formulario de búsqueda avanzada</title>
</head>
<main>
	<h3>Formulario de búsqueda avanzada</h3>

	<form action="resultado_busqueda.php" method="post">
		
		<div class="container_busqueda_avanzada">
			<div>
				<label>
					Título:
					<input type="text" name="titulo" placeholder="Título">
					<br><br>
				</label>	
			</div>
			
			<div>
				<label>
					Fecha entre:
					<input type="date" name="fecha1" value="Día/Mes/Año">
				</label>

				<label>
					y 
					<input type="date" name="fecha2" placeholder="">
					<br><br>
				</label>
					
			</div>
			
			<div>
		  		<label>
		  			País: 
		  			
		  			<select name="pais" id="pais">
		  				<option label=""></option>
		  				<?php 
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
							
		  				 ?>
		  			</select>


		  			<br><br>
		  		</label>
				
			</div>

			<div>
				<label>
					Álbum:
					<input type="text" name="album" placeholder="Álbum">
					<br><br>
				</label>	
			</div>

		</div>
		
		<div class="button">
			<input type="submit" name="buscar" value="Buscar">
		</div>

	</form>
	
</main>

<?php
	require_once("footer.inc");
?>