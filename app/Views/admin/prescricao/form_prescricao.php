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
                    <label for="DataInicioTratamento" class="form-label">Data da Prescrição <b class="text-danger">*</b></label>
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
