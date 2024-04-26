    <?= $this->extend('layouts/main_content') ?>
    <?= $this->section('subcontent') ?>
    <?php 
        if($layout == 'list')      
            echo $this->include('layouts/sidenavbar_paciente'); 
    ?>

    <div class="col border rounded ms-2 p-4">

        <?php
        if($prescricao['count'] <= 0) {
            echo '
            <div class="alert alert-dark" role="alert">
                Nenhuma prescrição registrada.
            </div>
            ';
        }

        foreach($prescricao['array'] as $v) {

            $mascara['Intervalo'] = 'Intervalo';
            $mascara['DoseDiaria'] = 'Dose diária';
            $mascara['DoseAtaque'] = 'Dose de ataque';    

            if($v['idTabSismicrob_Indicacao'] == 1) {
                $mascara['Intervalo'] = 'Intervalo para repique intraoperatório';
                $mascara['DoseDiaria'] = 'Dose diária - repique intraoperatório';
                $mascara['DoseAtaque'] = 'Dose de indução anestésica';
            }

        ?>

        <div class="accordion" id="accordion">
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading<?= $v['idSismicrob_Tratamento'] ?>">
                    <button class="accordion-button collapsed bg-info text-white" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $v['idSismicrob_Tratamento'] ?>" aria-expanded="false" aria-controls="collapse<?= $v['idSismicrob_Tratamento'] ?>">
                        <div class="container">
                            <div class="row">
                                <div class="col-9 text-start"><b> #<?= 
                                        $v['idSismicrob_Tratamento'] .' - '. $v['NomeMedicamento']
                                    ?>                                
                                </b></div>
                                <div class="col text-end">
                                    <?php
                                    if($v['Concluido'] == 1)
                                        echo '<span class="badge bg-primary text-white"><i class="fa-solid fa-check-circle"></i> Avaliação Pendente</span>';
                                    else
                                        echo '<span class="badge bg-warning text-white"><i class="fa-solid fa-triangle-exclamation"></i> Aberta</span>';
                                    ?>
                                </div>
                            </div>
                        </div>
                    </button>
                </h2>
                <div id="collapse<?= $v['idSismicrob_Tratamento'] ?>" class="accordion-collapse collapse" aria-labelledby="heading<?= $v['idSismicrob_Tratamento'] ?>">
                    <div class="accordion-body">
                        <div>
                            <?php if($layout == 'list') { ?>
                                <?php if($v['Concluido'] == 1) { ?>
                                    <a class="btn btn-outline-info" onclick="window.open(this.href).print(); return false" href="<?= base_url('prescricao/print_prescricao/'.$v['idSismicrob_Tratamento']) ?>" target="_blank" role="button"><i class="fa-solid fa-print"></i> Imprimir</a>
                                    <a class="btn btn-outline-info click" href="<?= base_url('prescricao/copy_prescricao/'.$v['idSismicrob_Tratamento']) ?>" role="button"><i class="fa-solid fa-copy"></i> Copiar</a>
                                    <!--<a class="btn btn-outline-info click" href="<?= base_url('prescricao/copy_prescricao/'.$v['idSismicrob_Tratamento'].'/1') ?>" role="button"><i class="fa-solid fa-repeat"></i> Continuar</a>-->
                                <?php } else { ?>
                                    <a class="btn btn-outline-warning" id="click" href="<?= base_url('prescricao/manage_prescricao/editar/'.$v['idSismicrob_Tratamento']) ?>" role="button"><i class="fa-solid fa-edit"></i> Editar</a>
                                    <a class="btn btn-outline-danger" id="click" href="<?= base_url('prescricao/manage_prescricao/excluir/'.$v['idSismicrob_Tratamento']) ?>" role="button"><i class="fa-solid fa-trash-can"></i> Excluir</a>
                            <?php } ?>
                                <hr>
                            <?php } ?>
                            
                        </div>

                        <div class="container">
                            <?php if($layout == 'assess') { ?>
                            <div class="row">
                                <div class="col">
                                    <b>Paciente:</b> <?= $v['NomePaciente'] ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <b>Prontuário:</b> <?= $v['Prontuario'] ?>
                                </div>                                
                                <div class="col">
                                    <b>Sexo:</b> <?= $v['Sexo'] ?>
                                </div>
                                <div class="col">
                                    <b>Data de Nascimento:</b> <?= $v['DataNascimento'] ?>
                                </div>
                            </div>
                            <hr>
                            <?php } ?>

                            <div class="row">
                                <div class="col"><b>Indicação:</b> <?= $v['Indicacao'] ?></div>
                            </div>

                            <?php
                            if($v['idTabSismicrob_Indicacao'] == 1) {
                            ?>
                            <div class="row">
                                <div class="col"><b>Tipo de Cirurgia:</b> <?= $v['IndicacaoTipoCirurgia'] ?></div>
                            </div>
                            <?php
                            }
                            elseif ($v['idTabSismicrob_Indicacao'] == 3) {
                            ?>
                            <div class="row">
                                <div class="col"><b>Diagnóstico Infeccioso:</b> <?= $v['DiagnosticoInfeccioso'] ?></div>
                                <?php if($v['idTabSismicrob_DiagnosticoInfeccioso'] == 7) { ?>
                                <div class="col"><b>Especificar:</b> <?= $v['DiagnosticoInfecciosoOutro'] ?></div>
                                <?php } ?>
                            </div>
                            <?php } ?>

                            <div class="row">
                                <div class="col"><b>Justificativa:</b> <br><?= nl2br($v['Justificativa']) ?><br></div>
                            </div>

                            <hr />

                            <div class="row">
                                <div class="col"><b>Medicamento:</b> <?= $v['NomeMedicamento'] ?></div>
                            </div>

                            <div class="row">
                                <div class="col"><b>De:</b> <?= $v['DataInicioTratamento'] ?> <b>até</b> <?= $v['DataFimTratamento'] ?></div>
                                <div class="col"><b>Duração:</b> <?= $v['Duracao'] ?> dia(s)</div>
                            </div>

                            <div class="row">
                                <div class="col"><b>Dose Posológica de Manutenção:</b> <?= $v['DosePosologica'] ?></div>
                                <div class="col"><b><?= $mascara['Intervalo'] ?>:</b> <?= $v['Intervalo'] ?></div>
                            </div>

                            <div class="row">
                                <div class="col"><b><?= $mascara['DoseDiaria'] ?>:</b> <?= $v['DoseDiaria'] ?></div>
                                <?php if($v['idTabSismicrob_Indicacao'] == 1) { ?>
                                    <div class="col"><b>Antibiótico após cirurgia:</b> <?= $v['AntibioticoMantido'] ?></div>
                                <?php } ?>
                            </div>

                            <div class="row">
                                <div class="col"><b>Via de Administração:</b> <?= $v['ViaAdministracao'] ?></div>
                                <div class="col"><b>Especialidade:</b> <?= $v['Especialidade'] ?></div>
                            </div>
                            
                            <br>

                            <div class="row">
                                <div class="col-3"><b>Peso:</b> <?= $v['Peso'] ?> kg</div>
                                <div class="col-3"><b>Creatinina:</b> <?= $v['Creatinina'] ?> mg/dL</div>
                                <div class="col"><b>Filtração Glomerular:</b> <?= $v['Clearance'] ?> mL/min/1.73m²</div>
                            </div>

                            <div class="row">
                                <div class="col"><b><?= $mascara['DoseAtaque'] ?>:</b> <?= ($v['DoseAtaque'] == 'S') ? 'Sim' : 'Não' ?></div>
                                <div class="col"><b>Hemodiálise:</b> <?= ($v['Hemodialise']) ? 'Sim' : 'Não' ?></div>
                            </div>
                            
                            <hr>

                            <div class="row">
                                <div class="col"><b>Criada em:</b> <?= $v['DataPrescricao'] ?></div>
                                <div class="col"><b>Prescritor:</b> <?= $v['NomePrescritor'] ?></div>
                                <div class="col"><b>Conselho:</b> <?= $v['Conselho'] ?></div>
                            </div>
                            <?php
                            if ($layout == 'assess' && $v['Avaliacao'] == 'P') {
                            ?>
                            <hr />
                            <div class="row">
                                <div class="col text-center">
                                    <a class="btn btn-warning btn-lg" href="<?= base_url('prescricao/assess_prescricao/'.$v['idSismicrob_Tratamento']) ?>" 
                                        role="button"><i class="fa-solid fa-scale-unbalanced-flip"></i> AVALIAR</a>
                                </div>
                            </div>

                            <br />
                            
                            <?php } ?>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <br />

        <?php
        }
        ?>

    </div>

    <?= $this->endSection() ?>
