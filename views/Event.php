
<div id="additional-info" style="padding:0;height: 70px;">
    <div class="row" style="padding:0;height: 70px;">
        <div class="large-12 columns" style="padding:0;height: 70px;">
            <h1 class="color-white headings text-center"><?=$event->getEventName()?></h2>
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

    <div class="row" style="padding:100px 0px 100px 0px;text-align:center">
        <?php 
        foreach ($theaterArray as $theater) {
        ?> 

        <div style="margin:0 auto;width:220px;display:inline-block;margin-left:20px">
            <div class="product-card">
                <div class="product-card-thumbnail">
                    <span class="faded faded-all">
                        <a href="#"><img class="round-image" src="<?=IMG_PATH.$theater->getImage()?>"/></a>
                    </span>
                </div>
                <h2 class="product-card-title"><a href="#">Ubicación: <?=$theater->getTheaterName()?></a></h2>
                <span class="product-card-desc">Localidad <?=$theater->getLocation()?></span>
                <div class="product-card-colors">
                    <form action="<?=FRONT_ROOT?>Purchase/showEventByDates" method="post">
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