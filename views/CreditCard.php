<body class="Site"> <!--These two classes are needed for the footer to stick to the bottom of the page-->
<main class="Site-content">
    
<body style="background-color: #dcdcdc;">
<form method="POST" onsubmit="return checkLenght();">
<div class="menuWrapper" style="border:none">
    <div id="sign-up"> 
        <h3 class="color-pink">Registro de Tarjeta de Crédito</h3>
        <hr />
        <label>Numero de Tarjeta</label>
        <input id="cardNumber" type="number" name="creditCardNumber" required>
        <label>Fecha de expiración</label>
        <input type="month" name="expirationDate" required>
        <label>Titular de la Tarjeta</label>
        <input type="text" name="cardHolder" required>
        <button class="blue-btn" formaction="<?=FRONT_ROOT?>Account/registerCreditCard">Enviar</button>
        <hr />
        <?php 
        if(!empty($redirect)){
        ?>
            <input type="hidden" name="redirect" value="noredirect">
        <?php
        }
        ?>
        <button type="submit" formnovalidate formaction="<?=FRONT_ROOT?>Home/index">Volver</button>
    </div>
</div>

</form>

<script>
function checkLenght() {
    var numeberLength = document.getElementById("cardNumber").value;
    numeberLength = numeberLength.length;

    var ok = true;
    if (numeberLength!=16) {
        alert("El número de tajeta debe ser de 16 dígitos");
        document.getElementById("cardNumber").style.borderColor = "#E34234";
        ok = false;
    }
    return ok;
    }
</script>

<?php require VIEWS_PATH."FooterUserView.php";?>