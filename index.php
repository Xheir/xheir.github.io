<?php

   if ($_POST["submit"] != "Buscar") {

      ?>
      <html>
         <head>
         <title> Pagina de Libros </title>
         </head>
         <body style = "background-color:powderblue;">
            <h1 style = "text-align:center;color:green"> Pagina de Libros </h1>
            <h2> Consulte por su libro, autor o genero favorito </h2>
            <br>
            <img src = "https://c.tenor.com/R5B3E0shIUgAAAAM/yoshi-dan%C3%A7ando-yoshi-e-ovo.gif" width = "300px" height = "300px" align = "right"/>
            <img src = "https://c.tenor.com/OBfGJE_1DDoAAAAC/twelve-street-fighter.gif" width = "300px" height = "300px" align = "right"/>
            <audio controls autoplay>
               <source src = "audio.ogg" type = "audio/mpeg">
               <source src = "audio.ogg" type = "audio/ogg">
               No acepta este audio.
            </audio> 
            <form action = "<?php $_PHP_SELF ?>" method = "POST">
               Consulta: <input type = "text" name = "marca" />
               <input type = "submit" name ="submit" value = "Buscar"/>
            </form>
         </body>
      </html>
      <?PHP

   }else{

      $marca = $_POST["marca"];

      $host        = "host = plop.inf.udec.cl";
      $port        = "port = 5432";
      $dbname      = "dbname = bdi2021bt";
      $user        = "user = bdi2021bt";
      $password    = "password = bdi2021bt";

      $db = pg_connect( "$host $port $dbname $user $password" );
   
      if(!$db) {
         echo "Error : No se puede acceder a la base de datos";
         echo "<br>";
      }

      $query1 = "SELECT a.nombre_autor, a.apellido_autor, l.titulo
                 FROM proyecto.autor AS a, proyecto.escrito_por AS ep, proyecto.libro AS l
                 WHERE (nombre_autor ~* '$marca' OR apellido_autor ~* '$marca' OR nacionalidad_autor ~* '$marca') AND a.id_autor = ep.id_autor AND l.id_libro = ep.id_libro ORDER BY l.titulo ASC";

      $query2 = "SELECT l.titulo, t.nombre_tienda, c.stock, c.precio_normal, c.precio_oferta, c.link_compra
                 FROM proyecto.autor AS a, proyecto.escrito_por AS ep, proyecto.libro AS l, proyecto.edicion_de_libro AS el, proyecto.contiene AS c, proyecto.tienda AS t
                 WHERE (nombre_autor ~* '$marca' OR apellido_autor ~* '$marca') AND a.id_autor = ep.id_autor AND l.id_libro = ep.id_libro AND l.id_libro = el.id_libro AND c.isbn = el.isbn AND c.id_tienda = t.id_tienda ORDER BY l.titulo ASC";

      $query3 = "SELECT l.titulo, t.nombre_tienda, c.stock, c.precio_normal, c.precio_oferta, c.link_compra
                 FROM proyecto.libro AS l, proyecto.edicion_de_libro AS el, proyecto.contiene AS c, proyecto.tienda AS t
                 WHERE titulo ~* '$marca' AND l.id_libro = el.id_libro AND c.isbn = el.isbn AND c.id_tienda = t.id_tienda ORDER BY l.titulo ASC";

      $query4 = "SELECT l.titulo, t.nombre_tienda, c.stock, c.precio_normal, c.precio_oferta, c.link_compra
                 FROM proyecto.libro AS l, proyecto.edicion_de_libro AS el, proyecto.contiene AS c, proyecto.tienda AS t, proyecto.subgenero AS s, proyecto.pertenece AS p
                 WHERE nombre_genero ~* '$marca' AND p.id_subgenero = s.id_subgenero AND l.id_libro = p.id_libro AND l.id_libro = el.id_libro AND c.isbn = el.isbn AND c.id_tienda = t.id_tienda ORDER BY l.titulo ASC";

      $query5 = "SELECT l.titulo, t.nombre_tienda, c.stock, c.precio_normal, c.precio_oferta, c.link_compra
                 FROM proyecto.libro AS l, proyecto.edicion_de_libro AS el, proyecto.contiene AS c, proyecto.tienda AS t, proyecto.editorial AS e
                 WHERE nombre_editorial ~* '$marca' AND e.id_editorial = el.id_editorial AND l.id_libro = el.id_libro AND c.isbn = el.isbn AND c.id_tienda = t.id_tienda ORDER BY l.titulo ASC";

      $query6 = "SELECT l.titulo, t.nombre_tienda, c.stock, c.link_compra
                 FROM proyecto.libro AS l, proyecto.edicion_de_libro AS el, proyecto.contiene AS c, proyecto.tienda AS t
                 WHERE titulo ~* '$marca' AND c.id_tienda = t.id_tienda AND l.id_libro = el.id_libro AND c.isbn = el.isbn ORDER BY l.titulo ASC";
      
      $query7 = "SELECT l.titulo, t.nombre_tienda, c.stock, c.precio_normal, c.precio_oferta, c.link_compra
                 FROM proyecto.libro AS l, proyecto.edicion_de_libro AS el, proyecto.contiene AS c, proyecto.tienda AS t
                 WHERE c.oferta = 'si' AND l.id_libro = el.id_libro AND c.isbn = el.isbn AND c.id_tienda = t.id_tienda ORDER BY l.titulo ASC";
  
      

      $ret1 = pg_query($db, $query1);
      $ret2 = pg_query($db, $query2);
      $ret3 = pg_query($db, $query3);
      $ret4 = pg_query($db, $query4);
      $ret5 = pg_query($db, $query5);
      $ret6 = pg_query($db, $query6);
      $ret7 = pg_query($db, $query7);
      if(!$ret1){
         while($row = pg_fetch_row($ret1)) {
            echo "Nombre = ". $row[0] . "<br>";
            echo "Apellido = ". $row[1] ."<br>";
            echo "Titulo = ". $row[2] ."<br><br>";
            //echo "nacionalidad =  ".$row[3] ."<br>";
            //echo "kilometraje =  ".$row[4] . " kms."."<br><br>";
         }
      }else if(!$ret2){
         while($row = pg_fetch_row($ret2)) {
            echo "Titulo = ". $row[0] ."<br>";
            echo "Nombre Tienda = ". $row[1] . "<br>";
            echo "Stock = ". $row[2] . "<br>";
            echo "Precio Normal = ". $row[3] . "<br>";
            echo "Precio Oferta = ". $row[4] . "<br>";
            echo "Link de Compra = ". $row[5] ."<br><br>"; 
         }
      }else if(!$ret3){
         while($row = pg_fetch_row($ret3)) {
            echo "Titulo = ". $row[0] ."<br>";
            echo "Nombre Tienda = ". $row[1] . "<br>";
            echo "Stock = ". $row[2] . "<br>";
            echo "Precio Normal = ". $row[3] . "<br>";
            echo "Precio Oferta = ". $row[4] . "<br>";
            echo "Link de Compra = ". $row[5] ."<br><br>"; 
         }
      }else if($ret4){
         while($row = pg_fetch_row($ret4)) {
            echo "Titulo = ". $row[0] ."<br>";
            echo "Nombre Tienda = ". $row[1] . "<br>";
            echo "Stock = ". $row[2] . "<br>";
            echo "Precio Normal = ". $row[3] . "<br>";
            echo "Precio Oferta = ". $row[4] . "<br>";
            echo "Link de Compra = ". $row[5] ."<br><br>"; 
         }
      }else if($ret5){
         while($row = pg_fetch_row($ret5)) {
            echo "Titulo = ". $row[0] ."<br>";
            echo "Nombre Tienda = ". $row[1] . "<br>";
            echo "Stock = ". $row[2] . "<br>";
            echo "Precio Normal = ". $row[3] . "<br>";
            echo "Precio Oferta = ". $row[4] . "<br>";
            echo "Link de Compra = ". $row[5] ."<br><br>"; 
         }
      }else if($ret6){
         while($row = pg_fetch_row($ret6)) {
            echo "Titulo = ". $row[0] ."<br>";
            echo "Nombre Tienda = ". $row[1] . "<br>";
            echo "Stock = ". $row[2] . "<br>";
            echo "Link de Compra = ". $row[3] ."<br><br>";
         }
      }else if($ret7){
         while($row = pg_fetch_row($ret7)) {
            echo "Titulo = ". $row[0] ."<br>";
            echo "Nombre Tienda = ". $row[1] . "<br>";
            echo "Stock = ". $row[2] . "<br>";
            echo "Precio Normal = ". $row[3] . "<br>";
            echo "Precio Oferta = ". $row[4] . "<br>";
            echo "Link de Compra = ". $row[5] ."<br><br>"; 
         }
      }else{
         echo pg_last_error($db);
         exit;
      }
      

      pg_close($db);
   }
?> 
