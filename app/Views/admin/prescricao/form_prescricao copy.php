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

                <div class="col-md-4">
                    <label for="DataPrescricao" class="form-label">Data da Prescrição <b class="text-danger">*</b></label>
                    <div class="input-group mb-3">
                        <input type="text" placeholder="DD/MM/AAAA" id="DataPrescricao" <?= $opt['disabled'] ?> maxlength="10"
                            class="form-control Data <?php if($validation->getError('DataPrescricao')): ?>is-invalid<?php endif ?>"
                            autofocus name="DataPrescricao" value="<?php echo $data['DataPrescricao']; ?>"/>

                        <?php if ($validation->getError('DataPrescricao')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('DataPrescricao') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-md-4">
                    <label for="Dia" class="form-label">Dia <b class="text-danger">*</b></label>
                    <div class="input-group mb-3">
                        <span class="input-group-text">D</span>
                        <select <?= $opt['disabled'] ?>
                            class="form-select <?php if($validation->getError('Dia')): ?>is-invalid<?php endif ?>"
                            id="Dia" name="Dia" data-placeholder="Selecione uma opção" data-allow-clear="1">
                            <option value=""></option>
                            <?php
                            for ($val = 1; $val <= 50; $val++) {
                                $selected = ($data['Dia'] == $val) ? 'selected' : '';
                                echo '<option value="'.$val.'" '.$selected.'>'.$val.'</option>';
                            }
                            ?>
                        </select>
                        <?php if ($validation->getError('Dia')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('Dia') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="Ciclo" class="form-label">Ciclo <b class="text-danger">*</b></label>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon2">CICLO</span>
                        <select <?= $opt['disabled'] ?>
                            class="form-select <?php if($validation->getError('Ciclo')): ?>is-invalid<?php endif ?>"
                            id="Ciclo" name="Ciclo" data-placeholder="Selecione uma opção" data-allow-clear="1">
                            <option value=""></option>
                            <?php
                            for ($val = 1; $val <= 50; $val++) {
                                $selected = ($data['Ciclo'] == $val) ? 'selected' : '';
                                echo '<option value="'.$val.'" '.$selected.'>'.$val.'</option>';
                            }
                            ?>
                        </select>
                        <?php if ($validation->getError('Ciclo')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('Ciclo') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-md-4">
                    <label for="CiclosTotais" class="form-label">Total de Ciclos <b class="text-danger">*</b></label>
                    <div class="input-group mb-3">
                        <input type="text" id="CiclosTotais" <?= $opt['disabled'] ?>
                            class="form-control <?php if($validation->getError('CiclosTotais')): ?>is-invalid<?php endif ?>"
                            name="CiclosTotais" value="<?php echo $data['CiclosTotais']; ?>" maxlength="4"/>
                        <span class="input-group-text" id="basic-addon2">ciclos(s)</span>
                        <?php if ($validation->getError('CiclosTotais')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('CiclosTotais') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="EntreCiclos" class="form-label">Entre Ciclos <b class="text-danger">*</b></label>
                    <div class="input-group mb-3">
                        <input type="text" id="EntreCiclos" <?= $opt['disabled'] ?>
                            class="form-control <?php if($validation->getError('EntreCiclos')): ?>is-invalid<?php endif ?>"
                            maxlength="10" name="EntreCiclos" value="<?php echo $data['EntreCiclos']; ?>" maxlength="4"/>
                        <span class="input-group-text" id="basic-addon2">dia(s)</span>
                        <?php if ($validation->getError('EntreCiclos')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('EntreCiclos') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                

                <div class="col-md-12">
                    <label for="idTabPreschuap_Categoria" class="form-label">CID Categoria <b class="text-danger">*</b></label>
                    <div class="input-group mb-3">

                        <select <?= $opt['disabled'] ?>
                            class="form-select select2 <?php if($validation->getError('idTabPreschuap_Categoria')): ?>is-invalid<?php endif ?>"
                            id="idTabPreschuap_Categoria" name="idTabPreschuap_Categoria" data-placeholder="Selecione uma opção"
                            data-allow-clear="1">
                            <option value="">Selecione uma opção</option>
                            <?php
                            foreach ($select['Categoria']->getResultArray() as $val) {
                                $selected = ($data['idTabPreschuap_Categoria'] == $val['idTabPreschuap_Categoria']) ? 'selected' : '';
                                echo '<option value="'.$val['idTabPreschuap_Categoria'].'" '.$selected.'>'.$val['idTabPreschuap_Categoria'].' - '.$val['Categoria'].'</option>';
                            }
                            ?>
                        </select>

                        <?php if ($validation->getError('idTabPreschuap_Categoria')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('idTabPreschuap_Categoria') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-md-12">
                    <label for="idTabPreschuap_Subcategoria" class="form-label">CID Subcategoria</b></label>
                    <div class="input-group mb-3">

                        <select <?= $opt['disabled'] ?>
                            class="form-select select2 <?php if($validation->getError('idTabPreschuap_Subcategoria')): ?>is-invalid<?php endif ?>"
                            id="idTabPreschuap_Subcategoria" name="idTabPreschuap_Subcategoria" data-placeholder="Selecione uma opção"
                            data-allow-clear="1">
                            <option value="">Selecione uma opção</option>
                            <?php
                            foreach ($select['Subcategoria']->getResultArray() as $val) {
                                $selected = ($data['idTabPreschuap_Subcategoria'] == $val['idTabPreschuap_Subcategoria']) ? 'selected' : '';
                                echo '<option value="'.$val['idTabPreschuap_Subcategoria'].'" '.$selected.'>'.$val['idTabPreschuap_Subcategoria'].' - '.$val['Subcategoria'].'</option>';
                            }
                            ?>
                        </select>

                        <?php if ($validation->getError('idTabPreschuap_Subcategoria')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('idTabPreschuap_Subcategoria') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-md-8">
                    <label for="idTabPreschuap_Protocolo" class="form-label">Protocolo <b class="text-danger">*</b></label>
                    <div class="input-group mb-3">

                        <select <?= $opt['disabled'] ?>
                            class="form-select select2 <?php if($validation->getError('idTabPreschuap_Protocolo')): ?>is-invalid<?php endif ?>"
                            id="idTabPreschuap_Protocolo" name="idTabPreschuap_Protocolo" data-placeholder="Selecione uma opção"
                            data-allow-clear="1">
                            <option value="">Selecione uma opção</option>
                            <?php
                            foreach ($select['Protocolo']->getResultArray() as $val) {
                                $selected = ($data['idTabPreschuap_Protocolo'] == $val['idTabPreschuap_Protocolo']) ? 'selected' : '';
                                echo '<option value="'.$val['idTabPreschuap_Protocolo'].'" '.$selected.'>'.$val['Protocolo'].'</option>';
                            }
                            ?>
                        </select>

                        <?php if ($validation->getError('idTabPreschuap_Protocolo')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('idTabPreschuap_Protocolo') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="idTabPreschuap_TipoTerapia" class="form-label">Tipo de Terapia</b></label>
                    <div class="input-group mb-3">

                        <select <?= $opt['disabled'] ?>
                            class="form-select <?php if($validation->getError('idTabPreschuap_TipoTerapia')): ?>is-invalid<?php endif ?>"
                            id="idTabPreschuap_TipoTerapia" name="idTabPreschuap_TipoTerapia" data-placeholder="Selecione uma opção"
                            data-allow-clear="1">
                            <option value="">Selecione uma opção</option>
                            <?php
                            foreach ($select['TipoTerapia']->getResultArray() as $val) {
                                $selected = ($data['idTabPreschuap_TipoTerapia'] == $val['idTabPreschuap_TipoTerapia']) ? 'selected' : '';
                                echo '<option value="'.$val['idTabPreschuap_TipoTerapia'].'" '.$selected.'>'.$val['TipoTerapia'].'</option>';
                            }
                            ?>
                        </select>

                        <?php if ($validation->getError('idTabPreschuap_TipoTerapia')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('idTabPreschuap_TipoTerapia') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-md-4">
                    <label for="Peso" class="form-label">Peso <b class="text-danger">*</b></label>
                    <div class="input-group mb-3">
                        <input type="text" id="Peso" <?= $opt['disabled'] ?>
                            class="form-control <?php if($validation->getError('Peso')): ?>is-invalid<?php endif ?>"
                            name="Peso" onkeyup="indiceMassaCorporal(),superficieCorporal(),clearanceCreatinina()"
                            value="<?php echo $data['Peso']; ?>" maxlength="9"/>
                        <span class="input-group-text" id="basic-addon2">kg</span>
                        <?php if ($validation->getError('Peso')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('Peso') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="Altura" class="form-label">Altura <b class="text-danger">*</b></label>
                    <div class="input-group mb-3">
                        <input type="text" id="Altura" <?= $opt['disabled'] ?>
                            class="form-control <?php if($validation->getError('Altura')): ?>is-invalid<?php endif ?>"
                            maxlength="10" name="Altura" onkeyup="indiceMassaCorporal(),superficieCorporal()"
                            value="<?php echo $data['Altura']; ?>" maxlength="5"/>
                        <span class="input-group-text" id="basic-addon2">cm</span>
                        <?php if ($validation->getError('Altura')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('Altura') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="CreatininaSerica" class="form-label">Creatinina Sérica (ClSr) <b class="text-danger">*</b></label>
                    <div class="input-group mb-3">
                        <input type="text" id="CreatininaSerica" <?= $opt['disabled'] ?>
                            class="form-control <?php if($validation->getError('CreatininaSerica')): ?>is-invalid<?php endif ?>"
                            maxlength="10" name="CreatininaSerica" onkeyup="clearanceCreatinina()"
                            value="<?php echo $data['CreatininaSerica']; ?>" maxlength="9"/>
                        <span class="input-group-text" id="basic-addon2">mg/dL</span>
                        <?php if ($validation->getError('CreatininaSerica')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('CreatininaSerica') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-md-4">
                    <label for="ClearanceCreatinina" class="form-label">Clearance Creatinina (ClCr) <b class="text-danger">*</b></label>
                    <div class="input-group mb-3">
                        <input type="text" readonly id="ClearanceCreatinina" <?= $opt['disabled'] ?>
                            class="form-control <?php if($validation->getError('ClearanceCreatinina')): ?>is-invalid<?php endif ?>"
                            name="ClearanceCreatinina" value="<?php echo $data['ClearanceCreatinina']; ?>" maxlength="9"/>
                        <span class="input-group-text" id="basic-addon2">mL/min</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="IndiceMassaCorporal" class="form-label">Índice de Massa Corporal (IMC) <b class="text-danger">*</b></label>
                    <div class="input-group mb-3">
                        <input type="text" readonly id="IndiceMassaCorporal" <?= $opt['disabled'] ?>
                        class="form-control <?php if($validation->getError('IndiceMassaCorporal')): ?>is-invalid<?php endif ?>"
                        maxlength="10" name="IndiceMassaCorporal" value="<?php echo $data['IndiceMassaCorporal']; ?>" maxlength="9"/>
                        <span class="input-group-text" id="basic-addon2">kg/m²</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="SuperficieCorporal" class="form-label">Superfície Corporal (SC) <b class="text-danger">*</b></label>
                    <div class="input-group mb-3">
                        <input type="text" readonly id="SuperficieCorporal" <?= $opt['disabled'] ?>
                        class="form-control <?php if($validation->getError('SuperficieCorporal')): ?>is-invalid<?php endif ?>"
                        maxlength="10" name="SuperficieCorporal" value="<?php echo $data['SuperficieCorporal']; ?>" maxlength="9"/>
                        <span class="input-group-text" id="basic-addon2">m²</span>
                    </div>
                </div>

                <div class="col-md-12">
                    <label for="Alergia" class="form-label">Alergias</b></label>
                    <div class="input-group mb-3">

                        <textarea <?= $opt['disabled'] ?> class="form-control <?php if($validation->getError('Alergia')): ?>is-invalid<?php endif ?>" id="Alergia" name="Alergia" rows="3"><?php echo $data['Alergia']; ?></textarea>

                        <?php if ($validation->getError('Alergia')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('Alergia') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-md-12">
                    <label for="ReacaoAdversa" class="form-label">Reação Adversa Anterior</b></label>
                    <div class="input-group mb-3">

                        <textarea <?= $opt['disabled'] ?> class="form-control <?php if($validation->getError('ReacaoAdversa')): ?>is-invalid<?php endif ?>" id="ReacaoAdversa" name="ReacaoAdversa" rows="3"><?php echo $data['ReacaoAdversa']; ?></textarea>

                        <?php if ($validation->getError('ReacaoAdversa')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('ReacaoAdversa') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-md-12">
                    <label for="InformacaoComplementar" class="form-label">Informação Complementar</b></label>
                    <div class="input-group mb-3">

                        <textarea <?= $opt['disabled'] ?> class="form-control <?php if($validation->getError('InformacaoComplementar')): ?>is-invalid<?php endif ?>" id="InformacaoComplementar" name="InformacaoComplementar" rows="3"><?php echo $data['InformacaoComplementar']; ?></textarea>

                        <?php if ($validation->getError('InformacaoComplementar')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('InformacaoComplementar') ?>
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
