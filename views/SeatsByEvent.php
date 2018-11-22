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
        $i=0;
        foreach ($seatsByEventList as $seatsByEvent) {
        ?>
       
       <div style="margin:0 auto;min-width:350px;display:inline-block;margin-left:20px">
            <div class="pricing-title" style="font-size:18px">
                $<?=$seatsByEvent->getPrice()?>
            </div>
            <ul class="pricing-table" style="background-color:white">
                <li class="description" style="font-size:18px">Tipo de Asiento: <?=$seatsByEvent->getSeatType()->getSeatTypeName()?></li>
                <li class="bullet-item">Descripción: <?=$seatsByEvent->getSeatType()->getDescription()?></li>
                <li class="bullet-item">Disponibilidad: <?=$seatsByEvent->getRemnants()?></li>
                <li class="bullet-item" style="display:inline-block;padding-bottom:0;padding-top:0">
                <form action="<?=FRONT_ROOT?>Purchase/addPurchaseLine" method="get">
                    <div class="input-group plus-minus-input" style="margin: 0 auto">
                        <div class="input-group-button">
                            <button type="button" class="btn-minus-plus button hollow circle" data-quantity="minus" data-field="quantity<?=$i?>">
                            <i class="fa fa-minus" aria-hidden="true"></i>
                            </button>
                        </div>
                        <input style="width:80px;margin-top:15px" class="input-group-field" type="number" name="quantity<?=$i?>" value="1" max="<?=$seatsByEvent->getRemnants()?>" required>
                        <div class="input-group-button">
                            <button type="button" class="btn-minus-plus button hollow circle" data-quantity="plus" data-field="quantity<?=$i?>">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                </li>
                <li class="bullet-item" style="padding-top:0px;padding-bottom:0px">
                    <input type="hidden" name="idSeatsByEvent" value="<?=$seatsByEvent->getIdSeatsByEvent()?>">
                    <button <?php if($seatsByEvent->getRemnants() <= 0) echo "disabled" ?> style="margin-top:20px">Compre Ahora</button> <!--Check for seat availability-->
                </form>
                </li>
            </ul>
        </div>

        <?php
        $i++;  
        }
        ?>
    </div>
</div>

<?php require VIEWS_PATH."FooterUserView.php";?>

<style>
.btn-minus-plus{
    height:40px !important;
}

.plus-minus-input {
  -webkit-align-items: center;
      -ms-flex-align: center;
          align-items: center;
}

.plus-minus-input .input-group-field {
  text-align: center;
  margin-left: 0.5rem;
  margin-right: 0.5rem;
  padding: 1rem;
}

.plus-minus-input .input-group-field::-webkit-inner-spin-button,
.plus-minus-input .input-group-field ::-webkit-outer-spin-button {
  -webkit-appearance: none;
          appearance: none;
}

.plus-minus-input .input-group-button .circle {
  border-radius: 50%;
  padding: 0.25em 0.8em;
}


</style>

<script>
jQuery(document).ready(function(){
    // This button will increment the value
    $('[data-quantity="plus"]').click(function(e){
        // Stop acting like a button
        e.preventDefault();
        // Get the field name
        fieldName = $(this).attr('data-field');
        // Get its current value
        var currentVal = parseInt($('input[name='+fieldName+']').val());
        // If is not undefined
        if (!isNaN(currentVal)) {
            // Increment
            $('input[name='+fieldName+']').val(currentVal + 1);
        } else {
            // Otherwise put a 0 there
            $('input[name='+fieldName+']').val(0);
        }
    });
    // This button will decrement the value till 1
    $('[data-quantity="minus"]').click(function(e) {
        // Stop acting like a button
        e.preventDefault();
        // Get the field name
        fieldName = $(this).attr('data-field');
        // Get its current value
        var currentVal = parseInt($('input[name='+fieldName+']').val());
        // If it isn't undefined or its greater than 1
        if (!isNaN(currentVal) && currentVal > 1) {
            // Decrement one
            $('input[name='+fieldName+']').val(currentVal - 1);
        } else {
            // Otherwise put a 1 there
            $('input[name='+fieldName+']').val(1);
        }
    });
});


</script>