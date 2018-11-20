<body style="background-image: url('<?=IMG_PATH?>adminBackground.jpg');">
<div class="wrapper">
    <form method="post">
        <section>
            <h3>Seleccionar Evento</h3>
            <select name="idEvent">
                <?php
                    foreach ($eventList as $value) {
                ?>
                    <option value="<?=$value->getIdEvent()?>"><?=$value->getEventName()?></option>      
                <?php
                    }
                ?>
            </select>
        </section>
        <section>
            <button class="button" type="submit" formaction="<?=FRONT_ROOT?>EventByDateManagement/eventByDateList2">Continuar</button>
            <button class="button" type="submit" formaction="<?=FRONT_ROOT?>EventByDateManagement/index">Volver</button>
        </section>
    </form>
</div>
