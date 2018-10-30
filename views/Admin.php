<?php
?>
<div class="wrapper"> 
<h3>Administrador</h3>
<form method="POST">
    <section>
        <div><button style="width: 100%" type="submit" formaction="<?=FRONT_ROOT?>ArtistManagement/index">Gestión Artistas</button></div>
        <div><button style="width: 100%" type="submit" formaction="<?=FRONT_ROOT?>TheaterManagement/index">Gestión Teatros</button></div>
        <div><button style="width: 100%" type="submit" formaction="<?=FRONT_ROOT?>CategoryManagement/index">Gestión Categorías</button></div>
        <div><button style="width: 100%" type="submit" formaction="<?=FRONT_ROOT?>SeatTypeManagement/index">Gestión Tipos de Asiento</button></div>
        <div><button style="width: 100%" type="submit" formaction="<?=FRONT_ROOT?>EventManagement/index">Gestión Eventos</button></div>
        <div><button style="width: 100%" type="submit" formaction="<?=FRONT_ROOT?>EventByDateManagement/index">Gestión Calendarios</button></div>
        <div><button style="width: 100%" type="submit" formaction="<?=FRONT_ROOT?>UserManagement/index">Gestión Usuarios</button></div>
    </section>
    <section>
        <div><button type="submit" formaction="<?=FRONT_ROOT?>Main/index">Volver (temp)</button></div>
    </section>
    Mirá papá esos botones
</form>
</div>