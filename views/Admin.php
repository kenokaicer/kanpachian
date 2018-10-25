<?php
?>
<div class="wrapper"> 
<h3>Temprary Admin view</h3>
<form method="POST">
    <section>
        <div><button type="submit" formaction="<?=FRONT_ROOT?>ArtistManagement/index">Gestión Artistas</button></div>
        <div><button type="submit" formaction="<?=FRONT_ROOT?>TheaterManagement/index">Gestión Teatros</button></div>
    </section>
    <section>
        <div><button type="submit" formaction="<?=FRONT_ROOT?>Main/index">Volver (temp)</button></div>
    </section>
</form>
</div>