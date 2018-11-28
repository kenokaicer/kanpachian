<?php 
error_reporting(E_ALL ^ E_WARNING); //disable warnings in this page
//or
//error_reporting(E_ERROR | E_PARSE);?>

<div id="additional-info" style="padding-top:5px;height:70px">
    <div class="row">
        <div class="large-12 columns">
            <h2 class="shadow-header color-white headings text-center">Go To Event</h2>
        </div>
    </div>
</div>

<div id="intro" style="min-height:67.25vh">

        <div class="wrapper" style="border:none;display:inline-block">
            <section class="app-feature-section" style="border:none;border-radius: 25px;">
                <div style=""><strong>ERROR 404</strong></div>
                <h1>Oopps! Página no encontrada</h1>
                <form action="<?=FRONT_ROOT?>Home/index" method="get"><button type="submit">Volver</button></form>
                
                <div id="image1" style="border-radius: 25px;max-width:50vw;display:inline-block"></div>
            </section>
        </div>

</div>

<?php require VIEWS_PATH."FooterUserView.php";?>

<script>
function ajax_get(url, callback) {
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
      console.log('responseText:' + xmlhttp.responseText);
      try {
        var data = JSON.parse(xmlhttp.responseText);
      } catch (err) {
        console.log(err.message + " in " + xmlhttp.responseText);
        return;
      }
      callback(data);
    }
  };

  xmlhttp.open("GET", url, true);
  xmlhttp.send();
}

ajax_get('https://api.thecatapi.com/v1/images/search?size=full', function(data) {
  var html = '<img style="border-radius: 25px;max-width:50vw" src="' + data[0]["url"] + '">';
  document.getElementById("image1").innerHTML = html;
});
</script>



