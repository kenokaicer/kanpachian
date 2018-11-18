<body class="Site"> <!--These two classes are needed for the footer to stick to the bottom of the page-->
<main class="Site-content">
  
<body style="background-color: #dcdcdc;">
<form method="POST">
<div class="login-box" style="background-color: #f6f6f6;">
  <div class="row collapse expanded">
    <div class="small-12 medium-6 column small-order-2 medium-order-1">
      <div id="papa" class="login-box-form-section">
        <h1 class="login-box-title">Ingresa con tu usuario</h1>
        <input class="login-box-input" type="text" name="username" placeholder="Usuario" required/>
        <input class="login-box-input" type="password" name="password" placeholder="Contraseña" id="pass1" required/>
        <input class="login-box-submit-button" type="submit" name="signup_submit" value="Ingresar" formaction="<?=FRONT_ROOT?>Account/sessionStart"/>
        <br>O<br><br>
        <a href="<?=FRONT_ROOT?>Account/registerUser" class="login-box-social-button-facebook">Registre una nueva cuenta</a>
      </div>
      <div class="or" style="position:fixed;top: 30%;left: 50%;z-index: 99">O</div>
    </div>
    <div class="small-12 medium-6 column small-order-1 medium-order-2 login-box-social-section">
      <div class="login-box-social-section-inner">
        <span class="login-box-social-headline">Loguese con<br />su red social</span>
        <a class="login-box-social-button-facebook">Loguearse con Facebook</a>
        <a class="login-box-social-button-twitter">Loguearse con Twitter</a>
        <a class="login-box-social-button-google">Loguearse con Google+</a>
      </div>
    </div>
  </div>
  <button type="submit" formnovalidate formaction="<?=FRONT_ROOT?>Home/index">Volver</button>
</div>
</form>

<?php require VIEWS_PATH."FooterUserView.php";?>