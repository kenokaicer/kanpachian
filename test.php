<?php
require 'models\Calendar.php';

/*$c = new models\Calendar();
$c->setStartDate(date("Y/m/d"));
echo "Today is " . date("Y/m/d") . "<br>";

var_dump($c);*/

if($_POST){
    echo $_POST["date"]."   ";
    echo $_POST["tiempo"];
}
?>

<form action="" method="POST">
<input type="date" name="date" id="">
<input type="time" name="tiempo" id="">
<input type="submit" value="enviar">
</form>

