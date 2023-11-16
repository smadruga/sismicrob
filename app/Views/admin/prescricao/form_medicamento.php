<?= $this->extend('layouts/main_content') ?>
<?= $this->section('subcontent') ?>
<?= $this->include('layouts/sidenavbar_paciente') ?>

<main class="col">

    <form method="post" action="<?= base_url('prescricao/manage_medicamento/') ?>">
        <?= csrf_field() ?>
        <?php $validation = \Config\Services::validation(); ?>

        <div class="card">
            <div class="card-header <?= $opt['bg'] ?> text-white">
                <b><?= $opt['title'] ?></b>
            </div>
            <div class="card-body has-validation row g-3">

                <div class="row">
                    <div class="col"><b>Prescrição:</b> #<?= $data['prescricao']['idPreschuap_Prescricao'] ?></div>
                    <div class="col"><b>Data da Prescrição:</b> <?= $data['prescricao']['DataPrescricao'] ?></div>
                </div>

                <div class="row">
                    <div class="col"><b>Dia:</b> <?= $data['prescricao']['Dia'] ?></div>
                    <div class="col"><b>Ciclo:</b> <?= $data['prescricao']['Ciclo'] ?></div>
                </div>

                <div class="row">
                    <div class="col"><b>Total de Ciclos:</b> <?= $data['prescricao']['CiclosTotais'] ?> ciclo(s)</div>
                    <div class="col"><b>Entre Ciclos:</b> <?= $data['prescricao']['EntreCiclos'] ?> dia(s)</div>
                </div>

                <div class="row">
                    <div class="col"><b>CID Categoria:</b> <?= $data['prescricao']['Categoria'] ?></div>
                </div>

                <div class="row">
                    <div class="col"><b>CID Subcategoria:</b> <?= $data['prescricao']['Subcategoria'] ?></div>
                </div>

                <div class="row">
                    <div class="col"><b>Aplicabilidade:</b> <?= $data['prescricao']['Aplicabilidade'] ?></div>
                    <div class="col"><b>Tipo de Terapia:</b> <?= $data['prescricao']['TipoTerapia'] ?></div>
                </div>

                <hr />

                <div class="row">
                    <div class="col"><b>Peso:</b> <?= $data['prescricao']['Peso'] ?> kg</div>
                    <div class="col"><b>Altura:</b> <?= $data['prescricao']['Altura'] ?> cm</div>
                </div>

                <div class="row">
                    <div class="col"><b>Índice de Massa Corporal (IMC):</b> <?= $data['prescricao']['IndiceMassaCorporal'] ?> kg/m²</div>
                    <div class="col"><b>Superfície Corporal (SC):</b> <?= $data['prescricao']['SuperficieCorporal'] ?> m²</div>
                </div>

                <div class="row">
                    <div class="col"><b>Creatinina Sérica (ClSr):</b> <?= $data['prescricao']['CreatininaSerica'] ?> mg/dL</div>
                    <div class="col"><b>Clearance Creatinina (ClCr):</b> <?= $data['prescricao']['ClearanceCreatinina'] ?> mL/min</div>
                </div>

                <hr />

                <div class="row">
                    <div class="col"><b>Serviço:</b> <?= $data['prescricao']['DescricaoServico'] ?></div>
                </div>

                <br />

                <div class="row">
                    <div class="col"><b>Alergias:</b> <?= ($data['prescricao']['Alergia']) ? '<br>'.nl2br($data['prescricao']['Alergia']) : NULL ?></div>
                </div>

                <br />

                <div class="row">
                    <div class="col"><b>Reações Adversas:</b> <?= ($data['prescricao']['ReacaoAdversa']) ? '<br>'.nl2br($data['prescricao']['ReacaoAdversa']) : NULL ?></div>
                </div>

                <br />

                <div class="row">
                    <div class="col"><b>Informações Complementares:</b> <?= ($data['prescricao']['InformacaoComplementar']) ? '<br>'.nl2br($data['prescricao']['InformacaoComplementar']) : NULL ?></div>
                </div>

                <br />

                <div class="row">
                    <div class="col"><b>Médico(a) Prescritor(a):</b> <?= $data['prescricao']['Nome'] ?></div>
                </div>


                <hr />

                    <div class="text-center">
                        <b>
                            <h4><span class="badge bg-primary">PROTOCOLO: <?= $data['prescricao']['Protocolo'] ?></b></span></h4>
                        </b>
                </div>                                
                <div><strong>Observações do Protocolo: </strong> <?= $data['prescricao']['Observacoes'] ?></div>

                <hr />

                <?php
                if(!isset($data['medicamento'])) {
                ?>
                <div class="alert alert-warning" role="alert">
                    Nenhum medicamento cadastrado.
                </div>
                <?php
                }
                else {
                    $i=0;
                    foreach($data['medicamento'] as $m) {

                        $dose = explode(" ", $m['Dose']);

                        if($m['idTabPreschuap_Formula'] == 2 || $m['idTabPreschuap_Formula'] == 3)
                            $u = explode("/", $m['Unidade']);
                        else
                            $u[0] = $m['Unidade'];

                        $u[0] = ($u[0] == 'auc') ? 'mg' : $u[0];

                ?>
                <div class="row">
                    <div class="col"><b>Ordem de Infusão:</b> <span class="badge bg-primary"><?= $m['OrdemInfusao'] ?></span></div>
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
                    <div class="col"><b>Volume:</b> <?= ($m['Volume'] != '0,00') ? $m['Volume'] . 'ml' : NULL ?></div>
                </div>

                <div class="row">
                    <div class="col"><b>TempoInfusão:</b> <?= $m['TempoInfusao'] ?></div>
                    <div class="col"><b>Posologia:</b> <?= $m['Posologia'] ?></div>
                </div>

                <div class="col-md-6">
                    <label for="Dose<?= $i ?>" class="form-label"><b>Dose do Protocolo</b> <b class="text-danger">*</b></label>
                    <div class="input-group mb-3">
                        <input type="text" id="Dose<?= $i ?>" disabled
                            class="form-control <?php if($validation->getError('Dose'.$i)): ?>is-invalid<?php endif ?>"
                            name="Dose<?= $i ?>" value="<?php echo $dose[0] ?>"/>
                        <span class="input-group-text" id="basic-addon2"><?php echo $dose[1] ?></span>
                        <?php if ($validation->getError('Dose'.$i)): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('Dose'.$i) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="Calculo<?= $i ?>" class="form-label"><b>Cálculo Final</b> <b class="text-danger">*</b></label>
                    <div class="input-group mb-3">
                        <input type="text" id="Calculo<?= $i ?>" readonly
                            class="form-control <?php if($validation->getError('Calculo'.$i)): ?>is-invalid<?php endif ?>"
                            maxlength="10" name="Calculo<?= $i ?>" value="<?php echo $m['Calculo'] ?>"/>
                        <span class="input-group-text" id="basic-addon2"><?php echo $u[0] ?></span>
                        <?php if ($validation->getError('Calculo'.$i)): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('Calculo'.$i) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>


                <div class="col-md-4">
                    <label for="Ajuste<?= $i ?>" class="form-label"><b>Ajuste</b> <b class="text-danger">*</b></label>
                    <div class="input-group mb-3">
                        <input type="text" id="Ajuste<?= $i ?>" <?= $opt['disabled'] ?>
                            class="form-control <?php if($validation->getError('Ajuste'.$i)): ?>is-invalid<?php endif ?>"
                            maxlength="9" name="Ajuste<?= $i ?>" placeholder="Apenas números" onkeyup="ajuste(<?= $i ?>)"
                            
                            value="<?php echo $data['input'][$i]['Ajuste']; ?>"/>

                        <?php if ($validation->getError('Ajuste'.$i)): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('Ajuste'.$i) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="TipoAjuste<?= $i ?>" class="form-label"><b>Tipo de Ajuste</b> <b class="text-danger">*</b></label>
                    <div class="input-group mb-3">

                        <select <?= $opt['disabled'] ?>
                            class="form-select <?php if($validation->getError('TipoAjuste'.$i)): ?>is-invalid<?php endif ?>"
                            id="TipoAjuste<?= $i ?>" name="TipoAjuste<?= $i ?>" onchange="ajuste(<?= $i ?>)"
                            data-placeholder="Selecione uma opção" data-allow-clear="1">
                            <?php
                            foreach ($select['TipoAjuste'] as $key => $val) {
                                $selected = ($data['input'][$i]['TipoAjuste'] == $key) ? 'selected' : '';
                                echo '<option value="'.$key.'" '.$selected.'>'.$val.'</option>';
                            }
                            ?>
                        </select>

                        <?php if ($validation->getError('TipoAjuste'.$i)): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('TipoAjuste'.$i) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="idTabPreschuap_MotivoAjusteDose<?= $i ?>" class="form-label"><b>Motivo do Ajuste</b></label>
                    <div class="input-group mb-3">

                        <select <?= $opt['disabled'] ?>
                            class="form-select <?php if($validation->getError('idTabPreschuap_MotivoAjusteDose'.$i)): ?>is-invalid<?php endif ?>"
                            id="idTabPreschuap_MotivoAjusteDose<?= $i ?>" name="idTabPreschuap_MotivoAjusteDose<?= $i ?>" onchange="ajuste(<?= $i ?>)"
                            data-placeholder="Selecione uma opção" data-allow-clear="1">
                            <option value="">Selecione uma opção</option>
                            <?php
                            foreach ($select['MotivoAjusteDose']->getResultArray() as $val) {
                                $selected = ($data['input'][$i]['idTabPreschuap_MotivoAjusteDose'] == $val['idTabPreschuap_MotivoAjusteDose']) ? 'selected' : '';
                                echo '<option value="'.$val['idTabPreschuap_MotivoAjusteDose'].'" '.$selected.'>'.$val['MotivoAjusteDose'].'</option>';
                            }
                            ?>
                        </select>

                        <?php if ($validation->getError('idTabPreschuap_MotivoAjusteDose'.$i)): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('idTabPreschuap_MotivoAjusteDose'.$i) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <input type="hidden" name="idPreschuap_Prescricao_Medicamento<?= $i ?>"
                    value="<?= $data['input'][$i]['idPreschuap_Prescricao_Medicamento'] ?>" />
                <input type="hidden" name="Formula<?= $i ?>" id="Formula<?= $i ?>"
                    value="<?= $m['idTabPreschuap_Formula'] ?>" />
                <input type="hidden" name="CalculoOriginal<?= $i ?>" id="CalculoOriginal<?= $i ?>"
                    value="<?= $m['Calculo'] ?>" />
                <input type="hidden" name="CalculoLimiteMinimo<?= $i ?>" id="CalculoLimiteMinimo<?= $i ?>"
                    value="<?= $m['CalculoLimiteMinimo'] ?>" />
                <input type="hidden" name="CalculoLimiteMaximo<?= $i ?>" id="CalculoLimiteMaximo<?= $i ?>"
                    value="<?= $m['CalculoLimiteMaximo'] ?>" />
                <input type="hidden" name="CalculoFinalOriginal<?= $i ?>" id="CalculoFinalOriginal<?= $i ?>"
                    value="<?php echo $m['Calculo'] ?>" />
                    

                <hr />
                <?php
                    $i++;
                    }
                }
                ?>

                <div class="col-md-12">
                    <?= $opt['button'] ?>
                    <!--<a class="btn btn-warning" href="javascript:history.go(-1)"><i class="fa-solid fa-arrow-left"></i> Cancelar</a>-->
                    <a class="btn btn-warning" href="<?= base_url('prescricao/list_prescricao/') ?>"><i class="fa-solid fa-ban"></i> Cancelar</a>
                </div>

                <input type="hidden" name="idPreschuap_Prescricao" value="<?= $data['prescricao']['idPreschuap_Prescricao'] ?>" />
                <input type="hidden" name="Peso" id="Peso" value="<?= $data['prescricao']['Peso'] ?>" />
                <input type="hidden" name="SuperficieCorporal" id="SuperficieCorporal" value="<?= $data['prescricao']['SuperficieCorporal'] ?>" />

            </div>
        </div>

    </form>

</main>

<?= $this->endSection() ?>
