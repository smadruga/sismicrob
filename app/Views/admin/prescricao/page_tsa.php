<?= $this->extend('layouts/main_content') ?>
<?= $this->section('subcontent') ?>
<?= $this->include('layouts/sidenavbar_paciente') ?>

<main class="container col rounded ms-2 p-4">

    <div class="container text-center border">
        <div class="row">
            <div class="col text-start">
                <b>Atendimento:</b> <?= $tsa['cabecalho']['atd_seq'] ?>
            </div>
            <div class="col text-start">
                <b>Solicitação:</b> <?= $tsa['cabecalho']['seq_solic_ex'] ?>
            </div>
        </div>

        <div class="row">
            <div class="col text-start">
                <b>Unidade Solicitante:</b> <?= $tsa['cabecalho']['unidade_solicitante'] ?>
            </div>
        </div>

        <div class="row">
            <div class="col text-start">
                <b>Data da Coleta:</b> <?= $tsa['cabecalho']['dt_coleta'] ?>
            </div>
            <div class="col text-start">
                <b>Última movimentação:</b> <?= $tsa['cabecalho']['dt_ult_mov'] ?>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col text-start">
                <b>Exame:</b> <?= $tsa['cabecalho']['exame'] ?>
            </div>
        </div>

        <div class="row">
            <div class="col text-start">
                <b>Resultado:</b><br> <?= $tsa['cabecalho']['descricao'] ?>
            </div>
        </div>

    </div>

    <br>

    <div class="container text-center border">

        <div class="row">
            <div class="col">
                <b>* TSA - TESTE DE SENSIBILIDADE AOS ANTIMICROBIANOS - Simples conferência *</b><br>

            </div>
        </div>

    </div>

    <br>

    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th class="col">ANTIMICROBIANO</th>
                <th class="col">RESULTADO</th>
                <th class="col">MIC</th>
            </tr>
        </thead>
        <tbody>
            <?php
                /*$i=0;
                while($i=0) {*/
                for($i=0;$i<count($tsa['antimicrobiano'][20113]);$i++) {
                    #$mic = (isset($tsa['mic'][$tsa['antimicrobiano'][20113][$i]['pcl_seqp']]['descricao'])) ? $tsa['mic'][$tsa['antimicrobiano'][20113][$i]['pcl_seqp']]['descricao'] : NULL;
                    $mic = (isset($tsa['mic'][$tsa['mic_ordem'][$i]])) ? $tsa['mic'][$tsa['mic_ordem'][$i]]['descricao'] : NULL;
                    $result = (isset($tsa['antimicrobiano'][20133][$i]['descricao'])) ? $tsa['antimicrobiano'][20133][$i]['descricao'] : NULL;
                    echo '
                        <tr>
                            <td>'.$tsa['antimicrobiano'][20113][$i]['descricao'].'</td>
                            <td>'.$result.'</td>
                            <td>'.$mic.'</td>
                        </tr>
                    ';
                }
            ?>
        </tbody>
    </table>


</main>

<?= $this->endSection() ?>