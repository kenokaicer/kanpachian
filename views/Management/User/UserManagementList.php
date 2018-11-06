<body style="background-image: url('<?=IMG_PATH?>adminBackground.jpg');">
<div class="wrapper">
    <section>
        <table>
            <thead>
                <th>Nombre de Usuario</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Editar</th>
                <th>Eliminar</th>
            </thead>
            <tbody>
            <?php
            if (!empty($userList)) {
                foreach ($userList as $user) {  
                    $userValuesArray = $user->getAll();
            ?>
                <tr>
                    <form method="post">
                        <?php 
                            foreach ($userValuesArray as $attribute => $value) { //print all attributes from object in a td each
                                if($attribute=="idUser"){
                        ?>
                                    <input type="hidden" name="idUser" value="<?=$value?>">
                        <?php 
                                }else if($attribute!="password"){
                                    echo "<td>";
                                    echo $value;
                                    echo "</td>";
                                }
                            }
                        ?>
                        <td>
                            <input type="submit" value="Editar" formaction="<?=FRONT_ROOT?>UserManagement/viewEditUser"> 
                        </td>
                        <td>
                            <input type="submit" value="Eliminar" formaction="<?=FRONT_ROOT?>UserManagement/deleteUser">
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
            <button type="submit" formaction="<?=FRONT_ROOT?>UserManagement/index">Volver</button>
        </form>
    </section>
</div>