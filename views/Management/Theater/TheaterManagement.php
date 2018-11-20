<body style="background-image: url('<?=IMG_PATH?>adminBackground.jpg');">
<div class="menuWrapper">
    <h2 style="color:white">Gestión de Teatros</h2>
    <form method="get">
        <section>
            <div class="menu"><button class="menuButton" type="submit" formaction="<?=FRONT_ROOT?>TheaterManagement/viewAddTheater">Agregar Teatro</button></div>
            <div class="menu"><button class="menuButton" type="submit" formaction="<?=FRONT_ROOT?>TheaterManagement/theaterList">Listar Teatros</button></div>
            <div class="menu"><button class="menuButton" type="submit" formaction="<?=FRONT_ROOT?>Admin">Volver</button></div>
        </section>
    </form>
</div>