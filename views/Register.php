<div class="wrapper"> 
<h3>Registro</h3>
<form method="POST" onsubmit="return (checkPassword() &&  checkEmail());">
    <section>
        <table>
            <tr><td>Usuario: <input type="text" name="username" required></td></tr>
            <tr><td>Contraseña: <input type="password" name="password" id="pass1" required></td></tr>
            <tr><td>Repita la contraseña: <input type="password" name="password_confirm" id="pass2" required></td></tr>
            <tr><td>Email: <input type="email" name="email" id="email1" required></td></tr>
            <tr><td>Repita Email: <input type="email" id="email2" required></td></tr>
            <tr><td>Nombre: <input type="text" name="name" required></td></tr>
            <tr><td>Apellido: <input type="text" name="lastname" required></td></tr>
            <tr><td>DNI: <input type="text" name="dni" required></td></tr>
        </table>
    </section>
    <section>
        <div><button type="submit" formaction="<?=FRONT_ROOT?>Account/addUser">Registrar</button></div>
        <div><button type="submit" formnovalidate formaction="<?=FRONT_ROOT?>Home/index">Volver</button></div>
    </section>
</form>
</div>

<script language='javascript' type='text/javascript'>
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

    function checkEmail() {
    var pass1 = document.getElementById("email1").value;
    var pass2 = document.getElementById("email2").value;
    var ok = true;
    if (pass1 != pass2) {
        alert("Los email no coinciden");
        document.getElementById("email1").style.borderColor = "#E34234";
        document.getElementById("email2").style.borderColor = "#E34234";
        ok = false;
    }
    else {
        //alert("emails Match!!!");
        document.getElementById("email2").setAttribute("disabled", true);
    }
    return ok;
    }
</script>