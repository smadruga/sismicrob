<?= $this->extend('layouts/main_content') ?>
<?= $this->section('subcontent') ?>
<?= $this->include('layouts/sidenavbar_paciente') ?>

<main class="col">

    <form method="post" action="<?= base_url('prescricao/manage_prescricao/') ?>">
        <?= csrf_field() ?>
        <?php $validation = \Config\Services::validation(); ?>

        <div class="card">
            <div class="card-header <?= $opt['bg'] ?> text-white">
                <?= $opt['title'] ?></b>
            </div>
            <div class="card-body has-validation row g-3">

                <div class="col-md-12">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Indicação <b class="text-danger">*</b></label>
                            <select data-placeholder="Selecione uma opção..." class="form-control select2"
                                    id="idTabSismicrob_Indicacao" onchange="showHideDiv(this.value,this.name,'idTabSismicrob_Indicacao','1|3')"
                                    name="idTabSismicrob_Indicacao">
                                <option value="">Selecione uma opção...</option>
                                <?php

                                foreach ($select['Indicacao']->getResultArray() as $row) {
                                    if ($data['idTabSismicrob_Indicacao'] == $row['idTabSismicrob_Indicacao']) {
                                        echo '<option value="' . $row['idTabSismicrob_Indicacao'] . '" selected="selected">' . $row['Indicacao'] . '</option>';
                                    }
                                    else {
                                        echo '<option value="' . $row['idTabSismicrob_Indicacao'] . '">' . $row['Indicacao'] . '</option>';
                                    }
                                }

                                ?>
                            </select>
                        </div>
                        <div class="form-group col-md-6 idTabSismicrob_Indicacao1" id="#idTabSismicrob_Indicacao1" style="display: none;">
                            <label>Tipo de Cirurgia <b class="text-danger">*</b></label>
                            <input type="text" class="form-control" id="IndicacaoTipoCirurgia" maxlength="250"
                                    name="IndicacaoTipoCirurgia" value="<?php echo $data['IndicacaoTipoCirurgia'] ?>">
                        </div>
                    </div>
                </div>

                <div class="form-row idTabSismicrob_Indicacao3" id="#idTabSismicrob_Indicacao3" style="display: none;">
                    
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Diagnóstico Infeccioso <b class="text-danger">*</b></label>
                            <select data-placeholder="Selecione uma opção..." class="form-control select2"
                                    id="idTabSismicrob_DiagnosticoInfeccioso" onchange="showHideDiv(this.value,this.name,'idTabSismicrob_DiagnosticoInfeccioso','7')" name="idTabSismicrob_DiagnosticoInfeccioso">
                                <option value="">Selecione uma opção...</option>
                                <?php

                                foreach ($select['DiagnosticoInfeccioso']->getResultArray() as $row) {
                                    if ($data['idTabSismicrob_DiagnosticoInfeccioso'] == $row['idTabSismicrob_DiagnosticoInfeccioso']) {
                                        echo '<option value="' . $row['idTabSismicrob_DiagnosticoInfeccioso'] . '" selected="selected">' . $row['DiagnosticoInfeccioso'] . '</option>';
                                    }
                                    else {
                                        echo '<option value="' . $row['idTabSismicrob_DiagnosticoInfeccioso'] . '">' . $row['DiagnosticoInfeccioso'] . '</option>';
                                    }
                                }

                                ?>
                            </select>
                        </div>
                        <div class="form-group col-md-6 idTabSismicrob_DiagnosticoInfeccioso7" id="#idTabSismicrob_DiagnosticoInfeccioso7" style="display: none;">
                            <label>Especificar <b class="text-danger">*</b></label>
                            <input type="text" class="form-control" id="DiagnosticoInfecciosoOutro" maxlength="250"
                                    name="DiagnosticoInfecciosoOutro" value="<?php echo $data['DiagnosticoInfecciosoOutro'] ?>">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label>Justificativa:</label>
                            <textarea class="form-control" id="Justificativa" maxlength="65000"
                                name="Justificativa"><?php echo $data['Justificativa'] ?></textarea>
                            <small id="JustificativaHelp" class="form-text text-muted">
                                <b class="text-warning">*</b>
                                O campo "Justificativa" será obrigatório se o campo "Indicação" for "Terapêutica".
                            </small>
                        </div>
                    </div>
                    
                </div>                

                <div class="col idTabSismicrob_IndicacaoDiv" id="#idTabSismicrob_IndicacaoDiv" style="display: none;">
                    
                    <div class="col-md-12">
                        <label for="Codigo" class="form-label">Medicamento <b class="text-danger">*</b></label>
                        <div class="input-group mb-3">

                            <select <?= $opt['disabled'] ?>
                                class="form-select select2 <?php if($validation->getError('Codigo')): ?>is-invalid<?php endif ?>"
                                id="Codigo" name="Codigo" data-placeholder="Selecione uma opção"
                                data-allow-clear="1">
                                <option value="">Selecione uma opção</option>
                                <?php
                                foreach ($select['Medicamento']->getResultArray() as $val) {
                                    $selected = ($data['Codigo'] == $val['Codigo']) ? 'selected' : '';
                                    echo '<option value="'.$val['Codigo'].'" '.$selected.'>'.$val['Medicamento'].'</option>';
                                }
                                ?>
                            </select>

                            <?php if ($validation->getError('Codigo')): ?>
                                <div class="invalid-feedback">
                                    <?= $validation->getError('Codigo') ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    

                </div>

                <input type="hidden" name="Idade" id="Idade" value="<?= $_SESSION['Paciente']['idade'] ?>" />
                <input type="hidden" name="Sexo" id="Sexo" value="<?= $_SESSION['Paciente']['sexo'] ?>" />
                <input type="hidden" name="action" value="<?= $opt['action'] ?>" />
                <?php if($opt['action'] == 'editar' || $opt['action'] == 'excluir' || $opt['action'] == 'concluir') { ?>
                <input type="hidden" name="idPreschuap_Prescricao" value="<?= $data['idPreschuap_Prescricao'] ?>" />
                <?php } ?>

                <hr />
                <div class="col-md-12">
                    <?= $opt['button'] ?>
                    <a class="btn btn-warning" href="javascript:history.go(-1)"><i class="fa-solid fa-arrow-left"></i> Voltar</a>
                </div>

            </div>
        </div>

    </form>

</main>

<?= $this->endSection() ?>
