<body style="background-image: url('<?=IMG_PATH?>adminBackground.jpg');">
<div class="wrapper">
    <section>
        <table id="myTable">
            <thead>
                <th>Nombre</th>
                <th>Ubicación</th>
                <th>Capacidad Máxima</th>
                <th>Tipo de Asientos</th>
                <th>Editar</th>
                <th>Eliminar</th>
            </thead>
            <tbody>
            <?php
            if (!empty($theaterList)) {
                $i = 0;
                foreach ($theaterList as $key => $theater) { 
                    $seatTypeList = $theater->getSeatTypes(); 
            ?>
                <tr>
                    <td><?= $theater->getTheaterName() ?></td>
                    <td><?= $theater->getLocation() ?></td>
                    <td><?= $theater->getMaxCapacity() ?></td>
                    <td><div style="align:center"><button style="margin:0" onclick="showTable(tr<?=$i?>,table<?=$i?>)">Ver/Esconder</button></div></td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="id" value="<?=$theater->getIdTheater()?>">
                            <input type="submit" value="Editar" formaction="<?=FRONT_ROOT?>TheaterManagement/viewEditTheater">
                    </td>
                    <td>                           
                            <input type="submit" value="Eliminar" formaction="<?=FRONT_ROOT?>TheaterManagement/deleteTheater">
                        </form>
                    </td>
                </tr>
                <tr id="tr<?=$i?>" hidden>
                    <td colspan=6>
                        <table style="margin:0;paddin:0" id="table<?=$i?>" hidden>
                            <th>Nombre</th>
                            <th colspan="3">Descripción</th>
                            <?php
                            foreach ($seatTypeList as $seatType) {
                            ?>
                            <tr>
                                <td><?= $seatType->getSeatTypeName() ?></td>
                                <td><?= $seatType->getDescription() ?></td>
                            </tr>
                            <?php
                            }
                            ?>
                        </table>
                    </td>
                </tr>
            <?php
                $i++;
                }
            }
            ?>
            </tbody>
        </table>
    </section>
    <section>
        <form method="post">
            <button type="submit" formaction="<?=FRONT_ROOT?>TheaterManagement/index">Volver</button>
        </form>
    </section>
</div>

<script language='javascript' type='text/javascript'>
function showTable(tr,id){
    if($(id).is(":hidden")){
        jQuery(tr).show(1000); //$() is the same as jQuery()
        $(id).show(1000);
    }else{
        $(id).hide(1000);
        $(tr).hide(1000);
    }   
}
</script>