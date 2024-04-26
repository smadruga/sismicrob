<?= $this->extend('layouts/main_admin') ?>
<?= $this->section('content') ?>

<?php

$mascara['Intervalo'] = 'Intervalo';
$mascara['DoseDiaria'] = 'Dose diária';
$mascara['DoseAtaque'] = 'Dose de ataque';    

if($prescricao['idTabSismicrob_Indicacao'] == 1) {
    $mascara['Intervalo'] = 'Intervalo para repique intraoperatório';
    $mascara['DoseDiaria'] = 'Dose diária - repique intraoperatório';
    $mascara['DoseAtaque'] = 'Dose de indução anestésica';
}

?>

<table class="table ms-1" style="width:270mm;font-size:70%;">
    <thead>
        <tr>
            <td class="border-0" colspan="12">

                <div class="ms-1 me-1">
                    <div class="row">
                        <div class="col container border border-dark">
                            <b>
                                <div class="row">
                                    <div class="col-2 ps-3 pt-2">
                                        <img src="<?= base_url('/assets/img/huap.png') ?>" width="60%" /><br />
                                        <img src="<?= base_url('/assets/img/ebserh.png') ?>" width="60%" />
                                    </div>
                                    <div class="col pt-2 fs-6">
                                        HOSPITAL UNIVERSITÁRIO ANTONIO PEDRO - HUAP<br />
                                        GERÊNCIA DE ATENÇÃO A SAÚDE - GAS<br />
                                        DIVISÃO DE GESTÃO DO CUIDADO - DGC<br />
                                        COMISSÃO DE CONTROLE DE INFECÇÕES HOSPITALARES - CCIH<br />
                                        SETOR DE FARMÁCIA HOSPITALAR - SFH
                                    </div>
                                </div>
                            </b>
                        </div>
                    </div>  

                    <div class="row">
                        <div class="col container border border-dark">
                            <b>
                                <div class="col fs-6 text-center p-2">
                                    REQUISIÇÃO DE ANTIMICROBIANO
                                </div>
                            </b>
                        </div>
                    </div>  
                    
                    <div class="row fs-6">
                        <div class="container border border-dark">
                            <div class="row">
                                <div class="col">
                                    <b>Paciente:</b> <?= $prescricao['NomePaciente'] ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <b>Prontuário:</b> <?= $prescricao['Prontuario'] ?>
                                </div>                                
                                <div class="col">
                                    <b>Sexo:</b> <?= $prescricao['Sexo'] ?>
                                </div>
                                <div class="col">
                                    <b>Data de Nascimento:</b> <?= $prescricao['DataNascimento'] ?>
                                </div>
                            </div>

                            <hr>

                            <div class="row">
                                <div class="col">
                                    <b>Indicação:</b> <?= $prescricao['Indicacao'] ?>
                                </div>
                            </div>
                            <?php if($prescricao['idTabSismicrob_Indicacao'] == 1) { ?>
                            <div class="row">
                                <div class="col">
                                    <b>Tipo de Cirurgia:</b> <?= $prescricao['IndicacaoTipoCirurgia'] ?>
                                </div>
                            </div>
                            <?php } ?>
                            
                            <?php if($prescricao['idTabSismicrob_Indicacao'] == 3) { ?>
                            <div class="row">
                                <div class="col">
                                    <b>Diagnóstico Infeccioso:</b> <?= $prescricao['DiagnosticoInfeccioso'] ?>
                                </div>
                                <?php if($prescricao['idTabSismicrob_DiagnosticoInfeccioso'] == 7) { ?>
                                <div class="col">
                                    <b>Especificar:</b> <?= $prescricao['DiagnosticoInfecciosoOutro'] ?>
                                </div>
                                <?php } ?>
                            </div>
                            <?php } ?>

                            <div class="row">
                                <div class="col">
                                    <b>Justificativa:</b> <br><?= nl2br($prescricao['Justificativa']) ?><br>
                                </div>
                            </div>                           
                            
                            <hr>

                            <div class="row">
                                <div class="col">
                                    <b>Medicamento: <?= $prescricao['NomeMedicamento'] ?></b>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <b>De: </b><?= $prescricao['DataInicioTratamento'] ?> <b>até</b> <?= $prescricao['DataFimTratamento'] ?>
                                </div>
                                <div class="col">
                                    <b>Duração:</b> <?= $prescricao['Duracao'] ?> dia(s)
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <b>Dose Posológica de Manutenção:</b> <?= $prescricao['DosePosologica'] ?>
                                </div>
                                <div class="col">
                                    <b><?= $mascara['Intervalo'] ?>:</b> <?= $prescricao['Intervalo'] ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <b><?= $mascara['DoseDiaria'] ?>:</b> <?= $prescricao['DoseDiaria'] ?>
                                </div>
                                <?php if($prescricao['idTabSismicrob_Indicacao'] == 1) { ?>
                                <div class="col">
                                    <b>Antibiótico após cirurgia:</b> <?= $prescricao['AntibioticoMantido'] ?>
                                </div>
                                <?php } ?>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <b>Via de Administração:</b> <?= $prescricao['ViaAdministracao'] ?>
                                </div>
                                <div class="col">
                                    <b>Especialidade:</b> <?= $prescricao['Especialidade'] ?>
                                </div>
                            </div>                            

                            <hr>

                            <div class="row">
                                <div class="col">
                                    <b>Peso:</b> <?= $prescricao['Peso'] ?> kg
                                </div>
                                <div class="col-4">
                                    <b>Creatinina:</b> <?= $prescricao['Creatinina'] ?> mg/dL
                                </div>
                                <div class="col-5">
                                    <b>Filtração Glomerular:</b> <?= $prescricao['Clearance'] ?> mL/min/1.73m²
                                </div>                                
                            </div>    

                            <div class="row">
                                <div class="col">
                                    <b><?= $mascara['DoseAtaque'] ?>:</b> <?= ($prescricao['DoseAtaque']) == 'S' ? 'Sim' : 'Não' ?>
                                </div>
                                <div class="col-5">
                                    <b>Hemodiálise:</b> <?= ($prescricao['Hemodialise']) == 'S' ? 'Sim' : 'Não' ?>
                                </div>
                            </div>                                



                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col container border border-dark">
                            <b>
                                <div class="col fs-6 text-center p-2">
                                    RESPONSÁVEL PELA PRESCRIÇÃO
                                </div>
                            </b>
                        </div>
                    </div>  

                    <div class="row">
                        <div class="col container border border-dark">
                        
                            <div class="col fs-6 p-2">
                                <br>
                                    <b>Criada em:</b> <?= $prescricao['DataPrescricao'] ?> ||
                                    <b>Prescritor:</b> <?= $prescricao['NomePrescritor'] ?> ||
                                    <b>Conselho:</b> <?= $prescricao['Conselho'] ?>
                                    <!--<br>
                                    <b>Concluída em:</b> <?= $prescricao['DataConclusao'] ?> ||
                                    <b>Concluída por:</b> <?= $prescricao['NomeResponsavel'] ?> ||
                                    <b>Conselho:</b> <?= $prescricao['Conselho1'] ?>-->
                                <br><br>
                            </div>
                            
                        </div>
                    </div>                      

                </div>
            </td>
        </tr>

    </thead>

    <tbody>



    </tbody>

    <tfoot>

    </tfoot>

</table>

<?= $this->endSection() ?>
