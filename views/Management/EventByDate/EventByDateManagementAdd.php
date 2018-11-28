<body style="background-image: url('<?=IMG_PATH?>adminBackground.jpg');">
<div class="wrapper">
    <section>
        <form onsubmit="return send()" action="<?=FRONT_ROOT?>EventByDateManagement/addEventByDate" method="post">
            <table>
                <tr>
                    <td style="width:50%">Evento:
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
                    <td style="width:50%">
                            <label>Esta en oferta?
                                <select id="selectIsSale" name ="inSale">
                                    <option value="0">No</option>  
                                    <option value="1">Si</option>
                                </select>
                            </label>
                    </td>
                </tr>
                <tr>
                    <td>Fecha: <input type="date" name="date" required></td>
                    <td>Fecha de finalización promo: <input id="date2" type="date" name="date2"></td>   
                </tr>
                <tr>
                    <td>Artista: 
                        <select id="artistSelect" name="idArtist">
                            <?php
                                foreach ($artistList as $value) {
                            ?>
                                <option value="<?=$value->getIdArtist()?>"><?=$value->getName()." ".$value->getLastname()?></option>      
                            <?php
                                }
                            ?>
                        </select>
                    </td>
                    <td>Teatro:
                        <select name="idTheater">
                            <?php
                                foreach ($theaterList as $value) {
                            ?>
                                <option value="<?=$value->getIdTheater()?>"><?=$value->getTheaterName().", maxCap: ".$value->getMaxCapacity()?></option>      
                            <?php
                                }
                            ?>
                        </select>
                    </td>
                </tr>
                
                <tr>
                    <td colspan="2">
                    <table id="artistTable">
                        <thead>
                            <th>Artista</th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <div id="artistHidden">
                            <button class="button" type="submit">Agregar</button>
                            <input class="button" type="submit" value="Volver" formaction="<?=FRONT_ROOT?>EventByDateManagement/index" formnovalidate>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </section> 
</div>


<script>

var artistList = [];


$("#artistSelect").mouseup(function() { //This is for events. //Is triggered when option changed.
    var open = $(this).data("isopen");

    if(open) {
        artistList.push(this.value);
        var optionText = this.options[this.selectedIndex].text;
        $('option:selected', this).remove();
        $('#artistTable').append('<tr><td>'+optionText+'</td></tr>'); 
    }

    $(this).data("isopen", !open);
});


function send()
{
    var ok = false;

    if(artistList.length != 0){
        artistListJson = JSON.stringify(artistList);
        $('#artistHidden').append("<input type='hidden' value='"+artistListJson+"' name='artistList'>");
        document.getElementById("artistSelect").disabled = true;
        ok = true;
    }else{
        alert('Agrege al menos un artista');
    }

    return ok;
}


$('#selectIsSale').change(function() {
    if ($(this).val() === '1') {
        $("#date2").prop('required',true);
    }
});
</script>