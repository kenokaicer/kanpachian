<div class="wrapper">
    <section>
        <form action="<?=FRONT_ROOT?>EventByDateManagement/addEventByDate" method="post">
            <table>
                <tr>
                    <td colspan="2">Evento:
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
                    <td>Fecha: <input type="date" name="date"></td>
                    <td>Teatro:
                        <select name="idTheater">
                            <?php
                                foreach ($theaterList as $value) {
                            ?>
                                <option value="<?=$value->getIdTheater()?>"><?=$value->getName().", maxCap: ".$value->getMaxCapacity()?></option>      
                            <?php
                                }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">Artista: 
                        <select name="idArtist">
                            <?php
                                foreach ($artistList as $value) {
                            ?>
                                <option value="<?=$value->getIdArtist()?>"><?=$value->getName()." ".$value->getLastname()?></option>      
                            <?php
                                }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">Lista de artistas seleccionados</td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div>
                            <button type="submit">Agregar</button>
                            <input class="button" type="submit" value="Volver" formaction="<?=FRONT_ROOT?>EventByDateManagement/index" formnovalidate>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </section> 
</div>