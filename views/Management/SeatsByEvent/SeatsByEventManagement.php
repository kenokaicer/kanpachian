<body style="background-image: url('<?=IMG_PATH?>adminBackground.jpg');">
<div class="menuWrapper">
    <h2 style="color:white">Gestión de Asientos por Evento</h2>
    <form method="get">
        <section>
            <div class="menu"><button class="menuButton" type="submit" formaction="<?=FRONT_ROOT?>SeatsByEventManagement/viewAddSeatsByEvent">Agregar Asiento por Evento</button></div>
            <div class="menu"><button class="menuButton" type="submit" formaction="<?=FRONT_ROOT?>SeatsByEventManagement/seatsByEventList">Listar Asiento por Evento</button></div>
            <div class="menu"><button class="menuButton" type="submit" formaction="<?=FRONT_ROOT?>Admin">Volver</button></div>
        </section>
    </form>
</div>
