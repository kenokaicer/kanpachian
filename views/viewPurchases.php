<body class="Site"> <!--These two classes are needed for the footer to stick to the bottom of the page-->
<!--<main class="Site-content">-->

<div id="additional-info" style="padding:0;height: 70px;">
    <div class="row" style="padding:0;height: 70px;">
        <div class="large-12 columns" style="padding:0;height: 70px;">
            <h1 class="shadow-header color-white headings text-center">Cuenta</h2>
        </div>
    </div>

</div>
<div style="display: inline-block;border-style: none" id="intro">
    <div class="wrapper" style="border-style:none;min-height:58vh;width:800px">
        <section class="app-feature-section">
            <div class="row align-middle">

                <div class="small-12 medium-12 columns" >
                    <h3 class="app-feature-section-main-header">Seleccione la fecha de compra</h3>    
                    <!--<h4 class="app-feature-section-sub-header" style="display:inline-block">TEXTO</h4>-->
                    <div>
                        <form action="<?=FRONT_ROOT?>Purchase/showTickets" method="post">
                            <select name="idPurchase">
                                <?php
                                if(isset($purchaseList)){
                                    foreach ($purchaseList as $purchase) {
                                ?>
                                <option value="<?=$purchase->getIdPurchase()?>">
                                    <?php
                                    echo "fecha: ".$purchase->getDate().", Total: $".$purchase->getTotalPrice();
                                    ?>
                                </option>
                                <?php
                                    }
                                }
                                ?>
                            </select>
                            <button type="submit">Ver Tickets</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>


    </div>
</div>

<?php require VIEWS_PATH."FooterUserView.php";?>



<script language='javascript' type='text/javascript'>
function showCreditCard(){
    if($("#creditCardFields").is(":hidden")){
        jQuery("#creditCardFields").show(1000); //$() is the same as jQuery()
    }else{
        $("#creditCardFields").hide(1000);
    }   
}

function checkPassword() {
    var pass1 = document.getElementById("pass1").value;
    var pass2 = document.getElementById("pass2").value;
    var ok = true;
    if (pass1 != pass2) {
        alert("Las contraseñas no coinciden");
        document.getElementById("pass1").style.borderColor = "#E34234";
        document.getElementById("pass2").style.borderColor = "#E34234";
        ok = false;
    }
    else {
        //alert("Passwords Match!!!");
        document.getElementById("pass2").setAttribute("disabled", true);
    }
    return ok;
    }
</script>