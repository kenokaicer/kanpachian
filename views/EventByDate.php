
<div id="additional-info" style="padding:0">
    <div class="row">
        <div class="large-12 columns">
            <h1 class="color-white headings text-center"><?=$event->getEventName()?></h2>
        </div>
    </div>
    
</div>

<!--<div class="large-12 columns">
        <img src="<?=IMG_PATH.$event->getImage()?>" alt="mockup" />
    </div>-->
<div style="height:500px" id="intro">
<div class="large-12 columns">
        <img style="width:65%" src="<?=IMG_PATH.$event->getImage()?>" alt="mockup" />
    </div>
</div>

<div id="pricing">
    <div class="row">
        <div class="large-12 columns">
            <h2 class="text-center color-pink headings"><?=$event->getEventName()?></h2>
        </div>
        <div class="large-12 columns"><p><?=$event->getDescription()?></p></div>
    </div>
    <div class="row" style="padding:100px 0px 100px 0px">
        <?php 
        foreach ($seatsByEventList as $seatsByEvent) {
        ?>
       
        <div class="large-4 medium-4 small-12 columns">
            <div class="pricing-title">
                $<?=$seatsByEvent->getPrice()?>
            </div>
            <ul class="pricing-table">
                <li class="description">Tipo de Asiento: <?=$seatsByEvent->getSeatType()->getSeatTypeName()?></li>
                <li class="bullet-item">Descripción: <?=$seatsByEvent->getSeatType()->getDescription()?></li>
                <li class="bullet-item">Disponibilidad: <?=$seatsByEvent->getRemnants()?></li>
                <button>Compre Ahora</button>
            </ul>
        </div>

        <?php    
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