<body style="background-image: url('<?=IMG_PATH?>adminBackground.jpg');">
<div class="wrapper" style="max-width:900px">
    <form onsubmit="return send()" action="<?=FRONT_ROOT?>TheaterManagement/editTheater" method="post" enctype="multipart/form-data">
        <input type="hidden" name="oldIdTheater" value="<?=$theater->getIdTheater()?>">
        <section>
            <table>
                <th style="width:33%"></th>
                <th style="width:33%"></th>
                <th style="width:33%"></th>
                <tr>
                    <td>Nombre: <input type="text" name="theaterName" value="<?=$theater->getTheaterName()?>" required></td>
                    <td>Ubicación: <input type="text" name="location" value="<?=$theater->getLocation()?>" required></td>
                    <td>Dirección: <input type="text" name="address" value="<?=$theater->getAddress()?>" required></td>
                </tr>
                <tr>
                    <td>Capacidad Máxima: <input type="number" name="maxCapacity" value="<?=$theater->getMaxCapacity()?>" required></td>
                    <td>
                        <select style="margin-top:18px" name="seatTypes" id="seatTypesSelect">
                            <?php
                                foreach ($seatTypeList as $value) {
                            ?>
                                <option value="<?=$value->getIdSeatType()?>"><?=$value->getSeatTypeName()?></option>      
                            <?php
                                }
                            ?>
                        </select>
                    </td> 
                    <td>Imágen: <input id="file" type="file" name="file"></td>    
                </tr>
                <tr>
                    <td colspan="3">
                        <div class="first-half">
                            <h5>Plazas:</h5>
                            <table>
                                <thead>
                                    <th>Tipo</th>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($theater->getSeatTypes() as $seatType) {
                                    ?>
                                        <tr><td><?=$seatType->getSeatTypeName()?></td></tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="second-half">
                            <h5>Plazas Agregadas:</h5>
                            <table>
                                <thead>
                                    <th>Tipo</th>
                                </thead>
                                <tbody id="seatTypesTable">

                                </tbody>
                            </table>
                        </div>
                    </td>
                </tr>
            </table>
        </section> 
        <section>
            <div id="seatTypeHidden">
                <button class="button" type="submit">Modificar</button>
                <input class="button" type="submit" value="Volver" formaction="<?=FRONT_ROOT?>TheaterManagement/index" formnovalidate>
            </div>
        </section>
    </form>
</div>

<style>
.first-half {
    float: left;
    width: 50%;
}
.second-half {
    float: right;
    width: 50%;
}
</style>

<script>
var seatTypeList = [];

$("#seatTypesSelect").mouseup(function() { //This is for events. //Is triggered when option changed.
    var open = $(this).data("isopen");

    if(open) {
        seatTypeList.push(this.value);
        var optionText = this.options[this.selectedIndex].text;
        $('option:selected', this).remove();
        $('#seatTypesTable').append('<tr><td>'+optionText+'</td></tr>'); 
    }

    $(this).data("isopen", !open);
});


function send()
{
    var ok = false;

    if(seatTypeList.length != 0){
        seatTypeListJson = JSON.stringify(seatTypeList);
        $('#seatTypeHidden').append("<input type='hidden' value='"+seatTypeListJson+"' name='seatTypeList'>");
        document.getElementById("seatTypesSelect").disabled = true;
        ok = true;
    }else{
        alert('Seleccione al menos un tipo de asiento');
    }

    return ok;
}

</script>