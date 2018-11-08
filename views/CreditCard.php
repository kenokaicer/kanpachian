<div class="menuWrapper"> 
<h3>Registro de Tarjeta de Crédito</h3>
<form method="POST" onsubmit="return checkLenght();">
    <section>
        <table>
            <tr><td>Numero de Tarjeta: <input id="cardNumber" type="number" pattern="{16,16}" name="creditCardNumber" required></td></tr>
            <tr><td>Fecha de expiración: <input type="month" name="expirationDate" required></td></tr>
            <tr><td>Titular de la Tarjeta: <input type="text" name="cardHolder" required></td></tr>
        </table>
    </section>
    <section>
        <div><button type="submit" formaction="<?=FRONT_ROOT?>Account/registerCreditCard">Registrar</button></div>
        <div><button type="submit" formnovalidate formaction="<?=FRONT_ROOT?>Home/index">Volver</button></div>
    </section>
</form>
</div>

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