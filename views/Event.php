<body style="background-color: #fafafa;" class="Site"> <!--These two classes are needed for the footer to stick to the bottom of the page-->
<main class="Site-content">

<div id="additional-info" style="padding:0;height: 70px;">
    <div class="row" style="padding:0;height: 70px;">
        <div class="large-12 columns" style="padding:0;height: 70px;">
            <h1 class="shadow-header color-white headings text-center"><?=$event->getEventName()?></h2>
        </div>
    </div>
    
</div>

<?php require VIEWS_PATH."eventHeader.php" ?>

    <div class="row" style="padding:30px 0px 30px 0px;text-align:center">
        <?php 
        foreach ($theaterArray as $theater) {
        ?> 

        <div style="margin:0 auto;width:220px;display:inline-block;margin-left:20px;">
            <div class="product-card">
                <div class="product-card-thumbnail">
                    <span class="faded faded-all">
                        <a><img class="round-image" src="<?=IMG_PATH.$theater->getImage()?>"/></a>
                    </span>
                </div>
                <a class="product-card-title" style="margin-top:5px">Ubicación:</a>
                <h2 class="product-card-title" style="margin-top:0"><a><?=$theater->getTheaterName()?></a></h2>
                <span class="product-card-desc">Localidad <?=$theater->getLocation()?></span>
                <div class="product-card-colors">
                    <form action="<?=FRONT_ROOT?>Purchase/showEventByDates" method="get">
                    <input type="hidden" name="idEvent" value="<?=$event->getIdEvent()?>">
                    <input type="hidden" name="idTheater" value="<?=$theater->getIdTheater();?>">
                    <button >Ver Fechas</button>
                    </form>
                </div>
            </div> 
        </div>   

        <?php    
        }
        ?>
        
    </div>
</div>

<?php require VIEWS_PATH."FooterUserView.php";?>