<body style="background-image: url('<?=IMG_PATH?>adminBackground.jpg');">
<div class="wrapper">
    <section>
        <form onsubmit="return send()" action="<?=FRONT_ROOT?>EventByDateManagement/editEventByDate" method="post">
            <input type="hidden" name="oldIdEventByDate" value="<?=$eventByDate->getIdEventByDate()?>">
            <table>
                <th style="width:50%"></th>
                <th style="width:50%"></th>
                <tr>
                    <td colspan="2">Evento:
                        <select name="idEvent">
                            <?php
                                foreach ($eventList as $value) {
                            ?>
                                <option <?php if($value->getIdEvent() == $eventByDate->getEvent()->getIdEvent()) echo "selected"?> value="<?=$value->getIdEvent()?>"><?=$value->getEventName().", Categoría: ".$value->getCategory()->getCategoryName()?></option>      
                            <?php
                                }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Fecha: <input type="date" name="date" value="<?=$eventByDate->getDate()?>"></td>
                    <td>Teatro:
                        <select name="idTheater">
                            <?php
                                foreach ($theaterList as $value) {
                            ?>
                                <option <?php if($value->getIdTheater() == $eventByDate->getTheater()->getIdTheater()) echo "selected"?> value="<?=$value->getIdTheater()?>"><?=$value->getTheaterName().", maxCap: ".$value->getMaxCapacity()?></option>      
                            <?php
                                }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">Artista: 
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
                </tr>
                
                <tr>
                    <td>Artistas Cargados
                        <table>
                        <?php
                        foreach ($eventByDate->getArtists() as $artistItem) {
                        ?>
                            <tr><td><?=$artistItem->getName()." ".$artistItem->getLastName()?></td></tr>
                        <?php
                        }
                        ?>
                        </table>
                    </td>
                    <td>Artistas Seleccionados
                        <table id="artistTable">
                            <tbody>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div id="artistHidden">
                            <button class="button" type="submit">Modificar</button>
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

</script>