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
                            <button type="submit">Agregar</button>
                            <input type="submit" value="Volver" formaction="<?=FRONT_ROOT?>CategoryManagement/index" formnovalidate>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </section> 
</div>