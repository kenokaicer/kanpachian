<body style="background-image: url('<?=IMG_PATH?>adminBackground.jpg');">
<div class="wrapper">
    <section>
        <table>
            <thead>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Remanentes</th>
                <th>Editar</th>
                <th>Eliminar</th>
            </thead>
            <tbody>
            <?php
            if (!empty($seatsByEventList)) {
                foreach ($seatsByEventList as $seatsByEventItem) {  
            ?>
                <tr>
                    <form method="post">
                        <input type="hidden" name="idSeatsByEvent" value="<?=$seatsByEventItem->getIdSeatsByEvent()?>">
                        <?php 
                            echo "<td>".$seatsByEventItem->getQuantity()."</td>";
                            echo "<td>".$seatsByEventItem->getPrice()."</td>";
                            echo "<td>".$seatsByEventItem->getRemnants()."</td>";
                        ?>
                        <td>
                            <input type="submit" value="Editar" formaction="<?=FRONT_ROOT?>SeatsByEventManagement/viewEditCategory"> 
                        </td>
                        <td>
                            <input type="submit" value="Eliminar" formaction="<?=FRONT_ROOT?>SeatsByEventManagement/deleteCategory">
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
            <button type="submit" formaction="<?=FRONT_ROOT?>SeatsByEventManagement/index">Volver</button>
        </form>
    </section>
</div>
