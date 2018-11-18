<body class="Site"> <!--These two classes are needed for the footer to stick to the bottom of the page-->
<main class="Site-content">

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
    <div class="login-box" style="background-color: #f6f6f6;width:30%;margin:0 auto;padding:0px;margin-top:15px;overflow: visible">     
        <h3 class="text-center color-pink headings"><?=$theater->getTheaterName()?></h2>
    </div>

    <div class="row" style="padding:20px 0px 100px 0px;text-align:center">
        <?php 
        foreach ($eventByDateList as $eventByDate) {
        ?> 
        
        <div style="margin:0 auto;width:220px;display:inline-block;margin-left:20px">
            <div class="product-card">
                <div class="product-card-thumbnail" style="width:50px;display:inline-block">
                    <img src="<?=IMG_PATH."calendar.png"?>"/>
                </div>
                <h2 class="product-card-title"><a href="#">Fecha: <?=date($eventByDate->getDate())?></a></h2>
                <span class="product-card-desc">Artistas: 
                <?php 
                $artistList = $eventByDate->getArtists();
                $stringArtistas = "";
                foreach ($artistList as $artist) {
                    $stringArtistas .= $artist->getName()." ".$artist->getLastname().", "; 
                }
                $stringArtistas = rtrim($stringArtistas, ", ");
                echo $stringArtistas;
                ?>
                </span>
                <div class="product-card-colors">
                    <form action="<?=FRONT_ROOT?>Purchase/showSeatsByEvent" method="post">
                    <input type="hidden" name="idEvent" value="<?=$event->getIdEvent()?>">
                    <input type="hidden" name="idTheater" value="<?=$theater->getIdTheater()?>">
                    <input type="hidden" name="idEventByDate" value="<?=$eventByDate->getIdEventByDate();?>">
                    <button >Ver Asientos</button>
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