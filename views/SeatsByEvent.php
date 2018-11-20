<body class="Site"> <!--These two classes are needed for the footer to stick to the bottom of the page-->
<main class="Site-content">

<div id="additional-info" style="padding:0;height: 70px;">
    <div class="row" style="padding:0;height: 70px;">
        <div class="large-12 columns" style="padding:0;height: 70px;">
            <h1 class="shadow-header color-white headings text-center"><?=$event->getEventName()?></h2>
        </div>
    </div>
    
</div>

<?php require VIEWS_PATH."eventHeader.php" ?>

    <div class="row" style="padding:25px 0px 10px 0px;max-width:70vw">
        <?php 
        foreach ($seatsByEventList as $seatsByEvent) {
        ?>
       
       <div style="margin:0 auto;min-width:350px;display:inline-block;margin-left:20px">
            <div class="pricing-title" style="font-size:18px">
                $<?=$seatsByEvent->getPrice()?>
            </div>
            <ul class="pricing-table">
                <li class="description" style="font-size:18px">Tipo de Asiento: <?=$seatsByEvent->getSeatType()->getSeatTypeName()?></li>
                <li class="bullet-item">Descripción: <?=$seatsByEvent->getSeatType()->getDescription()?></li>
                <li class="bullet-item">Disponibilidad: <?=$seatsByEvent->getRemnants()?></li>
                <li class="bullet-item">
                <form action="<?=FRONT_ROOT?>Purchase/addPurchaseLine" method="get">
                <input type="hidden" name="idSeatsByEvent" value="<?=$seatsByEvent->getIdSeatsByEvent()?>">
                <button <?php if($seatsByEvent->getRemnants() <= 0) echo "disabled" ?> style="margin-top:20px">Compre Ahora</button> <!--Check for seat availability-->
                </form>
                </li>
            </ul>
        </div>

        <?php    
        }
        ?>
    </div>
</div>

<?php require VIEWS_PATH."FooterUserView.php";?>