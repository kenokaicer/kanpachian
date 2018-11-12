<body style="background-color: #dcdcdc;">
<form method="POST" onsubmit="return (checkPassword() &&  checkEmail());">
<div class="login-box" style="background-color: #f6f6f6;">
  <div class="row collapse expanded">
    <div class="small-12 medium-6 column small-order-2 medium-order-1">
      <div id="papa" class="login-box-form-section">
        <h1 class="login-box-title">Registrese</h1>
        <input class="login-box-input" type="text" name="username" placeholder="Usuario" required/>
        <input class="login-box-input" type="password" name="password" placeholder="Contraseña" id="pass1" required/>
        <input class="login-box-input" type="password" placeholder="Repita la contraseña" id="pass2" required/>
        <input class="login-box-input" type="email" name="email" placeholder="E-mail" id="email1" required/>
        <input class="login-box-input" type="email" placeholder="Repita E-mail" id="email2" required/>
        <input class="login-box-input" type="text" name="name" placeholder="Nombre" required/>
        <input class="login-box-input" type="text" name="lastname" placeholder="Apellido" required/>
        <input class="login-box-input" type="text" name="dni" placeholder="DNI" required/>
        <input class="login-box-submit-button" type="submit" name="signup_submit" value="Resistrar" />
      </div>
      <div class="or" style="position:fixed;top: 40%;left: 50%;z-index: 99">O</div>
    </div>
    <div class="small-12 medium-6 column small-order-1 medium-order-2 login-box-social-section" style="margin-top:125px">
      <div class="login-box-social-section-inner">
        <span class="login-box-social-headline">Registrese con<br />su red social</span>
        <a class="login-box-social-button-facebook">Loguearse con Facebook</a>
        <a class="login-box-social-button-twitter">Loguearse con Twitter</a>
        <a class="login-box-social-button-google">Loguearse con Google+</a>
      </div>
    </div>
  </div>
</div>
</form>


