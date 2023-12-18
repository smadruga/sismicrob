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

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label><b class="text-danger">*</b> Indicação:</label>
                        <select data-placeholder="Selecione uma opção..." class="form-control Chosen"
                                id="idTabSismicrob_Indicacao"
                                onchange="showHideDiv(this.value,this.name,'idTabSismicrob_Indicacao','1|3')"
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
                    <div class="form-group col-md-6 idTabSismicrob_Indicacao1" id="#idTabSismicrob_Indicacao1">
                        <label><b class="text-danger">*</b> Tipo de Cirurgia:</label>
                        <input type="text" class="form-control" id="IndicacaoTipoCirurgia" maxlength="250"
                                name="IndicacaoTipoCirurgia" value="<?php echo $data['IndicacaoTipoCirurgia'] ?>">
                    </div>
                </div>

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



                    <div class="col-md-4">
                        <label for="DataInicioTratamento" class="form-label">Data de Início <b class="text-danger">*</b></label>
                        <div class="input-group mb-3">
                            <input type="text" placeholder="DD/MM/AAAA" id="DataInicioTratamento" <?= $opt['disabled'] ?> maxlength="10"
                                class="form-control Data <?php if($validation->getError('DataInicioTratamento')): ?>is-invalid<?php endif ?>"
                                autofocus name="DataInicioTratamento" value="<?php echo $data['DataInicioTratamento']; ?>"/>

                            <?php if ($validation->getError('DataInicioTratamento')): ?>
                                <div class="invalid-feedback">
                                    <?= $validation->getError('DataInicioTratamento') ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>


                    <div class="col-md-4">
                        <label for="DosePosologica" class="form-label">Duração <b class="text-danger">*</b></label>
                        <div class="input-group mb-3">
                            <input type="text" id="DosePosologica" <?= $opt['disabled'] ?>
                                class="form-control <?php if($validation->getError('DosePosologica')): ?>is-invalid<?php endif ?>"
                                name="DosePosologica" value="<?php echo $data['DosePosologica']; ?>" maxlength="4"/>
                            <span class="input-group-text" id="basic-addon2">dias(s)</span>
                            <?php if ($validation->getError('DosePosologica')): ?>
                                <div class="invalid-feedback">
                                    <?= $validation->getError('DosePosologica') ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>         

                    <div class="col-md-4">
                        <label for="DataFimTratamento" class="form-label">Fim do Tratamento <b class="text-danger">*</b></label>
                        <div class="input-group mb-3">
                            <input type="text" readonly id="DataFimTratamento" <?= $opt['disabled'] ?>
                                class="form-control <?php if($validation->getError('DataFimTratamento')): ?>is-invalid<?php endif ?>"
                                name="DataFimTratamento" value="<?php echo $data['DataFimTratamento']; ?>" maxlength="9"/>
                                <span class="input-group-text" id="basic-addon2"><span class="fas fa-calendar"></span></span>
                        </div>
                    </div>

                

                <div class="col-md-12">
                    <label for="DataFimTratamento" class="form-label">Dose de Ataque <b class="text-danger">*</b></label>
                    <div class="input-group mb-3">
                        <div class="form-check">
                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                        <label class="form-check-label" for="flexRadioDefault1">
                            Sim
                        </label>
                        </div>
                        <div class="form-check">
                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2" checked>
                        <label class="form-check-label" for="flexRadioDefault2">
                            Não
                        </label>
                        </div>
                    </div>
                </div>

                

                    <div class="col-md-4">
                        <label for="DosePosologica" class="form-label">Dose Posológica de Manutenção <b class="text-danger">*</b></label>
                        <div class="input-group mb-3">
                            <input type="text" id="DosePosologica" <?= $opt['disabled'] ?>
                                class="form-control <?php if($validation->getError('DosePosologica')): ?>is-invalid<?php endif ?>"
                                name="DosePosologica" value="<?php echo $data['DosePosologica']; ?>" maxlength="4"/>
                            
                            <?php if ($validation->getError('DosePosologica')): ?>
                                <div class="invalid-feedback">
                                    <?= $validation->getError('DosePosologica') ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>                     

                    <div class="col-md-2">
                        <label for="DataFimTratamento" class="form-label">Unidade de Medida <b class="text-danger">*</b></label>
                        <div class="input-group mb-3">
                            <div class="form-check">
                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                            <label class="form-check-label" for="flexRadioDefault1">
                                g
                            </label>
                            </div>
                            <div class="form-check">
                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2" checked>
                            <label class="form-check-label" for="flexRadioDefault2">
                                mg
                            </label>
                            </div>
                            <div class="form-check">
                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2" checked>
                            <label class="form-check-label" for="flexRadioDefault2">
                                UI
                            </label>
                            </div>
                        </div>
                        </div>
                    

                        <div class="col-md-4">
                            <label for="Intervalo" class="form-label">Intervalo <b class="text-danger">*</b></label>
                            <div class="input-group mb-3">

                                <select <?= $opt['disabled'] ?>
                                    class="form-select select2 <?php if($validation->getError('Intervalo')): ?>is-invalid<?php endif ?>"
                                    id="Intervalo" name="Intervalo" data-placeholder="Selecione uma opção"
                                    data-allow-clear="1">
                                    <option value="">Selecione uma opção</option>
                                    <?php
                                    foreach ($select['Intervalo']->getResultArray() as $val) {
                                        $selected = ($data['IntervaloUnidade'] == $val['Intervalo']) ? 'selected' : '';
                                        echo '<option value="'.$val['Intervalo'].'" '.$selected.'>'.$val['Intervalo'].'</option>';
                                    }
                                    ?>
                                </select>

                                <?php if ($validation->getError('Intervalo')): ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('Intervalo') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                

                <div class="col-md-2">
                    <label for="DoseDiaria" class="form-label">Dose diária <b class="text-danger">*</b></label>
                    <div class="input-group mb-3">
                        <input type="text" readonly id="DoseDiaria" <?= $opt['disabled'] ?>
                            class="form-control <?php if($validation->getError('DoseDiaria')): ?>is-invalid<?php endif ?>"
                            name="DoseDiaria" value="<?php echo $data['DoseDiaria']; ?>" maxlength="9"/>
                    </div>
                </div>


                <div class="col-md-4">
                            <label for="ViaAdministracao" class="form-label">ViaAdministracao <b class="text-danger">*</b></label>
                            <div class="input-group mb-3">

                                <select <?= $opt['disabled'] ?>
                                    class="form-select select2 <?php if($validation->getError('ViaAdministracao')): ?>is-invalid<?php endif ?>"
                                    id="ViaAdministracao" name="ViaAdministracao" data-placeholder="Selecione uma opção"
                                    data-allow-clear="1">
                                    <option value="">Selecione uma opção</option>
                                    <?php
                                    foreach ($select['ViaAdministracao']->getResultArray() as $val) {
                                        $selected = ($data['idTabSismicrob_ViaAdministracao'] == $val['ViaAdministracao']) ? 'selected' : '';
                                        echo '<option value="'.$val['ViaAdministracao'].'" '.$selected.'>'.$val['ViaAdministracao'].'</option>';
                                    }
                                    ?>
                                </select>

                                <?php if ($validation->getError('ViaAdministracao')): ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('ViaAdministracao') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>      

                <div class="col-md-4">
                            <label for="Especialidade" class="form-label">Especialidade <b class="text-danger">*</b></label>
                            <div class="input-group mb-3">

                                <select <?= $opt['disabled'] ?>
                                    class="form-select select2 <?php if($validation->getError('Especialidade')): ?>is-invalid<?php endif ?>"
                                    id="Especialidade" name="Especialidade" data-placeholder="Selecione uma opção"
                                    data-allow-clear="1">
                                    <option value="">Selecione uma opção</option>
                                    <?php
                                    foreach ($select['Especialidade']->getResultArray() as $val) {
                                        $selected = ($data['idTabSismicrob_Especialidade'] == $val['Especialidade']) ? 'selected' : '';
                                        echo '<option value="'.$val['Especialidade'].'" '.$selected.'>'.$val['Especialidade'].'</option>';
                                    }
                                    ?>
                                </select>

                                <?php if ($validation->getError('Especialidade')): ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('Especialidade') ?>
                                    </div>
                                <?php endif; ?>
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
