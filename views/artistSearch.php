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


    <div id="testimonial">
        <div class="row">
            <div class="large-12 columns">
                <ul class="example-orbit-content" data-orbit>
                    <li data-orbit-slide="headline-1">
                        <div class="text-center">
                            <h6 class="color-white">Detalles</h6>
                            <p class="color-white">Un Texto para agregar cuerpo</p>
                        </div>
                    </li>
                    <li data-orbit-slide="headline-2">
                        <div>
                            <h6 class="color-white">Detalles2</h6>
                            <p class="color-white">Un Texto para agregar cuerpo2</p>
                        </div>
                    </li>
                    <li data-orbit-slide="headline-3">
                        <div>
                            <h6 class="color-white">Detalles3</h6>
                            <p class="color-white">Un texto para agregar cuerpo3</p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    
<!--
<script src="js/vendor/jquery.js"></script>
<script src="js/foundation.min.js"></script>
<script>
 //   $(document).foundation();
</script>
-->

<script>
function doSomething(id)
{
    console.log(id);
}
</script>
</html>