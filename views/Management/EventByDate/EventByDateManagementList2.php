<body style="background-image: url('<?=IMG_PATH?>adminBackground.jpg');">
<div class="wrapper">
    <section>
        <table>
            <thead>
                <th>Fecha</th>
                <th>Teatro</th>
                <th>Artistas</th>
                <th>Editar</th>
                <th>Eliminar</th>
            </thead>
            <tbody>
            <?php
            if (!empty($eventByDateList)) {
                foreach ($eventByDateList as $eventByDateItem) {  
            ?>
                <tr>
                    <form method="post">
                        <input type="hidden" name="idEventByDate" value="<?=$eventByDateItem->getIdEventByDate()?>">
                        <?php 
                            echo "<td>".$eventByDateItem->getDate()."</td>";
                            echo "<td>".$eventByDateItem->getTheater()->getTheaterName()."</td>";
                            $artists = $eventByDateItem->getArtists(); //javascript for artists needed here, event for button below "ver"
                            
                            $artistsNames = ""; //temp solution until javascript done
                            foreach ($artists as $artist) {
                                $name = $artist->getName();
                                $lastname = $artist->getLastname();
                                $artistsNames .= $name." ".$lastname.", ";
                            }
                            $artistsNames = rtrim($artistsNames, ", ");
                            echo "<td>".$artistsNames."</td>";
                        ?>
                        <!-- <td><button>Ver</button></td> -->
                        <td>
                            <input type="submit" value="Editar" formaction="<?=FRONT_ROOT?>EventByDateManagement/viewEditCategory"> 
                        </td>
                        <td>
                            <input type="submit" value="Eliminar" formaction="<?=FRONT_ROOT?>EventByDateManagement/deleteCategory">
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
            <button type="submit" formaction="<?=FRONT_ROOT?>EventByDateManagement/index">Volver</button>
        </form>
    </section>
</div>
