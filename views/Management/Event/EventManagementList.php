<div class="wrapper">
    <section>
        <table>
            <thead>
                <th>Nombre</th>
                <th>Imágen</th>
                <th>Descripción</th>
                <th>Categoría</th>
                <th>Eliminar</th>
            </thead>
            <tbody>
            <?php
            if (!empty($eventList)) {
                foreach ($eventList as $event) {  
                    $eventValuesArray = $event->getAll();
                    array_pop($eventValuesArray);
                    array_pop($eventValuesArray);
            ?>
                <tr>
                    <form method="post">
                        <?php 
                            foreach ($eventValuesArray as $atribute => $value) { //print all atributes from object in a td each
                                if($atribute=="idEvent"){
                        ?>
                                    <input type="hidden" name="idEvent" value="<?=$value?>">
                        <?php 
                                }else{
                                    echo "<td>";
                                    echo $value;
                                    echo "</td>";
                                }
                            }
                        ?>
                        <td>
                            <?=$event->getCategory()->getCategoryName();?>
                        </td>
                        <td>
                            <input type="submit" value="Editar" formaction="<?=FRONT_ROOT?>EventManagement/viewEditEvent"> 
                        </td>
                        <td>
                            <input type="submit" value="Eliminar" formaction="<?=FRONT_ROOT?>EventManagement/deleteEvent">
                        </td>
                    </form>
                </tr>
            <?php
                }
            }
            ?>
            </tbody>
        </table>
    </section>
    <section>
        <form method="post">
            <button type="submit" formaction="<?=FRONT_ROOT?>EventManagement/index">Volver</button>
        </form>
    </section>
</div>
