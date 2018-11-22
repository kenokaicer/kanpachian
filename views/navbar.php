<nav id="navbar" class="sticky" style="background-image: url('<?=IMG_PATH?>navbar.png');">
  <a href="<?=FRONT_ROOT?>Home">
    <div id="navbar-home"></div>
  </a>
  <div id="topbar-responsive" class="topbar-responsive-links topbar-responsive">
    <?php
      if(isset($_SESSION["userLogged"]) && $_SESSION["userLogged"]->getRole()=="Admin"){
      }else{
    ?>
      <div style="max-width: 80vw;max-height:50px" class="top-bar-left left" > <!--style for if bar is longer than screen not working-->
        <ul class="menu simple vertical medium-horizontal">
          <?php
            $eventsByCategory = \controllers\PurchaseController::getCategoryList();
            if(isset($eventsByCategory) && !empty($eventsByCategory)){
              foreach ($eventsByCategory as $category => $eventArray) {              
          ?>
            <li>
              <div class="dropdown">
                <button type="input" class="dropdown-category-button button hollow topbar-responsive-button"><?=$category?></button>
                <div class="dropdown-content" style="background-image: url('<?=IMG_PATH?>navbar-menu-middle-left.jpg');">
                  <?php
                  foreach ($eventArray as $event) {
                  ?>
                    <a class="a-dropdown" href="<?=FRONT_ROOT."Purchase/index/?idEvent=".$event->getIdEvent() ?>"><?=$event->getEventName()?></a>
                  <?php
                  }
                  ?>
            </div>
              </div>
            </li>
          <?php
              }
            }else{
          ?>
            <li><a href="#">Categorías</a></li>
          <?php
            }
          ?>
        </ul>
      </div>
    <?php
      }
    ?>
    <div class="top-bar-right right">
      <form method="get">
        <ul class="menu vertical medium-horizontal" data-responsive-menu="drilldown medium-dropdown">
          <?php
          if(!isset($_SESSION["userLogged"])){
          ?>
          <li>
            <button type="input" formaction="<?=FRONT_ROOT?>Account/registerUser" class="button hollow topbar-responsive-button">Registrarse</button>
          </li>
          <li>
            <button type="input" formaction="<?=FRONT_ROOT?>Account/index" class="button hollow topbar-responsive-button" style="margin-left:0.8em">Acceder</button>
          </li>
          <?php
          }else if($_SESSION["userLogged"]->getRole()=="Admin"){
          ?>
          <li>
            <button type="input" formaction="<?=FRONT_ROOT?>Admin/index" class="button hollow topbar-responsive-button">Admin
              Menu</button>
          </li>
          <li>
            <button style="margin-left:0.8em" type="input" formaction="<?=FRONT_ROOT?>Account/sessionClose" class="button hollow topbar-responsive-button">Cerrar
              Sesión</button>
          </li>
          <?php
          }else{
          ?>
          <li><a id="user-name"><?php if(isset($_SESSION["clientName"])) echo $_SESSION["clientName"]; ?></a></li>
          <li>
            <div id="ex4"><span id="cart-number" class="p1 fa-2x has-badge" data-count="<?=sizeof($_SESSION["virtualCart"])?>"></span></div>
                <i id="cart-icon-navbar" class="fa fa-shopping-cart icon-button" aria-hidden="true"></i>
                <button formaction="<?=FRONT_ROOT?>Purchase/viewCart" type="input" id="cart-button" class="button hollow topbar-responsive-button">Ver Carrito</button>
          </li>
          <li>
            <div class="dropdown" style="margin-left:0.8em">
              <button type="button" class="button hollow topbar-responsive-button">Menú de Usuario</button>
              <div class="dropdown-content" style="background-image: url('<?=IMG_PATH?>navbar-menu-right.jpg');">
                  <a class="a-dropdown" href="<?=FRONT_ROOT?>Account/accountView?">Cuenta</a>
                  <a class="a-dropdown" href="<?=FRONT_ROOT?>Account/viewPurchases?">Ver Compras</a>
                  <a class="a-dropdown" href="<?=FRONT_ROOT?>Account/sessionClose?">Cerrar Sesión</a>
              </div>
            </div>
          </li>
          <?php
          }
          ?>
        </ul>
      </form>
    </div>
  </div>
</nav>


<script>
  window.onscroll = function () { myFunction() };

  var navbar = document.getElementById("navbar");

  var sticky = navbar.offsetTop;

  function myFunction() {
    if (window.pageYOffset >= sticky) {
      navbar.classList.add("sticky")
    } else {
      navbar.classList.remove("sticky");
    }
  }

  <?php
  if(isset($_SESSION["userLogged"]) && $_SESSION["userLogged"]->getRole() == "Common"){
  /*Set correct position to cart icon*/
  ?>
  var userWidth = document.getElementById('user-name').clientWidth;
  userWidth += 6;
  document.getElementById('cart-icon-navbar').style.left = userWidth+"px";
  <?php
  }
  ?>
</script>