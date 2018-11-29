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

    <div class="row" style="padding:20px 0px 100px 0px;text-align:center">
        <?php 
        foreach ($eventByDateList as $eventByDate) {
        ?> 
        
        <div style="margin:0 auto;width:220px;display:inline-block;margin-left:20px; vertical-align:text-top;position:relative">
            <div class="product-card" style="min-height:270px">
                <div class="product-card-thumbnail" style="width:50px;display:inline-block">
                    <img src="<?=IMG_PATH."calendar.png"?>"/><img style="position:absolute;left:8px;up:1px;width:44px;<?php if($eventByDate->getIsSale()==0) echo "visibility: hidden;" ?>" src="<?=IMG_PATH."Sale-Ribbon.png"?>"/><img>
                </div>
                <h2 class="product-card-title"><a>Fecha: <?=date($eventByDate->getDate())?></a><br>
                <span style="color:#4fa0ea"><?php if($eventByDate->getIsSale()==1) echo "Hasta: ".date($eventByDate->getEndPromoDate())?></span>
                </h2>
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
            </div> 
            <div style="position:absolute; bottom:25px; left: 35px" class="product-card-colors">
                <form action="<?=FRONT_ROOT?>Purchase/showSeatsByEvent" method="get">
                <input type="hidden" name="idEvent" value="<?=$event->getIdEvent()?>">
                <input type="hidden" name="idTheater" value="<?=$theater->getIdTheater()?>">
                <input type="hidden" name="idEventByDate" value="<?=$eventByDate->getIdEventByDate();?>">
                <button>Ver Asientos</button>
                </form>
            </div>
        </div> 

        <?php    
        }
        ?>
        
    </div>
</div>

<?php require VIEWS_PATH."FooterUserView.php";?>