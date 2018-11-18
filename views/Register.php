<body class="Site"> <!--These two classes are needed for the footer to stick to the bottom of the page-->
<main class="Site-content">
  
<body style="background-color: #dcdcdc;">
<form method="POST" onsubmit="return (checkPassword() && checkEmail() && checkUsername());">
<div class="login-box" style="background-color: #f6f6f6;">
  <div class="row collapse expanded">
    <div class="small-12 medium-6 column small-order-2 medium-order-1">
      <div id="papa" class="login-box-form-section">
        <h1 class="login-box-title">Registrese</h1>
        <input class="login-box-input" type="text" name="username" placeholder="Usuario" id="username" required/>
        <input class="login-box-input" type="password" name="password" placeholder="Contraseña" id="pass1" required/>
        <input class="login-box-input" type="password" placeholder="Repita la contraseña" id="pass2" required/>
        <input class="login-box-input" type="email" name="email" placeholder="E-mail" id="email1" required/>
        <input class="login-box-input" type="email" placeholder="Repita E-mail" id="email2" required/>
        <input class="login-box-input" type="text" name="name" placeholder="Nombre" required/>
        <input class="login-box-input" type="text" name="lastname" placeholder="Apellido" required/>
        <input class="login-box-input" type="text" name="dni" placeholder="DNI" required/>
        <input class="login-box-submit-button" type="submit" name="signup_submit" value="Resistrar" formaction="<?=FRONT_ROOT?>Account/addUser" id="registerButton"/>
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
  <button type="submit" formnovalidate formaction="<?=FRONT_ROOT?>Home/index">Volver</button>
</div>
</form>

<?php require VIEWS_PATH."FooterUserView.php";?>

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

    $(document).ready(function(){
      $("#username").focusout(function(){
        ajaxQuery("usernameExist",this.value);
      });
    });

    function ajaxQuery(func,value)
    {
      return $.ajax({ //return needed for when jquery
          url : <?=FRONT_ROOT?>+'controllers/Ajax/AccountManagementAjax.php', // requesting a PHP script
          type: 'post',
          dataType : 'json',
          data: {"function": func, "value": value}, //name of function to call in php file (this is a string passed by post and then checked in an if statement)
          success : function (data) 
          { // data contains the PHP script output
          console.log(data);
              if(data==true){
                alert('El usuario ya existe');
                document.getElementById("username").style.borderColor = "#E34234";
                document.getElementById("registerButton").disabled = true; 
              }else{
                document.getElementById("username").style.borderColor = "green";
                document.getElementById("registerButton").disabled = false;   
              }
          },
      })
    }
</script>