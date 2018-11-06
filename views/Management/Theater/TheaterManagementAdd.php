<?php
if(isset($_POST["seatTypes"]))
{   
    $var = true;
    foreach ($_SESSION["seatTypesForTheater"] as $value) {
        if ($value->getIdSeatType() == $_POST["seatTypes"])
            $var = false;
    }

    if($var){
        foreach ($seatTypeList as $key => $value) {
            if($value->getIdSeatType() == $_POST["seatTypes"]){
                array_push($_SESSION["seatTypesForTheater"], $value);
            }
        }
    }else{
        echo "<script>alert('Plaza ya agregada');</script>";
    }

}else{
    $_SESSION["seatTypesForTheater"] = array();
}
?>

<body style="background-image: url('<?=IMG_PATH?>adminBackground.jpg');">
<div class="wrapper">
    <section>
        <table>
            <tr>
                <td colspan="2">
                    <form action="" method="post">
                        Tipo/s de Plaza:
                        <select name="seatTypes">
                            <?php
                                foreach ($seatTypeList as $value) {
                            ?>
                                <option value="<?=$value->getIdSeatType()?>"><?=$value->getSeatTypeName()?></option>      
                            <?php
                                }
                            ?>
                        </select>
                        <button type="submit">Agregar Plaza</button><br>
                        <h5>Plazas Agregadas:</h5>
                        <table>
                        <th>Tipo</th>
                        <th>Descripción</th>
                        <?php
                            foreach ($_SESSION["seatTypesForTheater"] as $value) {        
                        ?>
                            <tr>
                                <td><?=$value->getSeatTypeName()?></td>
                                <td><?=$value->getDescription()?></td>
                            </tr>
                        <?php
                            }
                        ?>
                        </table>
                    </form>
                </td>
            </tr>
            <form action="<?=FRONT_ROOT?>TheaterManagement/addTheater" method="post" id="form1">
            <tr>
                <td>Nombre: <input type="text" name="name" required></td>
                <td>Imágen: </td>
            </tr>
            <tr>
                <td>Ubicación: <input type="text" name="location" required></td>
                <td>Capacidad Máxima: <input type="number" name="maxCapacity" required></td>     
            </tr>
            </form>
        </table>
    </section> 
    <section>
        <form action="<?=FRONT_ROOT?>TheaterManagement/index" method="post" id="form2"></form>
        <button type="submit" form="form1">Agregar</button>
        <button type="submit" form="form2">Volver</button>
    </section>
</div>