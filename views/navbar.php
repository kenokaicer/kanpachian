<nav id="navbar" class="sticky" style="background-image: url('<?=IMG_PATH?>navbar.png');height:50px">
  <a href="<?=FRONT_ROOT?>Home"><div style="width:300px;height:50px;float:left" ></div></a>
  <ul class="right medium-horizontal menu">
    <?php
    if(isset($_SESSION["userLogged"])){
      echo "<li><span><a>Usuario: " . $_SESSION["userLogged"]->getUsername()."</a></span></li>";
    }
    ?>
    <li><a href="<?=FRONT_ROOT?>Admin"><i class="fi-list"></i> <span>Admin</span></a></li>
    <li><a href="<?=FRONT_ROOT?>Account/sessionClose"><i class="fi-list"></i> <span>Session Close</span></a></li>
    <li><a href="<?=FRONT_ROOT?>Cart/index"><i class="fi-list"></i> <span>Carrito</span></a></li>
    <li><a href="<?=FRONT_ROOT?>Account/viewRegisterCreditCard"><i class="fi-list"></i> <span>Four</span></a></li>
  </ul>
</nav>

<style type="text/css">	
	 /* Style the navbar */

body { padding-top: 70px; }

#navbar {
  overflow: hidden;
  background-color: #333;
}

/* Navbar links */
#navbar a {
  float: left;
  display: block;
  color: #f2f2f2;
  text-align: center;
  padding: 14px;
  text-decoration: none;
}

/* Page content */
.content {
  padding: 16px;
}

/* The sticky class is added to the navbar with JS when it reaches its scroll position */
.sticky {
  position: fixed;
  top: 0;
  width: 100%;
  z-index: 99;
}

/* Add some top padding to the page content to prevent sudden quick movement (as the navigation bar gets a new position at the top of the page (position:fixed and top:0) */
.sticky + .content {
  padding-top: 60px;

} 
</style>

<script type="text/javascript">
    
    window.onscroll = function() {myFunction()};

// Get the navbar
var navbar = document.getElementById("navbar");

// Get the offset position of the navbar
var sticky = navbar.offsetTop;

// Add the sticky class to the navbar when you reach its scroll position. Remove "sticky" when you leave the scroll position
function myFunction() {
  if (window.pageYOffset >= sticky) {
    navbar.classList.add("sticky")
  } else {
    navbar.classList.remove("sticky");
  }
} 
</script>
