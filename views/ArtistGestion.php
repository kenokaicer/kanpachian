<?php
    namespace views;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <div class="wrapper">
        <section>
            <form action="<?=BASE?>ArtistGestion/addArtist" method="post">
                <table>
                    <tr>
                        <td>name: <input type="text" name="name" required></td>
                        <td>Apellido: <input type="text" name="apellido"></td>
                    </tr>
                    <tr>
                        <td><button type="submit">Agregar</button></td>
                        <td><input type="submit" value="Volver" formaction="<?=BASE?>Main/index" formnovalidate></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                    </tr>
                </table>
            </form>
        </section>
    
    </div>
</body>
</html>

<?php

?>