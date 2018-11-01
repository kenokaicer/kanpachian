<div class="wrapper"> 
<h3>Login</h3>
<form method="POST">
    <section>
        <table>
            <tr><td>Usuario: <input type="text" name="username" required></td></tr>
            <tr><td>Contraseña: <input type="password" name="password" required></td></tr>
        </table>
    </section>
    <section>
        <div><button type="submit" formaction="<?=FRONT_ROOT?>Account/sessionStart">Ingresar</button></div>
        <div><button type="submit" formaction="<?=FRONT_ROOT?>Home/index">Volver</button></div>
    </section>
</form>
<form action="">
<div><button type="submit" formaction="<?=FRONT_ROOT?>Account/registerUser">Registrar</button></div></form>
</div>