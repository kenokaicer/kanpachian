<body class="Site"> <!--These two classes are needed for the footer to stick to the bottom of the page-->
<main class="Site-content">

<div id="additional-info" style="padding:0;height: 70px;">
    <div class="row" style="padding:0;height: 70px;">
        <div class="large-12 columns" style="padding:0;height: 70px;">
            <h1 class="color-white headings text-center">Cuenta</h2>
        </div>
    </div>

</div>
<div style="border-style: none" id="intro">
    <div class="wrapper" style="border-style:none;min-height:57.74vh;width:800px">
        <section class="app-feature-section">
            <div class="row align-middle">

                <div class="small-12 medium-12 columns" >
                    <h3 class="app-feature-section-main-header">Información Personal</h3>    
                    <!--<h4 class="app-feature-section-sub-header" style="display:inline-block">TEXTO</h4>-->
                    <div class="row">
                        <div class="small-12 medium-3 columns" style="text-align:left">
                            <div class="app-feature-section-features" style="display:inline-block;">
                                <div class="app-feature-section-features-block">
                                    <i class="fa fa-user-circle" aria-hidden="true"></i> <span>Usuario: <?=$user->getUserName()?></span>
                                </div>
                                <div class="app-feature-section-features-block">
                                    <i class="fa fa-user" aria-hidden="true"></i> <span>Nombre: <?=$client->getName()?></span>
                                </div>
                                <div class="app-feature-section-features-block">
                                    <i class="fa fa-user" aria-hidden="true"></i> <span>Apellido: <?=$client->getLastName()?></span>
                                </div>
                                <div class="app-feature-section-features-block" style="padding-bottom:0">
                                    <i class="fa fa-id-card" aria-hidden="true"></i> <span>DNI: <?=$client->getDni()?></span>
                                </div>
                                <div class="app-feature-section-features-block">
                                    <i class="fa fa-credit-card" aria-hidden="true"></i> <span>
                                        <?php 
                                        if(!is_null($client->getCreditCard())){
                                        ?>
                                        <button onclick="showCreditCard()" class="app-feature-section-features-block" id="buttonCreditCard">Mostrar Tarjeta de Crédito</button></span> 
                                        <?php 
                                        }else{
                                        ?>
                                        <form action="<?=FRONT_ROOT?>Account/viewRegisterCreditCard" method="get">
                                        <input type="hidden" name="redirect" value="noredirect">
                                        <button class="app-feature-section-features-block" id="buttonCreditCard">Agregar Tarjeta de Crédito</button></span> 
                                        </form>
                                        <?php 
                                        }
                                        ?>
                                </div>
                            </div>
                            <div id="creditCardFields" hidden>
                                
                                <div>Número: <?php if(!is_null($client->getCreditCard())) echo $client->getCreditCard()->getCreditCardNumber() ?></div>
                                <div>Titular: <?php if(!is_null($client->getCreditCard())) echo $client->getCreditCard()->getCardHolder() ?></div>
                            </div>
                        </div>
                        <div class="small-12 medium-5 columns" style="text-align:left">
                            <div class="app-feature-section-features" style="display:inline-block;">
                                <div class="app-feature-section-features-block">
                                    <i class="fa fa-key" aria-hidden="true"></i> <span>Cambio de Contraseña</span>
                                </div>
                                <div>
                                    <form action="<?=FRONT_ROOT?>Account/changePassword" onsubmit="return checkPassword()" method="post">
                                        <input style="margin-bottom:0.5em" placeholder="Contraseña Actual" type="password" name="password" id="pass">
                                        <input style="margin-bottom:0.5em" placeholder="Nueva Contraseña" type="password" name="newPassword" id="pass1">
                                        <input style="margin-bottom:0.5em" placeholder="Repita Contraseña" type="password" id="pass2">
                                        <input style="line-height:0;margin:0" class="button" type="submit" value="Cambiar">
                                    </form>
                                </div>
                                <div class="app-feature-section-features-block">
                                    <i class="fa fa-at" aria-hidden="true"></i> <span>Cambio de E-Mail</span>
                                </div>
                                <div>
                                    <form action="<?=FRONT_ROOT?>Account/changeEmail" method="post">
                                    <input style="margin:0;margin-bottom:0.5em;" type="email" value="<?=$user->getEmail()?>" name="email">
                                    <input style="line-height:0;margin:0" class="button" type="submit" value="Cambiar">
                                    </form>
                                </div>
                            </div>
                        </div>
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
        $("#creditCardFields").show(1000);
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