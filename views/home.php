
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
<div>
    <button type="submit" formaction="<?=FRONT_ROOT?>Home/test2">confirm purchase</button>
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

<?php require VIEWS_PATH."FooterUserView.php";?>