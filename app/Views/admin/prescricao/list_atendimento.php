<?= $this->extend('layouts/main_content') ?>
<?= $this->section('subcontent') ?>
<?= $this->include('layouts/sidenavbar_paciente') ?>

<main class="container col rounded ms-2 p-4">

    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th scope="col">Data</th>
                <th scope="col">Origem</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach($atendimento['array'] as $v) {
                echo '
                <tr>
                    <td>'.$v['dt_consulta_formatada'].'</td>
                    <td>'.$v['origem'].'</td>
                </tr>
                ';
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5" class="text-center">Total: <?= $atendimento['count'] ?> resultados.</th>
            </tr>
        </tfoot>
    </table>

</main>

<?= $this->endSection() ?>
