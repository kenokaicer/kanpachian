<body style="background-image: url('<?=IMG_PATH?>adminBackground.jpg');">
<div class="wrapper">
    <section>
            <table id="mainTable" style="padding:0px;margin:0">
                <tr>
                    <td colspan="3">Evento:
                        <select id="selectEvent" name="idEvent"> <!--no longer use of onchange, for a jquery script that detects click on same option as currently selected-->

                            <?php
                                foreach ($eventList as $value) {
                            ?>
                                <option onEventChanged="myFunction(this.value)" value="<?=$value->getIdEvent()?>"><?=$value->getEventName().", Categoría: ".$value->getCategory()->getCategoryName()?></option>      
                            <?php
                                }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr id="trEventByDate" hidden> <!--set unhidden when event changed on Event select-->
                    <td colspan="3">Calendario:
                        <select id="selectEventByDate" name="idEventByDate"> <!--onchange returns seatTypes-->
                            
                        </select>
                    </td>
                </tr>
                <tr id="trSeatType" hidden> <!--set unhidden when event changed on EventByDate select-->
                    <td colspan="3">Tipo de Asiento:
                        <select id="selectSeatType" name="idSeatType"> <!--onchange sets idSeatType to hidden input -->
                            
                        </select>
                    </td>
                </tr>
        <form action="<?=FRONT_ROOT?>SeatsByEventManagement/addSeatsByEvent" method="post">
                <tr id="inputs" hidden> <!--set unhidden when event changed on SeatType select-->
                        <td><input type="hidden" name="idSeatType">
                            Cantidad: <input type="number" name="quantity" required></td> 
                        <td>Precio: <input type="number" name="price" required></td>
                </tr>
                
            </table>
            <table style="padding:0px;margin:0">
                <tr>
                    <td colspan="3">
                        <div>
                            <button type="submit">Agregar</button>
                            <input class="button" type="submit" value="Volver" formaction="<?=FRONT_ROOT?>SeatsByEventManagement/index" formnovalidate>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </section> 
</div>

<script>
$("#selectEvent").mouseup(function() {
    var open = $(this).data("isopen");

    if(open) {
        alert(this.value); //do something here with the value recieved by the select
        //code //aca va el codigo que tenemos hecho hasta ahora en home, que ya esta devolviendo un array con los datos necesarios
        $("#trEventByDate").show(1000); //show the select after loading it
    }

    $(this).data("isopen", !open);
});

$("#selectEventByDate").mouseup(function() {
    var open = $(this).data("isopen");

    if(open) {
        alert(this.value); //do something here with the value recieved by the select
        //code //acá hay que cargar el select de SeatType, con el array devuelto, funcion for ajax "getSeatTypes"
        //importante poner los id de SeatType
        $("#trSeatType").show(1000); //show the select after loading it
    }

    $(this).data("isopen", !open);
});

$("#selectSeatType").mouseup(function() {
    var open = $(this).data("isopen");

    if(open) { //only show the inputs tr
        $("#inputs").show(1000); //show the select after loading it
    }

    $(this).data("isopen", !open);
});

//estaría bueno agregar una funcion que agrege los asientos sin irse de la página, el tema es que como devolver la confirmacion se
//los agregó o no, 
//de ser hay que hacer un remove del option del select de seatType, para prevenir que no se pueda volver a cargar
</script>