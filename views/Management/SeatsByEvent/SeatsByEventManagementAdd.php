<body style="background-image: url('<?=IMG_PATH?>adminBackground.jpg');">
   <div class="wrapper">
      <section>

         <table id="mainTable" style="padding:0px;margin:0">
            <tr>
               <td colspan="3">
                  Evento:
                  <select id="selectEvent" name="idEvent">
                     <!--no longer use of onchange, for a jquery script that detects click on same option as currently selected-->
                     <?php
                        foreach ($eventList as $value) {
                        ?>
                     <option value="<?=$value->getIdEvent()?>"><?=$value->getEventName().", Categoría: ".$value->getCategory()->getCategoryName()?></option>
                     <?php
                        }
                        ?>
                  </select>
               </td>
            </tr>
            <form action="<?=FRONT_ROOT?>SeatsByEventManagement/addSeatsByEvent" method="post">

            <tr id="trEventByDate" hidden>
               <!--set unhidden when event changed on Event select-->
               <td colspan="3">
                  Calendario:
                  <select id="selectEventByDate" name="idEventByDate">
                     <!--onchange returns seatTypes-->
                  </select>
               </td>
            </tr>
            <tr id="trSeatType" hidden>
               <!--set unhidden when event changed on EventByDate select-->
               <td colspan="3">
                  Tipo de Asiento:
                  <select id="selectSeatType" name="idSeatType">
                     <!--onchange sets idSeatType to hidden input -->
                  </select>
               </td>
            </tr>
               <tr id="inputs" hidden>
                  <!--set unhidden when event changed on SeatType select-->
                  <td>
                     Cantidad: <input type="number" name="quantity" required>
                  </td>
                  <td>Precio: <input type="number" name="price" required></td>
               </tr>
         </table>
         <table style="padding:0px;margin:0">
         <tr>
         <td colspan="3">
         <div style="vertical-align: middle;">
         <button type="submit">Agregar</button>
         <input style="margin-top: 18px"  class="button" type="submit" value="Volver" formaction="<?=FRONT_ROOT?>SeatsByEventManagement/index" formnovalidate> 
         </div>
         </td>
         </tr>
         </table>
         </form>
      </section>
   </div>
   <script>
      $("#selectEvent").mouseup(function() { //This is for events. //Is triggered when option changed.
          var open = $(this).data("isopen");
      
          if(open) {
              RetriveCalendars('<?=FRONT_ROOT."controllers/Ajax/"?>','getByEventId',this.value);
              $("#trEventByDate").show(500); //show the select after loading it
          }
      
          $(this).data("isopen", !open);
      });
      
      $("#selectEventByDate").mouseup(function() { // This is for calendars
          var open = $(this).data("isopen");
      
          if(open) {
             //alert(this.value); //do something here with the value recieved by the select
              RetriveSeatTypes('<?=FRONT_ROOT."controllers/Ajax/"?>','getSeatTypes',this.value);//code //acá hay que cargar el select de SeatType, con el array devuelto, funcion for ajax "getSeatTypes"
              //importante poner los id de SeatType
              $("#trSeatType").show(500); //show the select after loading it
          }
      
          $(this).data("isopen", !open);
      });
      
      $("#selectSeatType").mouseup(function() { //This is for seat Types.
          var open = $(this).data("isopen");
          if(open) { //only show the inputs tr
              RetriveCalendars();
              $("#inputs").show(500); //show the select after loading it
              //$("#selectSeatType").val(this.value);
          }
          $(this).data("isopen", !open);
      });
      
      function RetriveCalendars(path,func,value) // option = id , date , theater. && el claendario set attribute value = id. .
      {
          $.ajax({
          url : path+'SeatsByEventManagementAjax.php', // requesting a PHP script
          type: 'post',
          dataType : 'json',
          data: {"function": func, "value": value}, //name of function to call in php file (this is a string passed by post and then checked in an if statement)
          success : function (data) 
          { // data contains the PHP script output
             // console.log(data);
             
             $(this).data("isopen", !open);
             data.forEach(loadCalendar);
          },
      })
      }
      
      function loadCalendar(p)
      {

        $('#selectEventByDate').append($('<option>',{value:p.idEventByDate,text:'Teatro: ' +p.theaterName + ",  Fecha: "+ p.date }));

        /* Alternative methods for older browsers 
        $(option).html("texto");
        $('#selectEventByDate').append(option);
        */
      }

       function RetriveSeatTypes(path,func,value) // option = id , date , theater. && el claendario set attribute value = id. .
      {
          $.ajax({
          url : path+'SeatsByEventManagementAjax.php', // requesting a PHP script
          type: 'post',
          dataType : 'json',
          data: {"function": func, "value": value}, //name of function to call in php file (this is a string passed by post and then checked in an if statement)
          success : function (data) 
          { // data contains the PHP script output
            console.log(data);
             //alert(data);
             $(this).data("isopen", !open);
             data.forEach(loadSeatTypes);
          },
      })
      }

        function loadSeatTypes(p)
      {
        console.log(p);
        //$('#selectSeatType').append($('<option>',{value:p.id,text:'Tipo de asiento'}));
        //$('#selectSeatType').append(new Option('1','Perro'));
          $('#selectSeatType').append($('<option>',{value:p.idSeatType,text: p.seatTypeName }));
      }


           



      
      //estaría bueno agregar una funcion que agrege los asientos sin irse de la página, el tema es que como devolver la confirmación si
      //los agregó o no, 
      //de ser hay que hacer un remove del option del select de seatType, para prevenir que no se pueda volver a cargar
   </script>

