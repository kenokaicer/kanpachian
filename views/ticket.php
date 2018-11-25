<?php
use chillerlan\QRCode\QRCode as QRCode;
?>
<body class="Site"> <!--These two classes are needed for the footer to stick to the bottom of the page-->
<main class="Site-content">

<div id="additional-info" style="padding-top:5px;height:70px">
   <div class="row">
      <div class="large-12 columns">
         <h2 class="shadow-header color-white headings text-center">Tickets</h2>
      </div>
   </div>
</div>
<div class="wrapper" style="border:none">
   <div class="people-you-might-know">
      <?php
      if(isset($ticketList)){
         $i=0;
         foreach ($ticketList as $ticket) {
      ?>
      <div id="ticket<?=$i?>">
      <div id="ticket" class="row add-people-section">
         <div id="ticket-padding-top" class="row">
         </div>
         <div class="row" style="text-align: left;">
            <div class="small-4 large-2 columns about-people" >
               <div class="about-people-avatar">
                  <img class="qr-code img-thumbnail img-responsive" id="content" src="
                  <?php
                  $data = $ticket->getQrCode();
                  echo (new QRCode)->render($data);

                  /*Alternative version, with Google API*/
                  /*echo "https://chart.googleapis.com/chart?cht=qr&chl=".$data."&chs=160x160&chld=L|0";*/
                  ?> 
                  "
                  id="content">
               </div>
            </div>
            <div class="small-4 large-4 columns about-people" >
               <div id="ticket-2nd-column" class="about-people-author">
                  <p class="author-name" id="eventName" style="">
                     <strong><?=$ticket->getPurchaseLine()->getSeatsByEvent()->getEventByDate()->getEvent()->getEventName()?></strong>
                  </p>
                  <p class="author-mutual" id="date">
                     <?php
                     $date = $ticket->getPurchaseLine()->getSeatsByEvent()->getEventByDate()->getDate();
                     $date = strftime("%d de %B, de %G",strtotime($date));
                     echo $date;
                     ?>
                  </p>
                  <p class="author-location">
                     <i class="fa fa-map-marker" aria-hidden="true"></i>
                     <?=$ticket->getPurchaseLine()->getSeatsByEvent()->getEventByDate()->getTheater()->getTheaterName()?>
                  </p>
                  <p class="author-mutual" id="localidad">
                     <?=$ticket->getPurchaseLine()->getSeatsByEvent()->getEventByDate()->getTheater()->getLocation()?>
                  </p>
                  <p class="author-mutual" id="address" style="margin-bottom:10px">
                     <?=$ticket->getPurchaseLine()->getSeatsByEvent()->getEventByDate()->getTheater()->getAddress()?>
                  </p>
                  
                  
               </div>
            </div>  
            <div class="small-4 large-3 columns about-people">
               <div class="about-people-author">
                  <p class="author-mutual" id="seatType1" style="font-size: 16px">
                     Asiento:
                  </p>
                  <p class="author-mutual" id="seatType2" style="font-size: 16px">
                     <strong><?=$ticket->getPurchaseLine()->getSeatsByEvent()->getSeatType()->getSeatTypeName()?></strong>
                  </p>
                  <p class="author-mutual" id="ticketNumber">
                     Ticket nº: <?=$ticket->getTicketCode()?>
                  </p>
                  
                  <p class="author-mutual" id="price">
                     $<?=$ticket->getPurchaseLine()->getPrice()?>
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
      <?php 
         $i++;
         }
      }
      ?>
   </div>
</div>

<?php require VIEWS_PATH."FooterUserView.php";?>

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
    mywindow.document.write("</head><body style='text-align:center'>");  
    mywindow.document.write("<div id='print'>");  
    mywindow.document.write('<h1>Ticket</h1>');
    mywindow.document.write(elem.innerHTML);
    mywindow.document.write("</div>"); 
    mywindow.document.write('</body></html>');
    mywindow.document.getElementById("div-print-button").style.display = "none"; 
    mywindow.document.getElementById("print").style.display = "inline-block"; 
    mywindow.document.getElementById("print").style.width = "75vw";
    mywindow.document.close(); // necessary for IE >= 10
    mywindow.focus(); // necessary for IE >= 10*/
    //mywindow.print();
    //mywindow.close();

    return true;
}
</script>

<?php
if(isset($_SESSION["userLogged"])){
?>
<script>
    $('#cart-number').attr("data-count","<?php echo sizeof($_SESSION["virtualCart"])?>"); //set proper cart lenght, as navbar is loaded before the new purchaseLine
</script>
<?php
}
?>
