<body class="Site"> <!--These two classes are needed for the footer to stick to the bottom of the page-->
<main class="Site-content">

<form method="get">
<div>
    <button type="submit" formaction="<?=FRONT_ROOT?>Admin/index">ADMIN</button>
</div>
<div>
    <button type="submit" formaction="<?=FRONT_ROOT?>Home/test">SEARCH</button>
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
        $columnQuantity = 3;
        $boostrapDivision = 12/$columnQuantity;
        $i=0;
        //var_dump($eventList);
        foreach($eventList as $event) 
        {
            if($i==0){
        ?>
                <div class="grid-x grid-padding-x">
        <?php
            }
            $pc =  $event->getEventName();
        ?>
        <div style="align:center" class="large-<?=$boostrapDivision?> medium-6 cell">
        <img width="200px" id="<?=$event->getIdEvent()?>" onclick="doSomething(this.id)" src="<?=IMG_PATH.$event->getImage()?>">
        <div><?=$pc?></div>
        <form action="<?=FRONT_ROOT?>Purchase/index" method="post">
        <input type="hidden" name="idEvent" value="<?=$event->getIdEvent()?>">
        <input type="submit" class="button" value="Ver" id="<?=$event->getIdEvent()?>"></div>
        </form>
        <?php      
            if($i==$columnQuantity){
        ?>
                </div>  
        <?php
                $i=$columnQuantity;
            }
            $i++;
        }
        ?> 
    </div>
</div>

<?php require VIEWS_PATH."FooterUserView.php";?>