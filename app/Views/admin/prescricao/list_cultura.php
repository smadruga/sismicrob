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
                <th class="col-4">Resultado</th>
                <th scope="col">TSA</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach($cultura['array'] as $v) {

                if(str_contains($v['obs'], 'POSITIV'))
                    if(isset($cultura['tsa'][$v['seq_solic_ex']][($v['ordem']+1)]))
                        $detalhe = '<a href="'.base_url('prescricao/show_tsa/'.$v['seq_solic_ex'].'/'.$v['ordem']).'" 
                            type="button" class="btn btn-success btn-sm"><b><i class="fa-solid fa-square-plus"></i> Detalhes</b></a>';
                    else
                        $detalhe = '<button type="button" class="btn btn-warning btn-sm" disabled><b><i class="fa-solid fa-circle-exclamation"></i> Área Executora</b></button>';
                else
                    $detalhe = '';

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
