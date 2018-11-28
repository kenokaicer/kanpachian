<body class="Site"> <!--These two classes are needed for the footer to stick to the bottom of the page-->
<main class="Site-content">

<div id="additional-info" style="padding-top:5px;height:70px">
    <div class="row">
        <div class="large-12 columns">
            <h2 class="shadow-header color-white headings text-center">Go To Event</h2>
        </div>
    </div>
</div>

<div id="intro" style="min-height:67.25vh">
    <div class="row" style="text-align:center">
        <div class="wrapper" style="border:none;display:inline-block">
            <form action="<?=FRONT_ROOT?>Purchase/searchByArtist" method="get">
                <div class="input-group-rounded" style="display:inline-block">
                    <div style="float:left">
                        <input style="min-width:200px" class="animated-search-form input-group-field" name="q" type="search" placeholder="Busque por Artista.." required>
                    </div> 
                    <div class="input-group-button" style="float:left;height:37px">
                        <input type="submit" class="button secondary" value="Buscar">
                    </div>
                </div>
            </form>
        </div>
        <div class="wrapper" style="border:none;display:inline-block">
            <div class="date-picker">
	            <div class="input">
                <form action="<?=FRONT_ROOT?>Purchase/showEventByDate" method="get">
                  <input class="result" id="myDate" placeholder="Busque por Fecha" class="textbox-n" type="text" onfocus="(this.type='date')" name="date" required>
                  <button type="submit"><i class="fa fa-calendar"></i></button>
                </form>
	            </div>
        </div>
    </div>
    <div class="row">
    <div class="wrapper" style="border:none;width:960px">
        <?php //Responsive columns not working right, use responsive element sorting of event, or evenyByDate views
        $columnQuantity = sizeof($eventList); 
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
        ?>
        <div style="background: url(<?=IMG_PATH.$event->getImage()?>) 50% 50% no-repeat;" class="event-image large-<?=$boostrapDivision?> medium-6 cell">
        <div class="event-title" ><strong><?=$event->getEventName()?></strong></div>
        <a id="a<?=$i?>" href="<?=FRONT_ROOT."Purchase/index?idEvent=".$event->getIdEvent()?>">
        <div class="event-clickable-area"></div></a>
        </div>
        
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
</div>

<?php require VIEWS_PATH."FooterUserView.php";?>

<script>

</script>
