<div id="additional-info" style="padding-top:5px;height:70px">
   <div class="row">
      <div class="large-12 columns">
         <h2 class="color-white headings text-center">Tickets</h2>
      </div>
   </div>
</div>
<div class="wrapper" style="border:none">
   <div class="people-you-might-know">
      <?php
      for ($i=0; $i < 5 ; $i++) { 
      ?>
      <div id="ticket<?=$i?>">
      <div  class="row add-people-section">
         <div id="ticket-padding-top" class="row">
         </div>
         <div class="row" style="text-align: left;">
            <div class="small-4 large-2 columns about-people" >
               <div class="about-people-avatar">
                  <img class="qr-code img-thumbnail img-responsive" id="content"
                  <?php
                  $s = "src=https://chart.googleapis.com/chart?cht=qr&chl=$i&chs=160x160&chld=L|0";
                  echo $s;
               ?> 
                  id="content">
               </div>
            </div>
            <div class="small-4 large-4 columns about-people" >
               <div id="ticket-2nd-column" class="about-people-author">
                  <p class="author-name" id="eventName" style="">
                     <strong>Evento aaaa aaaaa aaaaa aaaaaa</strong>
                  </p>
                  <p class="author-mutual" id="date">
                     10 de Diciembre, de 2077
                  </p>
                  <p class="author-location">
                     <i class="fa fa-map-marker" aria-hidden="true"></i>
                     Teatro Colon
                  </p>
                  <p class="author-mutual" id="localidad">
                     Buenos Aires
                  </p>
                  <p class="author-mutual" id="address" style="margin-bottom:10px">
                     Disney 54345
                  </p>
                  
                  
               </div>
            </div>  
            <div class="small-4 large-3 columns about-people">
               <div class="about-people-author">
               <p class="author-mutual" id="seatType" style="font-size: 16px">
                     <strong>Asiento</strong>
                  </p>
                  <p class="author-mutual" id="ticketNumber">
                     Ticket nº: 123456
                  </p>
                  
                  <p class="author-mutual" id="price">
                     $2100
                  </p>
               </div>
            </div>
            <div class="small-4 large-3 columns add-friend" id="div-print-button">
               <div class="add-friend-action">
                  <button onclick="PrintElem(ticket<?=$i?>)" id="print-button" class="button primary small">
                  <i class="fa fa-user-plus" aria-hidden="true"></i>
                  Imprimir
                  </button>
               </div>
            </div>
         </div>
      </div>
      </div>
      <?php } ?>
   </div>
</div>

<script>

//createQR("qrCode","perro");

function createQR(div,qrstring) 
{
    console.log(div,qrstring);
   var qrcode = new QRCode(document.getElementById(div), {
    text: qrstring,
    width: 128,
    height: 128,
    colorDark : "#000000",
    colorLight : "#ffffff",
    correctLevel : QRCode.CorrectLevel.H
});

//qrcode.clear(); // clear the code.
//qrcode.makeCode("http://naver.com"); // make another code.
}

function PrintElem(elem)
{
    var mywindow = window.open('', 'PRINT', 'height=400,width=600');

    mywindow.document.write('<html><head><title>' + document.title  + '</title>');
    mywindow.document.write("<link rel='stylesheet' href='<?=CSS_PATH?>teststyle2.css'>");
    mywindow.document.write("<link rel='stylesheet' href='<?=CSS_PATH?>style.css'>");
    mywindow.document.write("<link rel='stylesheet' href='<?=CSS_PATH?>foundation.css'>");
    mywindow.document.write("<link rel='stylesheet' href='<?=CSS_PATH?>foundation.min.css'>");
    mywindow.document.write("<link rel='stylesheet' href='<?=CSS_PATH?>ticket.css'>");
    mywindow.document.write('</head><body >');    
    mywindow.document.write('<h1>' + document.title  + '</h1>');
    mywindow.document.write(elem.innerHTML);
    mywindow.document.write('</body></html>');
    mywindow.document.getElementById("div-print-button").style.display = "none"; 
    mywindow.document.close(); // necessary for IE >= 10
    mywindow.focus(); // necessary for IE >= 10*/

    //mywindow.print();
    //mywindow.close();

    return true;
}
</script>



