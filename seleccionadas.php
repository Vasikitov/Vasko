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
  
  <title>Fotos seleccionadas</title>
</head>

<main>
  <section>
    <h3>Fotos seleccionadas</h3>
    <div class="section_container">
       <?php 


                  if(($a=file('seleccionadas.txt')) == false)
                  {
                      
                  echo'
                      <p> No se ha podido acceder al fichero </p>

                       ';
                  }else{

                      $a=file('seleccionadas.txt');
                      

                      foreach($a as $linea){
                        list($nombre,$comentario,$idfoto) = explode("|",$linea);

                        $sentencia = "SELECT IdFoto, Titulo, Fecha, NomPais, Fichero FROM fotos, paises WHERE IdFoto=". $idfoto ." and Pais=IdPais ";

                        if(!($resultado = @mysqli_query($link, $sentencia))) {
                         echo "<p>Error al ejecutar la sentencia <b>$sentencia</b>: " . mysqli_error($link);
                        echo '</p>';
                        exit;
                        }
                        $fila = mysqli_fetch_assoc($resultado);
                              echo'<article>
                                <h2><a href="detalle_foto.php?foto='.$fila['IdFoto'].'">'.$fila['Titulo'].'</a></h2>

                                <figure style="text-align:center;">
                                  <a href="detalle_foto.php?foto='.$fila['IdFoto'].'"><img src="'.$fila['Fichero'].'" alt="" width="600" height="400"></a>
                                </figure>

                                <div style="margin-left:20%;margin-right:20%;flex-direction:column;text-align:center;">
                                      <p><b>Fecha: </b>'. $fila['Fecha'] . '<br></p>
                                      <p><b>País: </b>'. $fila['NomPais'] .'</p></p>
                                      <p><b>Seleccionada por: </b>'. $nombre . '<br></p>
                                      <p><b>Comentario: </b>'. $comentario . ' <br></p>
                                </div>
                               

                          </article>';
                        mysqli_free_result($resultado);
                      }
                    }

   ?>
    </div>
  </section>
</main>

<?php
  require_once("footer.inc");
?>