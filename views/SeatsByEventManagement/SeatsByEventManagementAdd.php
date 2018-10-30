<div class="wrapper">
    <section>
        <form action="<?=FRONT_ROOT?>SeatsByEventManagement/addSeatsByEvent" method="post">
            <table>
                <tr>
                    <td colspan="3">Evento:
                        <select name="idEvent">
                            <?php
                                foreach ($eventList as $value) {
                            ?>
                                <option value="<?=$value->getIdEvent()?>"><?=$value->getEventName().", Categoría: ".$value->getCategory()->getCategoryName()?></option>      
                            <?php
                                }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">Calendario:
                        <select name="idEventByDate">
                            <?php
                                foreach ($eventByDateList as $value) { ////////javascritp event on "Evento" changed
                            ?>
                                <option value="<?="Calendario"?>"><?="Calendario"?></option>      
                            <?php
                                }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <?php
                        foreach ($seatTypeList as $value) { ////////javascritp event on "Calendario" changed, should put a line for each seatType the theater inside EventByDate has
                    ?>
                        <td>
                            <select name="idSeatType">
                                <?php
                                    foreach ($seatTypeList as $value) {
                                ?>
                                    <option value="<?="seatType_ID"?>"><?= "tipo asiento "?></option>      
                                <?php
                                    }
                                ?>
                            </select>
                        </td>
                        <td>Cantidad: <input type="number" name="quantity"></td> <!--This should be replaced with javascrit, sends an array of quantity and prices -->
                        <td>Precio: <input type="number" name="price"></td>
                    <?php
                        }
                    ?>
                </tr>
                <tr>
                    <td colspan="3">
                        <div>
                            <button type="submit">Agregar</button>
                            <input class="button" type="submit" value="Volver" formaction="<?=FRONT_ROOT?>SeatsByEventManagement/index" formnovalidate>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </section> 
</div>