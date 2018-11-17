
<form method="get">
<div>
    <button type="submit" formaction="<?=FRONT_ROOT?>Admin/index">ADMIN</button>
</div>
 <div>
    <button type="submit" formaction="<?=FRONT_ROOT?>Account/index">LOGIN</button>
</div>
 <div>
    <button type="submit" formaction="<?=FRONT_ROOT?>Account/registerUser">REGISTER</button>
</div>
 <div>
    <button type="submit" formaction="<?=FRONT_ROOT?>Purchase/viewCart">CART</button>
</div>
 <div>
    <button type="submit" formaction="<?=FRONT_ROOT?>Account/viewRegisterCreditCard">Credit Card</button>
</div>
 <div>
    <button type="submit" formaction="<?=FRONT_ROOT?>Purchase/showTickets">ticket</button>
</div>

</form>
 <div id="additional-info" style="padding-top:5px;height:70px">
        <div class="row">
            <div class="large-12 columns">
                <h2 class="color-white headings text-center">Kanpachian!</h2>
            </div>
        </div>
    </div>

    <div id="intro">
        <div class="row">
            <div class="wrapper" style="border:none">
                <img src="<?=IMG_PATH?>kappa.png" width="200"  alt="logo" />
                <h3 class="color-white">Buscador</h3>
                <h6 class="color-white" style="line-height: 27px;">Buscador
                </h6>
            </div>
            
        </div>
    </div>

<div id="features">
    <div class="wrapper" style="border:none">
        <?php
        $pedidos = \controllers\HomeController::getEventList();
        $cantidadDeColumnas = 3;
        $boostrapDivision = 12/$cantidadDeColumnas;
        $contador=0;
        //var_dump($pedidos);
        foreach($pedidos as $key => $value) 
        {
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
        <form action="<?=FRONT_ROOT?>Purchase/index" method="post">
        <input type="hidden" name="idEvent" value="<?=$value->getIdEvent()?>">
        <input type="submit" class="button" value="Ver" id="<?=$value->getIdEvent()?>"></div>
        </form>
        <?php      
            if($contador==$cantidadDeColumnas){
        ?>
                </div>  
        <?php
                $contador=$cantidadDeColumnas;
            }
            $contador++;
        }
        ?> 
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
    
<!--
<script src="js/vendor/jquery.js"></script>
<script src="js/foundation.min.js"></script>
<script>
 //   $(document).foundation();
</script>
-->

<script>
function doSomething(id)
{
    console.log(id);
}
</script>
</html>