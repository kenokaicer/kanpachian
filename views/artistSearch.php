<body class="Site"> <!--These two classes are needed for the footer to stick to the bottom of the page-->
<main class="Site-content">
    
<div id="additional-info" style="padding-top:5px;height:70px">
        <div class="row">
            <div class="large-12 columns">
                <h2 class="color-white headings text-center">Kanpachian!</h2>
            </div>
        </div>
    </div>

    <div id="intro"> <!-- https://foundation.zurb.com/building-blocks/index.html -->
        <div class="row">
            <div class="wrapper" style="border:none">
            <form action="<?=FRONT_ROOT?>Purchase/searchByArtist" method="post">
            <div class="input-group-rounded">
                <div style="float:left">
                    <input class="animated-search-form input-group-field" name="q" type="search" placeholder="Busque por Artista..">
                </div> 
                <div class="input-group-button" style="float:left;height:37px">
                    <input type="submit" class="button secondary" value="Buscar">
                </div>
            </div>
            </form>
                <img src="<?=IMG_PATH?>kappa.png" width="200"  alt="logo" />
                <h3 class="color-white">Buscador</h3>
                <h6 class="color-white" style="line-height: 27px;">Buscador
                </h6>
            </div>
        </div>
    </div>

<div id="features">
    <div class="wrapper" style="border:none">
        <div class="polls">
            <h5 class="polls-question">
                <span class="polls-question-label">O</span>
                Más de un resultado econtrado, seleccione artista
            </h5>
            <div class="polls-options">
                <form action="" method="post">
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
    </div>
</div>

<?php require VIEWS_PATH."FooterUserView.php";?>