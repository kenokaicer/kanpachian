<body style="background-image: url('<?=IMG_PATH?>adminBackground.jpg');">
<div class="wrapper" style="max-width:900px">
    <form onsubmit="return send()" action="<?=FRONT_ROOT?>TheaterManagement/addTheater" method="post" id="form1" enctype="multipart/form-data">
        <section>
            <table>
                <th style="width:33%"></th>
                <th style="width:33%"></th>
                <th style="width:33%"></th>
                <tr>
                    <td>Nombre: <input type="text" name="name" required></td>
                    <td>Ubicación: <input type="text" name="location" required></td>
                    <td>Dirección: <input type="text" name="address" required></td>
                </tr>
                <tr>
                    <td>Capacidad Máxima: <input type="number" name="maxCapacity" required></td>
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
                        <h5>Plazas Agregadas:</h5>
                        <table>
                            <thead>
                                <th>Tipo</th>
                            </thead>
                            <tbody id="seatTypesTable">

                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>
        </section> 
        <section>
            <div id="seatTypeHidden">
                <button class="button" type="submit">Agregar</button>
                <input class="button" type="submit" value="Volver" formaction="<?=FRONT_ROOT?>TheaterManagement/index" formnovalidate>
            </div>
        </section>
    </form>
</div>

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