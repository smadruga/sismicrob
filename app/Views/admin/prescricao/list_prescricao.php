<?= $this->extend('layouts/main_content') ?>
<?= $this->section('subcontent') ?>
<?= $this->include('layouts/sidenavbar_paciente') ?>

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
    ?>

    <div class="accordion" id="accordion">
        <div class="accordion-item">
            <h2 class="accordion-header" id="heading<?= $v['idPreschuap_Prescricao'] ?>">
                <button class="accordion-button collapsed bg-info text-white" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $v['idPreschuap_Prescricao'] ?>" aria-expanded="false" aria-controls="collapse<?= $v['idPreschuap_Prescricao'] ?>">
                    <div class="container">
                        <div class="row">
                            <div class="col-6 text-start"><b>Prescrição #<?= $v['idPreschuap_Prescricao'] ?></b></div>
                            <div class="col-6 text-end">
                                <?php
                                if($v['Concluido'] == 1)
                                    echo '<span class="badge bg-primary text-white"><i class="fa-solid fa-check-circle"></i> Fechada</span>';
                                else
                                    echo '<span class="badge bg-warning text-white"><i class="fa-solid fa-triangle-exclamation"></i> Aberta</span>';
                                ?>
                            </div>
                        </div>
                    </div>
                </button>
            </h2>
            <div id="collapse<?= $v['idPreschuap_Prescricao'] ?>" class="accordion-collapse collapse" aria-labelledby="heading<?= $v['idPreschuap_Prescricao'] ?>">
                <div class="accordion-body">
                    <div>
                        <?php if($v['Concluido'] == 1) { ?>
                            <a class="btn btn-outline-info" onclick="window.open(this.href).print(); return false" href="<?= base_url('prescricao/print_prescricao/'.$v['idPreschuap_Prescricao']) ?>" target="_blank" role="button"><i class="fa-solid fa-print"></i> Imprimir</a>
                            <a class="btn btn-outline-info click" href="<?= base_url('prescricao/copy_prescricao/'.$v['idPreschuap_Prescricao']) ?>" role="button"><i class="fa-solid fa-copy"></i> Copiar</a>
                            <!--<a class="btn btn-outline-info click" href="<?= base_url('prescricao/copy_prescricao/'.$v['idPreschuap_Prescricao'].'/1') ?>" role="button"><i class="fa-solid fa-repeat"></i> Continuar</a>-->
                        <?php } else { ?>
                            <a class="btn btn-outline-warning" id="click" href="<?= base_url('prescricao/manage_prescricao/editar/'.$v['idPreschuap_Prescricao']) ?>" role="button"><i class="fa-solid fa-edit"></i> Editar</a>
                            <a class="btn btn-outline-danger" id="click" href="<?= base_url('prescricao/manage_prescricao/excluir/'.$v['idPreschuap_Prescricao']) ?>" role="button"><i class="fa-solid fa-trash-can"></i> Excluir</a>
                            <a class="btn btn-outline-success" id="click" href="<?= base_url('prescricao/manage_prescricao/concluir/'.$v['idPreschuap_Prescricao']) ?>" role="button"><i class="fa-solid fa-check-circle"></i> Concluir</a>
                        <?php } ?>
                    </div>

                    <hr />

                    <div class="container">
                        <div class="row">
                            <!--<div class="col"><b>Data da Marcação:</b> <?= $v['DataMarcacao'] ?></div>-->
                            <div class="col"><b>Data da Prescrição:</b> <?= $v['DataPrescricao'] ?></div>
                        </div>

                        <div class="row">
                            <div class="col"><b>Dia:</b> <?= $v['Dia'] ?></div>
                            <div class="col"><b>Ciclo:</b> <?= $v['Ciclo'] ?></div>
                        </div>

                        <div class="row">
                            <div class="col"><b>Total de Ciclos:</b> <?= $v['CiclosTotais'] ?> ciclo(s)</div>
                            <div class="col"><b>Entre Ciclos:</b> <?= $v['EntreCiclos'] ?> dia(s)</div>
                        </div>

                        <div class="row">
                            <div class="col"><b>CID Categoria:</b> <?= $v['Categoria'] ?></div>
                        </div>

                        <div class="row">
                            <div class="col"><b>CID Subcategoria:</b> <?= $v['Subcategoria'] ?></div>
                        </div>

                        <div class="row">
                            <div class="col"><b>Aplicabilidade:</b> <?= $v['Aplicabilidade'] ?></div>
                            <div class="col"><b>Tipo de Terapia:</b> <?= $v['TipoTerapia'] ?></div>
                        </div>

                        <hr />

                        <div class="row">
                            <div class="col"><b>Peso:</b> <?= $v['Peso'] ?> kg</div>
                            <div class="col"><b>Altura:</b> <?= $v['Altura'] ?> cm</div>
                        </div>

                        <div class="row">
                            <div class="col"><b>Índice de Massa Corporal (IMC):</b> <?= $v['IndiceMassaCorporal'] ?> kg/m²</div>
                            <div class="col"><b>Superfície Corporal (SC):</b> <?= $v['SuperficieCorporal'] ?> m²</div>
                        </div>

                        <div class="row">
                            <div class="col"><b>Creatinina Sérica (ClSr):</b> <?= $v['CreatininaSerica'] ?> mg/dL</div>
                            <div class="col"><b>Clearance Creatinina (ClCr):</b> <?= $v['ClearanceCreatinina'] ?> mL/min</div>
                        </div>

                        <hr />

                        <!--<div class="row">
                            <div class="col"><b>Serviço:</b> <?= $v['DescricaoServico'] ?></div>
                        </div>

                        <br />-->

                        <div class="row">
                            <div class="col"><b>Alergias:</b> <?= ($v['Alergia']) ? '<br>'.nl2br($v['Alergia']) : NULL ?></div>
                        </div>

                        <br />

                        <div class="row">
                            <div class="col"><b>Reações Adversas:</b> <?= ($v['ReacaoAdversa']) ? '<br>'.nl2br($v['ReacaoAdversa']) : NULL ?></div>
                        </div>

                        <br />

                        <div class="row">
                            <div class="col"><b>Informações Complementares:</b> <?= ($v['InformacaoComplementar']) ? '<br>'.nl2br($v['InformacaoComplementar']) : NULL ?></div>
                        </div>

                        <br />

                        <div class="row">
                            <div class="col"><b>Médico(a) Prescritor(a):</b> <?= $v['Nome'] ?></div>
                        </div>

                        <hr />

                        <div class="text-center">
                            <b>
                                <h4><span class="badge bg-primary">PROTOCOLO: <?= $v['Protocolo'] ?></b></span></h4>
                            </b>
                        </div>                                
                        <div><strong>Observações do Protocolo: </strong> <?= $v['Observacoes'] ?></div>

                        <hr />

                        <?php if($v['Concluido'] != 1) { ?>
                            <div class="text-center">
                                <a class="btn btn-warning" href="<?= base_url('prescricao/manage_prescricao/editar/'.$v['idPreschuap_Prescricao']) ?>" role="button"><i class="fa-solid fa-arrow-right-arrow-left"></i> Trocar Protocolo</a>
                                <a class="btn btn-warning" href="<?= base_url('prescricao/manage_medicamento/'.$v['idPreschuap_Prescricao']) ?>" role="button"><i class="fa-solid fa-edit"></i> Ajustar Doses</a>
                            </div>
                            <hr />
                        <?php } ?>

                        <?php
                        if(!isset($medicamento[$v['idPreschuap_Prescricao']])) {
                        ?>
                        <div class="alert alert-warning" role="alert">
                            Nenhum medicamento cadastrado.
                        </div>
                        <?php
                        }
                        else {
                            foreach($medicamento[$v['idPreschuap_Prescricao']] as $m) {
                        ?>
                        <div class="row">
                            <div class="col"><b>Ordem de Infusão:</b> <?= $m['OrdemInfusao'] ?></div>
                        </div>

                        <div class="row">
                            <div class="col"><b>Medicamento: <?= $m['Medicamento'] ?></b></div>
                        </div>

                        <div class="row">
                            <div class="col"><b>Etapa da Terapia:</b> <?= $m['EtapaTerapia'] ?></div>
                            <div class="col"><b>Via de Administração:</b> <?= $m['ViaAdministracao'] ?></div>
                        </div>

                        <div class="row">
                            <div class="col"><b>Diluente:</b> <?= $m['Diluente'] ?></div>
                            <div class="col"><b>Volume:</b> <?= ($m['Volume'] == '0,00' || !$m['Volume'] ) ? NULL : $m['Volume'] . 'ml' ?></div>
                        </div>

                        <div class="row">
                            <div class="col"><b>TempoInfusão:</b> <?= $m['TempoInfusao'] ?></div>
                            <div class="col"><b>Posologia:</b> <?= $m['Posologia'] ?></div>
                        </div>

                        <div class="row">
                            <div class="col"><b>Dose do Protocolo:</b> <?= $m['Dose'] ?></div>
                            <div class="col"><b>Cálculo Final:</b> <?= $m['Calculo'] ?></div>
                        </div>

                        <?php if ($m['Ajuste2']) { ?>
                        <div class="row">
                            <div class="col"><b>Ajuste:</b> <?= $m['Ajuste2'] ?></div>
                            <div class="col"><b>Tipo de Ajuste:</b> <?= $m['TipoAjusteDescricao'] ?></div>
                        </div>

                        <div class="row">
                            <div class="col"><b>Motivo do Ajuste:</b> <?= $m['MotivoAjusteDose'] ?></div>
                        </div>
                        <?php } ?>

                        <hr />
                        <?php
                            }
                        }
                        ?>

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
