<?php
	namespace views;
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Guía 5</title>
        <style>
            *{
                text-align: center;
                box-sizing: border-box;
            }

            .wrapper{
                width: 800px;
                margin: 0 auto;
                border: 1px;
                border-style: solid;
            }
            #top{
                border: 1px;
                border-style: solid;
                margin: 5px;
            }
            #bottom{
                border: 1px;
                border-style: solid;
                margin: 5px;
            }
            #tabla-items{
                margin: 15px auto;
                border: 3px;
                border-style: solid;
            }
            td{
                border: 1px;
                border-style: solid;
            }
            #cabecera-factura{
                border-radius: 3px;
                font-size: 20px;
                margin: auto;
                width: 160px;
                background-color: #8B4B9B;
                color: white;
                font-weight: bold;
            }
        </style>
    </head>
    <body>
        <div class="wrapper">
            <form action="<?=BASE?>ArtistGestion/index" method="POST" id="form1">
                <section id="top">		
                    <input type="submit" value="Gestion Artists" /> 
                    <br><br>
                </section>
            </form>
			<form action="<?=BASE?>ArtistGestion/listarArtists" method="POST" id="form1">
                <section id="top">		
                    <input type="submit" value="Gestion Teatros" /> 
                    <br><br>
                </section>
            </form>
        </div>
    </body>
</html>