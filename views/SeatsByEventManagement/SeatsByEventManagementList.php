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
            <button type="submit" formaction="<?=FRONT_ROOT?>SeatsByEventManagement/seatsByEventList2">Continuar</button>
            <button type="submit" formaction="<?=FRONT_ROOT?>SeatsByEventManagement/index">Volver</button>
        </section>
    </form>
</div>
