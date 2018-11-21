<body style="background-image: url('<?=IMG_PATH?>adminBackground.jpg');">
<div class="menuWrapper"> 
<h2 style="color:white">Administrador</h2>
<form method="GET">
    <section>
        <div class="menu"><button class="menuButton" type="submit" formaction="<?=FRONT_ROOT?>ArtistManagement/index">Gestión Artistas</button></div>
        <div class="menu"><button class="menuButton" type="submit" formaction="<?=FRONT_ROOT?>TheaterManagement/index">Gestión Teatros</button></div>
        <div class="menu"><button class="menuButton" type="submit" formaction="<?=FRONT_ROOT?>CategoryManagement/index">Gestión Categorías</button></div>
        <div class="menu"><button class="menuButton" type="submit" formaction="<?=FRONT_ROOT?>SeatTypeManagement/index">Gestión Tipos de Asiento</button></div>
        <div class="menu"><button class="menuButton" type="submit" formaction="<?=FRONT_ROOT?>EventManagement/index">Gestión Eventos</button></div>
        <div class="menu"><button class="menuButton" type="submit" formaction="<?=FRONT_ROOT?>EventByDateManagement/index">Gestión Calendarios</button></div>
        <div class="menu"><button class="menuButton" type="submit" formaction="<?=FRONT_ROOT?>SeatsByEventManagement/index">Gestión Asiento por Evento</button></div>
        <div class="menu"><button class="menuButton" type="submit" formaction="<?=FRONT_ROOT?>UserManagement/index">Gestión Usuarios</button></div>
        <div class="menu"><button class="menuButton" type="submit" formaction="<?=FRONT_ROOT?>ClientManagement/index">Gestión Clientes</button></div>
        <div class="menu"><button class="menuButton" type="submit" formaction="<?=FRONT_ROOT?>CheckSalesByEvent/index">Ventas por Evento</button></div>
        <div class="menu"><button class="menuButton" type="submit" formaction="<?=FRONT_ROOT?>CheckSalesByDateOrCategory/index">Ventas por Fecha o Categoría</button></div>
    </section>
</form>
</div>
