<body style="background-color: #f6f6f6" class="Site"> <!--These two classes are needed for the footer to stick to the bottom of the page-->
<main class="Site-content">

<div id="additional-info" style="padding:0;height: 70px;">
    <div class="row" style="padding:0;height: 70px;">
        <div class="large-12 columns" style="padding:0;height: 70px;">
            <h1 class="shadow-header color-white headings text-center"><?=strftime("%d de %B, de %G",strtotime($date))?></h1>
        </div>
    </div>   
</div>

<div id="pricing">
    <div class="row" style="padding:20px 0px 100px 0px;text-align:center">
        <?php 
        foreach ($eventByDateList as $eventByDate) {
        ?> 
        
        <div style="margin:0 auto;width:220px;display:inline-block;margin-left:20px;vertical-align:text-top;position:relative">
            <div class="product-card" style="min-height:372px">
                <div class="product-card-thumbnail" style="width:180px;display:inline-block;margin-bottom:5px">
                    <img stlye="width:250px" src="<?=IMG_PATH.$eventByDate->getEvent()->getImage()?>"/>
                </div>
                <h2 style="margin-top:5px;font-size:20px" class="product-card-title"><a><?=$eventByDate->getEvent()->getEventName()?></a></h2>
                <h2 class="product-card-title"><a><?=$eventByDate->getTheater()->getTheaterName()?></a></h2>
                <h2 class="product-card-title"><a>Fecha: <?=date($eventByDate->getDate())?></a></h2>
                <span style="margin-top:15px" class="product-card-desc">Artistas: 
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
            <div style="position:absolute; bottom:16px; left: 35px" class="product-card-colors">
                <form action="<?=FRONT_ROOT?>Purchase/showSeatsByEvent" method="get">
                <input type="hidden" name="idEvent" value="<?=$eventByDate->getEvent()->getIdEvent()?>">
                <input type="hidden" name="idTheater" value="<?=$eventByDate->getTheater()->getIdTheater()?>">
                <input type="hidden" name="idEventByDate" value="<?=$eventByDate->getIdEventByDate();?>">
                <button >Ver Asientos</button>
                </form>
            </div> 
        </div> 

        <?php    
        }
        ?>
        
    </div>
</div>

<?php require VIEWS_PATH."FooterUserView.php";?>