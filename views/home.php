<!doctype html>
<html class="no-js" lang="en" dir="ltr">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="x-ua-compatible" content="ie=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Foundation for Sites</title>
      <link rel="stylesheet" href="css/foundation.css">
      <link rel="stylesheet" href="css/app.css">
   </head>
   <body>
      <div class="grid-container">
         <div class="grid-x grid-padding-x">
            <div class="large-12 cell">
               <h1>Pedidos</h1>
            </div>
         </div>
         <div class="grid-x grid-padding-x">
            <div class="large-12 cell">
               <div class="callout">
                  <h3>Peccatore!</h3>
                  <p>Info sobre los pedidos.</p>
                  <p>Mas detalles:</p>
               </div>
               <div class="grid-x grid-padding-x">
                  <div class="large-4 medium-4 cell">
                     <label>Telefono</label>
                     <input type="text" placeholder="telefono">
                  </div>
                  <div class="large-4 medium-4 cell">
                     <label>Direccion</label>
                     <input type="text" placeholder="direccion">
                  </div>
                  <div class="large-4 medium-4 cell">
                     <label>Peso</label>
                     <input type="radio" name="1/4" value="Blue" id="1/4k"><label for="pokemonRed">1/4 Kilo</label>
                     <input type="radio" name="1/2" value="Blue" id="1/2k"><label for="pokemonBlue">1/2 Kilo</label>
                     <input type="radio" name="1" value="Blue" id="1k"><label for="pokemonBlue">1 Kilo</label>
                     <input type="radio" name="2" value="Blue" id="2k"><label for="pokemonBlue">2 Kilos</label>
                  </div>
               </div>
               <div class="grid-x grid-padding-x">
                 


                <!--Section Cuerpo-->  
                
     <?php

      $pedidos = kappa();
     // $pedidos = \controllers\pedidosController::get()->todos();
      $cantidadDeColumnas = 3;
      $boostrapDivision = 12/$cantidadDeColumnas;
      $contador=0;

      //var_dump($pedidos);
     
      foreach($pedidos as $x => $x_value) 
      {
       if($contador==0)
       {
        echo'<div class="large-12 cell">';
        echo'<div class="grid-x grid-padding-x">';
       }
      $pc =  $x_value["post_title"];
       echo'<div class="large-'.$boostrapDivision.' medium-6 cell">';
      // echo'<div class="md-wishlist" mbsc-form>';
         //Chocolatechocolate amargoFrutilla
        //echo'<div mbsc-page class="demo-wishlist">';
        //echo'<div class="md-wishlist" mbsc-form>';
       // echo'<img src=class="md-wishlist-img">';
       // print("<center>");
        print($x_value["post_title"]);
        print("</ceneter>");
        print("<center>".$x_value['post_content']."</center>");
       // echo'<div class="md-title">'.$x_value["post_title"].'</div>';
        //print('<button id="'.$pc.'" class="mbsc-btn mbsc-btn-block md-wish" "data-icon="plus" onClick="reply_click(this.id)"> <span class="md-wish-text">Agregar gusto</span></button>');
        echo'</div>';//end col
        echo'</div>';//end col
        if($contador==$cantidadDeColumnas)
        {
           echo'</div>';//end row
             echo'</div>';//end large-12 cell
            $contador=$cantidadDeColumnas;
        }
        $contador++;
        
      }
      
  
      ?> 






               </div>
            </div>
         </div>
      </div>
      </div>
      <script src="js/vendor/jquery.js"></script>
      <script src="js/vendor/what-input.js"></script>
      <script src="js/vendor/foundation.js"></script>
      <script src="js/app.js"></script>
   </body>
</html>