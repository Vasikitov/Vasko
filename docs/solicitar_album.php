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
				<link rel="stylesheet" type="text/css" href="'.$estilo['Fichero'].'/'.strtolower($estilo['Nombre']).'_solicitar_album.css" title="'.$estilo['Nombre'].'">
			';
		}else{
			// Por defecto mostramos el estilo Responsive
			echo '
				<link rel="stylesheet" type="text/css" href="./Responsive_css/responsive_solicitar_album.css" title="Responsive">
			';
		}
		
	 ?>
	
	<title>Solicitar Álbum</title>
</head>
<main>
<?php 
	if(isset($_COOKIE['usuario_cookie']) || isset($_SESSION['username_session'])){

		$usuario = '';

		if(isset($_COOKIE['usuario_cookie'])){
			$usuario = $_COOKIE['usuario_cookie'];
		}else{
			$usuario = $_SESSION['username_session'];
		}

		echo'
			<h2>Solicitar &aacute;lbum</h2>

			<p>El siguiente formulario servir&aacute; al usuario para la impresi&oacute;n de un album. <br>A continuaci&oacute;n se mostrar&aacute; una tabla con las tarifas correspondientes.</p>
			<h3>Tabla de precios</h3>
			<table>
		            <tr>
		               <td class="tit">
		                   Número de páginas:
		               </td>
		               <td>
		                   0.30€/pag.
		               </td>
		            </tr>
		            <tr>
		                <td class="tit">
		                    Resolución:
		                </td>
		                <td>
		                    150DPI = 0.70€<br>
							300DPI = 1.55€<br>
							450DPI = 2.20€<br>
							600DPI = 3.25€<br>
							750DPI = 4.30€<br>
							900DPI = 5.35€<br>
		                </td>
		            </tr>
		            <tr>
		                <td class="tit">
		                    Color de portada(blaco y negro o color):
		                </td>
		                <td>
		                    B&N 4.75€/pag.<br>
				COLOR 9.99€/pag.<br>
		                </td>
		            </tr>
		        </table>
	        <h3>Álbumes creados</h3>';
	        $select = "SELECT Titulo from albumes, usuarios where Usuario = IdUsuario and NomUsuario = '".$usuario."' ";
        	// Al no estar el usuario logueado, usamos los usuarios globales creados en la base de datos por defecto.
			$link = mysqli_connect('localhost', 'root', '', 'pibd');
			// Cambiamos la codificacion para que no surgan problemas con los acentos.
			mysqli_set_charset($link, 'utf8');
			// Recogemos el resultado
			$resultado = mysqli_query($link, $select);
			// Mostramos a continuación los países en el select
			echo'<div id="link_album">';
			while($fila = mysqli_fetch_assoc($resultado)){
				echo '
					
						<a href="mis_albumes.php"><em>-'.$fila['Titulo'].'</em></a>
					
					
				';
			}
			echo '</div>';
	        echo '
		    <h3>Datos del álbum</h3>
			<form action="respuesta_solicitud.php" method="post">
				<section class="dir">Datos personales</section>
				<div>
					<label>
						<b>Nombre del solicitante</b>
			  			<br>
						<input type="text" maxlength="200" name="nombre_solicitante" placeholder="Nombre del solicitante" required="required">
						<br><br>
					</label>
					
				</div>

				<div>
					<label>
						<b>T&iacute;tulo del &aacute;lbum</b>
			  			<br>
						<input type="text" maxlength="200" name="titulo_album" placeholder="T&iacute;tulo del &aacute;lbum" required="required">
						<br><br>
					</label>
					
				</div>
			 
			  	<div>
					<label>
						<b>Texto adicinal</b>
			  			<br>
						<input type="text" maxlength="4000" name="texto_adicional" placeholder="Texto adicinal">
						<br><br>
					</label>
					
				</div>

				<div>
					<label>
						<b>Correo electr&oacute;nico</b>
						<br>
						<input type="email" maxlength="200" name="email" placeholder="Correo electr&oacute;nico" required="required">
						<br><br>
					</label>
					
				</div>
				<section class="dir">Dirección</section>
				<div>
			  			<label>
			  				<a>Calle</a>
			  				<br>
			  				<input type="text" name="calle" placeholder="Calle">
							&nbsp;&nbsp;&nbsp;
			  			</label>
				</div>
				<div>
					<label>
						<a>Número</a>
		  				<br>
		  				<input type="number" name="numero" placeholder="N&uacute;mero">
						&nbsp;&nbsp;&nbsp;
					</label>
				</div>
				<div>
					<label>
						<a>Piso</a>
		  				<br>
						<input type="number" name="piso" placeholder="Piso">
						&nbsp;&nbsp;&nbsp;
					</label>
				</div>
				<div>
					<label>
						<a>Puerta</a>
		  				<br>
						<input type="text" name="puerta" placeholder="Puerta">
						&nbsp;&nbsp;&nbsp;
					</label>
				</div>
				<div>
					<label>
						<a>CP</a>
		  				<br>
						<input type="number" name="codigo_postal" placeholder="C.P">
					</label>
				</div>
				<div>
					<label>
						<a>Localidad</a>
		  				<br>
						<input type="text" name="localidad" placeholder="Localidad">
						&nbsp;&nbsp;&nbsp;
					</label>
				</div>
				<div>
					<label>
						<a>Provincia</a>
		  				<br>
						<input type="text" name="provincia" placeholder="Provincia">
						&nbsp;&nbsp;&nbsp;
					</label>
				</div>
				<div>
					<label>
						<a>País</a>
		  				<br>
						<input type="text" name="pais" placeholder="Pa&iacute;s">
					</label>
				</div>
				<br>		<br>		<br>
				<section class="dir">Información del álbum</section>
				<div>
					<label>
						<b>Color de la portada</b>
			  			<br>
			  			<input type="color" name="color_portada">
					</label>
					
				</div>
				<div>
					<label>
						<b>N&uacute;mero de copias</b>
			  			<br>
			  			<input type="number" min="1" value="1" name="numero_copias" required="required">
					</label>	
				</div>
			  
			  	<div>
			  		<label>
			  			<b>Resoluci&oacute;n de las fotos</b>
			  			<br>
			  			<input type="number" min="150" max="900" step="150" value="150" name="rango" required="required">DPI.
			  		</label>
					
				</div>

				<div>
			  		<label>
			  			<b>&Aacute;lbum portal</b>
			  			<br>
			  			<input type="text"  name="album_portal" placeholder="T&iacute;tulo del &aacute;lbum" required="required">
			  		</label>
					
				</div>

				<div>
			  		<label>
			  			<b>Fecha recepci&oacute;n &aacute;lbum</b>
			  			<br>
			  			<input type="date" name="recepcion_album">
			  		</label>
					
				</div>

				<div>
					<b>Impresi&oacute;n color</b>
					<br>
					<label for="negro_blanco">Blanco y Negro</label>
					<input type="radio" name="c" id="negro_blanco" value="Blanco y negro">

					<label for="color">Color</label>
					<input type="radio" name="c" id="color" value="Color">
				</div>
			 
				<input type="file" name="file" id="file" value="Subir archivo">
				<label for="file">Subir archivo</label>
				<br><br>
			  <input type="submit" value="Enviar">
			  <br><br>
			</form>
    
		';

	}else{
		header('Location:index.php?usuario_registrado_authorizationError');
	}
 ?>
	
</main>

<?php
	require_once("footer.inc");
?>