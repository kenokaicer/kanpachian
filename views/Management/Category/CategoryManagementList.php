<body style="background-image: url('<?=IMG_PATH?>adminBackground.jpg');">
<div class="wrapper">
    <section>
        <table>
            <thead>
                <th>Categoría</th>
                <th style="width:15%">Editar</th>
                <th style="width:15%">Eliminar</th>
            </thead>
            <tbody>
            <?php
            if (!empty($categoryList)) {
                foreach ($categoryList as $category) {  
                    $categoryValuesArray = $category->getAll();
            ?>
                <tr>
                    <form method="post">
                        <?php 
                            foreach ($categoryValuesArray as $attribute => $value) { //print all attributes from object in a td each
                                if($attribute=="idCategory"){
                        ?>
                                    <input type="hidden" name="idCategory" value="<?=$value?>">
                        <?php 
                                }else{
                                    echo "<td>";
                                    echo $value;
                                    echo "</td>";
                                }
                            }
                        ?>
                        <td>
                            <input type="submit" value="Editar" formaction="<?=FRONT_ROOT?>CategoryManagement/viewEditCategory"> 
                        </td>
                        <td>
                            <input type="submit" value="Eliminar" formaction="<?=FRONT_ROOT?>CategoryManagement/deleteCategory">
                        </td>
                    </form>
                </tr>
            <?php
                }
            }
            ?>
            </tbody>
        </table>
    </section>
    <section>
        <form method="post">
            <button type="submit" formaction="<?=FRONT_ROOT?>CategoryManagement/index">Volver</button>
        </form>
    </section>
</div>