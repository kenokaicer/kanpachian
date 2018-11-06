 <div><form method="post">
    <button type="submit" formaction="<?=FRONT_ROOT?>Admin/index">ADMIN</button>
 </form></div>
 <div><form method="post">
    <button type="submit" formaction="<?=FRONT_ROOT?>Account/index">TEST</button>
 </form></div>
 <div><form method="post">
    <button type="submit" formaction="<?=FRONT_ROOT?>Account/registerUser">REGISTER</button>
 </form></div>
 <div id="additional-info">
        <div class="row">
            <div class="large-12 columns">
                <h2 class="color-white headings text-center">Kanpachian!</h2>
            </div>
        </div>
        <div class="row">
            <div class="large-12 columns">
                <img src="<?=IMG_PATH?>mockup.png" alt="mockup" />
            </div>
        </div>
    </div>

    <div id="intro">
        <div class="row">
            <div class="large-6 medium-6 columns">
                <img src="<?=IMG_PATH?>kappa.png" width="200"  alt="logo" />
                <h3 class="color-white">Ingrese con su email y contraseña</h3>
                <h6 class="color-white" style="line-height: 27px;">Lorem ipsum dolor sit amet, consectetur Maecenas Maecenas adipiscing elit.  Maecenas fermentum neque id lobortis consectetur. Integer facilisis diam metus, quis volutpat mauris porta eget.
                </h6>
            </div>
            <div class="large-6 medium-6 columns">
                <div id="sign-up">
                    <h3 class="color-pink">Ingresar</h3>
                    <hr />
                    <label>Nombre de usuario</label>
                    <input id="Text1" type="text" />
                    <label>Contraseña</label>
                    <input id="Text2" type="text" />
                    <button class="blue-btn">Logear</button>
                </div>
            </div>
        </div>
    </div>

     <div id="eventos">

       
    <?php
    $pedidos = \controllers\HomeController::getEventList();
    $cantidadDeColumnas = 3;
    $boostrapDivision = 12/$cantidadDeColumnas;
    $contador=0;
    //var_dump($pedidos);
    foreach($pedidos as $key => $value) 
    {
     if($contador==0)
     {
      echo'<div class="grid-x grid-padding-x">';
     }
     $pc =  $value->getEventName();
     ?>
      <div style="align:center" class="large-<?=$boostrapDivision?> medium-6 cell">
      <img width="200px" id="<?=$value->getIdEvent()?>" onclick="ShowEvent(this.id)" src="<?=$value->getImage()?>">
      <div><?=$pc?></div>
      <input type="submit" class="button" value="Ver" 
      id="<?=$value->getIdEvent()?>" onclick="ShowEvent(this.id)"></div>
      <?php
      
      if($contador==$cantidadDeColumnas)
      {
        ?>
        </div>
           </div>
          
          <?php
                  $contador=$cantidadDeColumnas;

      }
      $contador++;
      ?>
      
      <?php
=======
        if($contador==0){
    ?>
            <div class="grid-x grid-padding-x">
    <?php
        }
        $pc =  $value->getEventName();
    ?>
    <div style="align:center" class="large-<?=$boostrapDivision?> medium-6 cell">
    <img width="200px" id="<?=$value->getIdEvent()?>" onclick="doSomething(this.id)" src="<?=IMG_PATH.$value->getImage()?>">
    <div><?=$pc?></div>
    <input type="submit" class="button" value="Ver" id="<?=$value->getIdEvent()?>" onclick="doSomething(this.id)"></div>
    <?php      
        if($contador==$cantidadDeColumnas){
    ?>
    </div>  
    <?php
          $contador=$cantidadDeColumnas;
        }
        $contador++;
>>>>>>> 29f564ed8a01152278e9dd5d3663076920a88542
    }
    ?> 
</div>
    <div id="pricing">
        <div class="row">
            <div class="large-12 columns">
                <h2 class="text-center color-pink headings">Top Eventos</h2>
            </div>
            <div class="large-4 medium-4 small-12 columns">
                <div class="pricing-title">
                    $99.99
                </div>
                <ul class="pricing-table">
                    <li class="description">An awesome description</li>
                    <li class="bullet-item">1 Database</li>
                    <li class="bullet-item">5GB Storage</li>
                    <li class="bullet-item">20 Users</li>
                    <li class="cta-button"><a class="button" href="#">Buy Now</a></li>
                </ul>
            </div>
            <div class="large-4 medium-4 small-12 columns">
                <div class="pricing-title">
                    $99.99
                </div>
                <ul class="pricing-table">
                    <li class="description">An awesome description</li>
                    <li class="bullet-item">1 Database</li>
                    <li class="bullet-item">5GB Storage</li>
                    <li class="bullet-item">20 Users</li>
                    <li class="cta-button"><a class="button" href="#">Buy Now</a></li>
                </ul>
            </div>
            <div class="large-4 medium-4 small-12 columns">
                <div class="pricing-title">
                    $99.99
                </div>
                <ul class="pricing-table">
                    <li class="description">An awesome description</li>
                    <li class="bullet-item">1 Database</li>
                    <li class="bullet-item">5GB Storage</li>
                    <li class="bullet-item">20 Users</li>
                    <li class="cta-button"><a class="button" href="#">Buy Now</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div id="testimonial">
        <div class="row">
            <div class="large-12 columns">
                <ul class="example-orbit-content" data-orbit>
                    <li data-orbit-slide="headline-1">
                        <div class="text-center">
                            <h6 class="color-white">Detalles</h6>
                            <p class="color-white">Un Texto para agregar cuerpo</p>
                        </div>
                    </li>
                    <li data-orbit-slide="headline-2">
                        <div>
                            <h6 class="color-white">Detalles2</h6>
                            <p class="color-white">Un Texto para agregar cuerpo2</p>
                        </div>
                    </li>
                    <li data-orbit-slide="headline-3">
                        <div>
                            <h6 class="color-white">Detalles3</h6>
                            <p class="color-white">Un texto para agregar cuerpo3</p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <footer>
        

    </footer>
</body>
<!--
<script src="js/vendor/jquery.js"></script>
<script src="js/foundation.min.js"></script>
<script>
 //   $(document).foundation();
</script>
-->

<script>

function ShowEvent(id)
{
    console.log(id);
    //Load("eventos","views/demo.php");
    //$("#eventos").load("EventDetails");
    $("#eventos").load("views/demo.php");
}
</script>
</html>