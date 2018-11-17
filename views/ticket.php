
<script type="text/javascript">
//console.log("https://chart.googleapis.com/chart?cht=qr&chl=swag&chs=160x160&chld=L|0")
</script>

<div id="additional-info" style="padding-top:5px;height:70px">
   <div class="row">
      <div class="large-12 columns">
         <h2 class="color-white headings text-center">Ticket!</h2>
      </div>
   </div>
</div>
<div class="wrapper">
   <div class="people-you-might-know">
      <div class="add-people-header">
         <h6 class="header-title">
            Tickets
         </h6>
      </div>

      <?php
       for ($i=0; $i < 5 ; $i++) { 
       ?>
      <div class="row add-people-section">
         <div class="small-12 medium-6 columns about-people" >
            <div class="about-people-avatar">
               <img class="qr-code img-thumbnail img-responsive" id="content" 
               <?php
               $s = "src=https://chart.googleapis.com/chart?cht=qr&chl=$i&chs=160x160&chld=L|0";
               echo $s;
              ?> 
                id="content">
            </div>
            <div class="about-people-author">
               <p class="author-name" id="eventName">
                  Pachanga!.
               </p>
               <p class="author-location">
                  <i class="fa fa-map-marker" aria-hidden="true"></i>
                  Teatro Colon
               </p>
               <p class="author-mutual" id="ubicacion">
                  <strong>Disney</strong> 54345.
               </p>
            </div>
         </div>
         <div class="small-12 medium-6 columns add-friend">
            <div class="add-friend-action">
               <button class="button primary small">
               <i class="fa fa-user-plus" aria-hidden="true"></i>
               Imprimir
               </button>
               <button class="button secondary small">
               <i class="fa fa-user-times" aria-hidden="true"></i>
               Otro boton
               </button>
            </div>
         </div>
      </div>
         <?php } ?>
   </div>
</div>
<style>
   .people-you-might-know" {
   overflow-y: scroll;
   }
   .people-you-might-know {
   background-color: #fefefe;
   padding: 1rem 0 0;
   border: 1px solid #cacaca;
   box-shadow: 0 0 3.125rem rgba(0, 0, 0, 0.18);
   }
   .people-you-might-know .add-people-header {
   padding: 0 0.9375rem;
   border-bottom: 0.0625rem solid #cacaca;
   }
   .people-you-might-know .add-people-header .header-title {
   font-weight: bold;
   }
   .people-you-might-know .add-people-section {
   margin: 1rem 0 0;
   padding-bottom: 1rem;
   border-bottom: 0.0625rem solid #cacaca;
   }
   .people-you-might-know .add-people-section .about-people {
   display: -webkit-flex;
   display: -ms-flexbox;
   display: flex;
   -webkit-align-items: flex-start;
   -ms-flex-align: start;
   align-items: flex-start;
   }
   .people-you-might-know .add-people-section .about-people .about-people-avatar {
   padding-right: 0.5rem;
   padding-left: 0;
   }

   .people-you-might-know .add-people-section .about-people .about-people-avatar .avatar-image {
   width: 5rem;
   height: 5rem;

   border: 0.0625rem solid #cacaca;
   }
   .people-you-might-know .add-people-section .about-people .about-people-author {
   -webkit-flex: 1 0 0;
   -ms-flex: 1 0 0px;
   flex: 1 0 0;
   }
   .people-you-might-know .add-people-section .about-people .about-people-author .author-name {
   color: #0a0a0a;
   margin: 0.375rem 0 0;
   }
   .people-you-might-know .add-people-section .about-people .about-people-author .author-location,
   .people-you-might-know .add-people-section .about-people .about-people-author .author-mutual {
   color: #8a8a8a;
   margin-bottom: 0;
   font-size: 0.85em;
   }
   .people-you-might-know .add-people-section .add-friend 
   {
   display: -webkit-flex;
   display: -ms-flexbox;
   display: flex;
   -webkit-align-items: center;
   -ms-flex-align: center;
   align-items: center;
   -webkit-justify-content: center;
   -ms-flex-pack: center;
   justify-content: center;
   }
   @media screen and (max-width: 39.9375em) {
   .people-you-might-know .add-people-section .add-friend {
   -webkit-justify-content: flex-start;
   -ms-flex-pack: start;
   justify-content: flex-start;
   }
   }
   .people-you-might-know .add-people-section .add-friend .add-friend-action {
   margin-top: 0.7rem;
   }
   .people-you-might-know .view-more-people {
   margin: .7rem 0;
   }
   .people-you-might-know .view-more-people .view-more-text {
   margin-bottom: 0;
   text-align: center;
   }
   .people-you-might-know .view-more-people .view-more-text .view-more-link {
   color: #1779ba;
   }
   .people-you-might-know .view-more-people .view-more-text .view-more-link:hover, .people-you-might-know .view-more-people .view-more-text .view-more-link:focus {
   text-decoration: underline;
   }
</style>
</html>

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
</script>


