<body style="background-image: url('<?=IMG_PATH?>adminBackground.jpg');">
<div class="menuWrapper">
    <h2 style="color:white">Gestión de Calendarios</h2>
    <form method="get">
        <section>
            <div class="menu"><button class="menuButton" type="submit" formaction="<?=FRONT_ROOT?>EventByDateManagement/viewAddEventByDate">Agregar Calendario</button></div>
            <div class="menu"><button class="menuButton" type="submit" formaction="<?=FRONT_ROOT?>EventByDateManagement/eventByDateList">Listar Calendarios</button></div>
            <div class="menu"><button class="menuButton" type="submit" formaction="<?=FRONT_ROOT?>Admin">Volver</button></div>
        </section>
    </form>
</div>
