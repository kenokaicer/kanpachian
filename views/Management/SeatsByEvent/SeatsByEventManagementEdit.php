<body style="background-image: url('<?=IMG_PATH?>adminBackground.jpg');">
   
<div class="wrapper">
    <section>
    <form action="" onsubmit="return checkMax()" method="post">
        <input type="hidden" name="oldIdSeatsByEvent" value="<?=$seatsByEvent->getIdSeatsByEvent()?>">
        <table id="mainTable" style="padding:0px;margin:0">
        <tr>
            <td style="width:50%">Evento: <?=$eventName?></td>
            <td style="width:50%">Calendario y <?=$theaterData?></td>
        </tr>
        <tr>
            <td colspan="2">
                <table>
                    <th style="width:25%">Tipo de Asiento</th>
                    <th style="width:25%">Cantidad</th>
                    <th style="width:25%">Precio</th>
                    <th style="width:25%">Remanentes</th>
                    <tr>
                        <td><?=$seatsByEvent->getSeatType()->getSeatTypeName()?></td>
                        <td><input type="number" name="quantity" value="<?=$seatsByEvent->getQuantity()?>" id="quantity"></td>
                        <td><input type="number" name="price" value="<?=$seatsByEvent->getPrice()?>"></td>
                        <td><input type="number" name="remnants" value="<?=$seatsByEvent->getRemnants()?>" id="remnants"></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div style="vertical-align: middle;">
                    <button type="submit" formaction="<?=FRONT_ROOT?>SeatsByEventManagement/editSeatsByEvent">Modificar</button>
                    <input style="margin-top: 18px"  class="button" type="submit" value="Volver" formaction="<?=FRONT_ROOT?>SeatsByEventManagement/index" formnovalidate> 
                </div>
            </td>
        </tr>
        </table>
        </form>
    </section>
</div>

<script>
function checkMax() {
    var quantity = document.getElementById("quantity").value;
    var remnants = document.getElementById("remnants").value;
    var ok = true;

    console.log(remnants,quantity);
    print 
    if (remnants > quantity) {
        alert("La cantidad de remanentes no puede ser superior a la cantidad disponible");
        document.getElementById("remnants").style.borderColor = "#E34234";
        ok = false;
    }

    return ok;
    }
</script>