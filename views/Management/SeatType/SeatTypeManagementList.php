<body style="background-image: url('<?=IMG_PATH?>adminBackground.jpg');">
<div class="wrapper">
    <section>
        <table>
            <thead>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Editar</th>
                <th>Eliminar</th>
            </thead>
            <tbody>
            <?php
            if (!empty($seatTypeList)) {
                foreach ($seatTypeList as $seatType) {  
                    $seatTypeValuesArray = $seatType->getAll();
            ?>
                <tr>
                    <form method="post">
                        <?php 
                            foreach ($seatTypeValuesArray as $attribute => $value) { //print all attributes from object in a td each
                                if($attribute=="idSeatType"){
                        ?>
                                    <input type="hidden" name="idSeatType" value="<?=$value?>">
                        <?php 
                                }else{
                                    echo "<td>";
                                    echo $value;
                                    echo "</td>";
                                }
                            }
                        ?>
                        <td>
                            <input type="submit" value="Editar" formaction="<?=FRONT_ROOT?>SeatTypeManagement/viewEditSeatType"> 
                        </td>
                        <td>
                            <input type="submit" value="Eliminar" formaction="<?=FRONT_ROOT?>SeatTypeManagement/deleteSeatType">
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
            <button type="submit" formaction="<?=FRONT_ROOT?>SeatTypeManagement/index">Volver</button>
        </form>
    </section>
</div>
