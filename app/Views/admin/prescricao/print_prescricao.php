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
                            <?php
                            if($prescricao['idTabSismicrob_Indicacao'] != 1) {
                            ?>
                            <div class="row">
                                <div class="col">
                                    <b>De: </b><?= $prescricao['DataInicioTratamento'] ?> <b>até</b> <?= $prescricao['DataFimTratamento'] ?>
                                </div>
                                <div class="col">
                                    <b>Duração:</b> <?= $prescricao['Duracao'] ?> dia(s)
                                </div>
                            </div>
                            <?php
                            }
                            ?>
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
                                <div class="col-4">
                                    <b>Peso:</b> <?= $prescricao['Peso'] ?> kg
                                </div>
                                <div class="col-3">
                                    <b>Creatinina:</b> <?= $prescricao['Creatinina'] ?> mg/dL
                                </div>
                                <div class="col">
                                    <b>Filtração Glomerular:</b> <?= $prescricao['Clearance'] ?> mL/min/1.73m²
                                </div>                                
                            </div>    

                            <div class="row">
                                <div class="col-4">
                                    <b><?= $mascara['DoseAtaque'] ?>:</b> <?= ($prescricao['DoseAtaque']) == 'S' ? 'Sim' : 'Não' ?>
                                </div>
                                <div class="col-3">
                                    <b>Hemodiálise:</b> <?= ($prescricao['Hemodialise']) == 'S' ? 'Sim' : 'Não' ?>
                                </div>
                                <div class="col"><b>Sepse:</b> 
                                    <?php 
                                        if ($prescricao['Sepse'] == 'S') 
                                            echo 'Sim';
                                        elseif ($prescricao['Sepse'] == 'C')  
                                            echo 'Choque Séptico';
                                        else 
                                            echo 'Não';
                                    ?>
                                </div>
                            </div>
                            <br>                            
                        </div>
                    </div>

                    <div class="row">
                        <div class="col container border border-dark">
                        
                            <?php   
                                if ( (!empty(array_intersect(array_keys($_SESSION['Sessao']['Perfil']), [1,9]))) || 
                                ( $prescricao['idSishuap_Usuario'] == $_SESSION['Sessao']['idSishuap_Usuario'] ) ) {
                                    $prescricao['NomePrescritor'];
                                    $prescricao['Conselho'];
                                }
                                else {
                                    $prescricao['NomePrescritor'] = '**********';
                                    $prescricao['Conselho'] = '**********';
                                }
                            ?>
                            <div class="col fs-6 p-2">
                                <br>
                                    <b>Criada em:</b> <?= $prescricao['DataPrescricao'] ?> ||
                                    <b>Prescritor:</b> <?= $prescricao['NomePrescritor'] ?> ||
                                    <b>Conselho:</b> <?= $prescricao['Conselho'] ?>
                                <br>
                                <?php
                                    if ($prescricao['Avaliacao'] != 'P') {
                                ?>
                                <br>
                                    <b>Avaliada em:</b> <?= ($prescricao['DataAvaliacao']) ? $prescricao['DataAvaliacao'] : 'NÃO REGISTRADO' ?> ||
                                    <b>Avaliador:</b> <?= ($prescricao['NomeAvaliador']) ? $prescricao['NomeAvaliador'] : 'NÃO REGISTRADO' ?> ||
                                    <b>Conselho:</b> <?= ($prescricao['Conselho2']) ? $prescricao['Conselho2'] : 'NÃO REGISTRADO' ?>
                                <br>
                                <?php } ?>
                            </div>
                            <br>
                        </div>
                    </div>    
                    
                    <?php
                    if ($prescricao['Avaliacao'] != 'P') {
                    ?>                    
                    
                    <div class="row">
                        <div class="col container border border-dark">
                        
                            <div class="col-12 fs-6">
                                <br>
                                    <b>Avaliação: <?= ($prescricao['Avaliacao'] == 'S') ? 'CONFORME' : 'NÃO CONFORME' ?></b>
                            </div>
                            
                            <div class="col-12 fs-6">
                                <div class="col-12 text-center"><b>JUSTIFICATIVAS</b></div>
                            </div>
                            <br>
                            <?php
                            $i=0;
                                if ($prescricao['AvaliacaoDoseObs']) {
                                    $i++;
                                ?>     
                                <div class="col fs-6">
                                        <b>Dose:</b> <?= $prescricao['AvaliacaoDoseObs'] ?>
                                </div>
                                <?php
                                }
                                if ($prescricao['AvaliacaoDuracaoObs']) {
                                    $i++;
                                ?>     
                                <div class="col fs-6">
                                        <b>Duração:</b> <?= $prescricao['AvaliacaoDuracaoObs'] ?>
                                </div>
                                <?php
                                }
                                if ($prescricao['AvaliacaoIntervaloObs']) {
                                    $i++;
                                ?>     
                                <div class="col fs-6">
                                        <b>Intervalo:</b> <?= $prescricao['AvaliacaoIntervaloObs'] ?>
                                </div>
                                <?php
                                }
                                if ($prescricao['AvaliacaoIndicacaoObs']) {
                                    $i++;
                                ?>     
                                <div class="col fs-6">
                                        <b>Indicação:</b> <?= $prescricao['AvaliacaoIndicacaoObs'] ?>
                                </div>
                                <?php
                                }
                                if ($prescricao['AvaliacaoPreenchimentoInadequadoObs']) {
                                    $i++;
                                ?>     
                                <div class="col fs-6">
                                        <b>Preenchimento Inadequado:</b> <?= $prescricao['AvaliacaoPreenchimentoInadequadoObs'] ?>
                                </div>
                                <?php
                                }
                                if ($prescricao['AvaliacaoOutrosObs']) {
                                    $i++;
                                ?>     
                                <div class="col fs-6">
                                        <b>Observações:</b> <?= nl2br($prescricao['AvaliacaoOutrosObs']) ?>
                                </div>
                                <?php
                                }             
                                if ($i==0) {
                                    $i++;
                                ?>     
                                <div class="col-12 fs-6">
                                    <div class="col-12 text-center"><b>Nenhuma justificativa registrada</b></div>
                                </div>   
                                <?php
                                }                                                                                                                                                       
                            ?>                                                                                
                            <br />
                        </div>
                    </div>                       

                <?php
                }
                else {
                ?> 
                    <div class="row">
                        <div class="col container border border-dark">
                            <b>
                                <div class="col fs-6 text-center p-2">
                                    Aguardando avaliação
                                    <br>
                                </div>
                            </b>
                        </div>
                    </div>
                <?php } ?>
            </td>
        </tr>

    </thead>

    <tbody>



    </tbody>

    <tfoot>

    </tfoot>

</table>

<?= $this->endSection() ?>
