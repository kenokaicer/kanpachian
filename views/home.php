<?php
	namespace Views;
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Guía 5</title>
        <link rel="stylesheet" href="../css/teststyle.css">
    </head>
    <body>
        <div class="wrapper">
            <form action="<?=BASE?>ArtistManagement/index" method="POST" id="form1">
                <section id="top">		
                    <input type="submit" value="Management Artists" /> 
                    <br><br>
                </section>
            </form>
			<form action="<?=BASE?>ArtistManagement/artistList" method="POST" id="form1">
                <section id="top">		
                    <input type="submit" value="Management Teatros" /> 
                    <br><br>
                </section>
            </form>
        </div>
    </body>
</html>