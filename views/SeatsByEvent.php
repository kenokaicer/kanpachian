<body class="Site"> <!--These two classes are needed for the footer to stick to the bottom of the page-->
<main class="Site-content">

<div id="additional-info" style="padding:0;height: 70px;">
    <div class="row" style="padding:0;height: 70px;">
        <div class="large-12 columns" style="padding:0;height: 70px;">
            <h1 class="shadow-header color-white headings text-center"><?=$event->getEventName()?></h2>
        </div>
    </div>
    
</div>

<div style="display: inline-block;" id="intro">
    <div class="large-12 columns">
        <img class="event-top-img round-image" src="<?=IMG_PATH.$event->getImage()?>" alt="mockup" />
    </div>
</div>

<div id="pricing">
    <div class="login-box" style="background-color: #f6f6f6;width:65%;margin:0 auto;padding-bottom:25px">
        <div class="row">
            <div class="large-12 columns">
                <h2 class="text-center color-pink headings" style="padding:0px;margin:0"><?=$event->getEventName()?></h2>
                <h5 class="color-pink" style="margin-bottom:15px"><?=$event->getCategory()->getCategoryName()?></h5>
            </div>
            <div class="large-12 columns"><p><?=$event->getDescription()?></p></div>
        </div>
    </div>
    <div class="login-box" style="background-color: #f6f6f6;width:30%;margin:0 auto;padding:0px;margin-top:15px;overflow: visible">     
        <h3 class="text-center color-pink headings"><?=$theater->getTheaterName()?></h2>
    </div>

    <div class="row" style="padding:50px 0px 100px 0px">
        <?php 
        foreach ($seatsByEventList as $seatsByEvent) {
        ?>
       
        <div class="large-4 medium-4 small-12 columns">
            <div class="pricing-title" style="font-size:18px">
                $<?=$seatsByEvent->getPrice()?>
            </div>
            <ul class="pricing-table">
                <li class="description" style="font-size:18px">Tipo de Asiento: <?=$seatsByEvent->getSeatType()->getSeatTypeName()?></li>
                <li class="bullet-item">Descripción: <?=$seatsByEvent->getSeatType()->getDescription()?></li>
                <li class="bullet-item">Disponibilidad: <?=$seatsByEvent->getRemnants()?></li>
                <form action="<?=FRONT_ROOT?>Purchase/addPurchaseLine" method="post">
                <input type="hidden" name="idSeatsByEvent" value="<?=$seatsByEvent->getIdSeatsByEvent()?>">
                <button <?php if($seatsByEvent->getRemnants() <= 0) echo "disabled" ?> style="margin-top:20px">Compre Ahora</button> <!--Check for seat availability-->
                </form>
            </ul>
        </div>

        <?php    
        }
        ?>
    </div>
</div>

<?php require VIEWS_PATH."FooterUserView.php";?>