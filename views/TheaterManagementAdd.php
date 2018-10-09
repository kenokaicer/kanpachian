<?php
use Models\SeatType as SeatType;
use Models\Theater as Theater;

if(isset($_POST["seatTypes"]))
{   
    $exists = false;

    foreach ($_SESSION["seatTypesForTheater"] as $value) {
        if ($value->getIdSeatType()==$_POST["seatTypes"])
            $exists = true;
    }

    if(!$exists){
        foreach ($seatTypeList as $key => $value) {
            if($value['idSeatType']==$_POST["seatTypes"]){
                $seatType = new SeatType();
                $seatType->setIdSeatType($value['idSeatType'])->setName($value['name'])->setDescription($value['description']);
                $_SESSION["seatTypesForTheater"]->addSeatType($seatType);
            }
        }
    }else{
        echo "<script>alert('Plaza ya agregada');</script>";
    }
}else{
    $_SESSION["seatTypesForTheater"] = new Theater();
}
?>

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
                                <option value="<?=$value['idSeatType']?>"><?=$value['name']?></option>      
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
                            foreach ($_SESSION["seatTypesForTheater"]->getSeatTypes() as $value) {        
                        ?>
                            <tr>
                                <td><?=$value->getName()?></td>
                                <td><?=$value->getDescription()?></td>
                            </tr>
                        <?php
                            }
                        ?>
                        </table>
                    </form>
                </td>
            </tr>
            <form action="<?=BASE?>TheaterManagement/addTheater" method="post" id="form1">
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
        <form action="<?=BASE?>TheaterManagement/index" method="post" id="form2"></form>
        <button type="submit" form="form1">Agregar</button>
        <button type="submit" form="form2">Volver</button>
    </section>
</div>