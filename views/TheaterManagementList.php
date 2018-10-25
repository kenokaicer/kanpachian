<div class="wrapper">
    <section>
        <table>
            <thead>
                <th>Nombre</th>
                <th>Ubicación</th>
                <th>Capacidad Máxima</th>
                <th>Editar</th>
                <th>Eliminar</th>
            </thead>
            <tbody>
            <?php
            if (!empty($theaterList)) {
                foreach ($theaterList as $key => $theater) {  
            ?>
                <tr>
                    <td><?= $theater['name'] ?></td>
                    <td><?= $theater['location'] ?></td>
                    <td><?= $theater['maxCapacity'] ?></td>
                    <td>
                    <form action="<?=FRONT_ROOT?>TheaterManagement/viewEditTheater" method="post">
                            <input type="hidden" name="id" value="<?=$theater['idTheater']?>">
                            <input type="hidden" name="name" value="<?=$theater['name']?>">
                            <input type="hidden" name="location" value="<?=$theater['location']?>">
                            <input type="hidden" name="maxCapacity" value="<?=$theater['maxCapacity']?>">
                            <input type="submit" value="Editar">
                        </form>
                    </td>
                    <td>
                        <form action="<?=FRONT_ROOT?>TheaterManagement/deleteTheater" method="post">
                            <input type="hidden" name="id" value="<?=$theater['idTheater']?>">
                            <input type="submit" value="Eliminar">
                        </form>
                    </td>
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
            <button type="submit" formaction="<?=FRONT_ROOT?>TheaterManagement/index">Volver</button>
        </form>
    </section>
</div>
