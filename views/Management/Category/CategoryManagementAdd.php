<body style="background-image: url('<?=IMG_PATH?>adminBackground.jpg');">
<div class="wrapper">
    <section>
        <form action="<?=FRONT_ROOT?>CategoryManagement/addCategory" method="post">
            <table>
                <tr>
                    <td>Categoría: <input type="text" name="category" required></td>
                </tr>
                <tr>
                    <td>
                        <div>
                            <button class="button" type="submit">Agregar</button>
                            <input class="button" type="submit" class="button" value="Volver" formaction="<?=FRONT_ROOT?>CategoryManagement/index" formnovalidate>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </section> 
</div>