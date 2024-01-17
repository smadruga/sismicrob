<?= $this->extend('layouts/main_content') ?>
<?= $this->section('subcontent') ?>
<?= $this->include('layouts/sidenavbar_paciente') ?>

<main class="container col rounded ms-2 p-4">

    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th class="col">Solicitação</th>
                <th class="col">Data Coleta</th>
                <th class="col">Data Última Movimentação</th>
                <th class="col-3">Exame</th>
                <th class="col-4">Observação</th>
                <th scope="col">TSA</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach($cultura['array'] as $v) {
                $detalhe = (str_contains($v['obs'], 'POSITIV')) ? '<a href="'.base_url('prescricao/show_tsa/'.$v['seq_solic_ex'].'/'.$v['ordem']).'" type="button" class="btn btn-warning"><b><i class="fa-solid fa-square-plus"></i> Detalhes</b></a>' : NULL;
                echo '
                    <tr>
                        <td>'.$v['seq_solic_ex'].'</td>
                        <td>'.$v['dt_coleta'].'</td>
                        <td>'.$v['dt_ult_mov'].'</td>
                        <td>'.$v['exame'].'</td>
                        <td>'.$v['obs'].'</td>
                        <td>'.$detalhe.'</td>
                    </tr>
                ';
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5" class="text-center">Total: <?= $cultura['count'] ?> resultados.</th>
            </tr>
        </tfoot>
    </table>

</main>

<?= $this->endSection() ?>
