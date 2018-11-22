<!--<body class="Site"> <!--These two classes are needed for the footer to stick to the bottom of the page-->
<!--<main class="Site-content">-->
    
<div id="additional-info" style="padding-top:5px;height:70px">
        <div class="row">
            <div class="large-12 columns">
                <h2 class="color-white headings text-center">Kanpachian!</h2>
            </div>
        </div>
    </div>

<div id="intro" style="min-height:67.25vh">
    <div class="row" style="text-align:center">
        <div class="wrapper" style="border:none;display:inline-block">
        <section class="app-feature-section" style="border:none;border-radius: 25px;">
        <div class="polls">
            <h5 class="polls-question">
                <span class="polls-question-label">o</span>
                Más de un resultado econtrado, seleccione artista
            </h5>
            <div class="polls-options">
                <form action="<?=FRONT_ROOT?>Purchase/showEventByDatesByArtist" method="post">
                <?php 
                $i=0;
                foreach ($artistList as $artist) {
                ?>
                    <div>
                        <input type="radio" name="artist" id="radio<?=$i?>" value="<?=$artist->getIdArtist()?>" <?php if($i==0)echo "checked='checked'"?> required>
                        <label for="radio<?=$i?>"> <?=$artist->getName()." ".$artist->getLastname() ?></label>
                    </div>
                <?php
                $i++;
                }
                ?>
                <input style="margin-top:20px" class="button" type="submit" value="Buscar">
                </form>
            </div>
        </div>
        </section>
        </div>
    </div>
</div>

<?php require VIEWS_PATH."FooterUserView.php";?>