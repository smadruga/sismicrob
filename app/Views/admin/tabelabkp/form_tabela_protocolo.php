<?= $this->extend('layouts/main_content') ?>
<?= $this->section('subcontent') ?>

<main>

    <form method="post" action="<?= base_url('tabela/list_tabela/'.$tabela) ?>">
        <?= csrf_field() ?>
        <?php $validation = \Config\Services::validation(); ?>

        <div class="card">
            <div class="card-header <?= $opt['bg'] ?> text-white">
                <b><?= $opt['title'] ?></b>
            </div>
            <div class="card-body has-validation">

                <?php if ($tabela == 'ViaAdministracao') { ?>
                    <div class="row">
                        <div class="col">
                            <label for="Item" class="form-label"><b>Item</b> <b class="text-danger">*</b></label>
                            <div class="input-group mb-3">
                                <input type="text" id="Item" <?= $opt['disabled'] ?> class="form-control <?php if($validation->getError('Item')): ?>is-invalid<?php endif ?>" autofocus name="Item" value="<?php echo $data['Item']; ?>"/>

                                <?php if ($validation->getError('Item')): ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('Item') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="form-text">
                                Descrição sobre o item.
                            </div>
                        </div>
                        <div class="col">
                            <label for="Codigo" class="form-label"><b>Abreviação</b> <b class="text-danger">*</b></label>
                            <div class="input-group mb-3">
                                <input type="text" id="Codigo" <?= $opt['disabled'] ?> class="form-control <?php if($validation->getError('Codigo')): ?>is-invalid<?php endif ?>" autofocus maxlength="10" name="Codigo" value="<?php echo $data['Codigo']; ?>"/>

                                <?php if ($validation->getError('Codigo')): ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('Codigo') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="form-text">
                                Informe a abreviação do item, com no máximo 10 caracteres.
                            </div>
                        </div>
                    </div>
                    <br />
                    <?= $opt['button'] ?>
                <?php } elseif ($tabela == 'Protocolo') { ?>
                    <div class="row">
                        <div class="col">
                            <label for="Item" class="form-label"><b>Protocolo</b> <b class="text-danger">*</b></label>
                            <div class="input-group mb-3">
                                <input <?= $opt['disabled'] ?> type="text" id="Item" <?= $opt['disabled'] ?> class="form-control <?php if($validation->getError('Item')): ?>is-invalid<?php endif ?>" autofocus name="Item" value="<?php echo $data['Item']; ?>"/>

                                <?php if ($validation->getError('Item')): ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('Item') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col">
                            <label for="Aplicabilidade" class="form-label"><b>Aplicabilidade</b> <b class="text-danger">*</b></label>
                            <div class="input-group mb-3">

                                <select <?= $opt['disabled'] ?> class="form-select <?php if($validation->getError('Aplicabilidade')): ?>is-invalid<?php endif ?>" id="Aplicabilidade"
                                    name="Aplicabilidade" data-placeholder="Selecione uma opção" data-allow-clear="1">
                                    <option></option>
                                    <?php
                                    foreach ($select['Aplicabilidade'] as $val) {
                                        $selected = ($data['Aplicabilidade'] == $val) ? 'selected' : '';
                                        echo '<option value="'.$val.'" '.$selected.'>'.$val.'</option>';
                                    }
                                    ?>
                                </select>

                                <?php if ($validation->getError('Aplicabilidade')): ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('Aplicabilidade') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-4">
                            <label for="idTabPreschuap_TipoTerapia" class="form-label"><b>Tipo de Terapia</b> <b class="text-danger">*</b></label>
                            <div class="input-group mb-3">

                                <select <?= $opt['disabled'] ?> class="form-select <?php if($validation->getError('idTabPreschuap_TipoTerapia')): ?>is-invalid<?php endif ?>" id="idTabPreschuap_TipoTerapia"
                                    name="idTabPreschuap_TipoTerapia" data-placeholder="Selecione uma opção" data-allow-clear="1">
                                    <option></option>
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
                        <div class="col">
                            <label for="idTabPreschuap_Categoria" class="form-label"><b>Categoria</b> <b class="text-danger">*</b></label>
                            <div class="input-group mb-3">

                                <select <?= $opt['disabled'] ?> class="form-select <?php if($validation->getError('idTabPreschuap_Categoria')): ?>is-invalid<?php endif ?>" id="idTabPreschuap_Categoria"
                                    name="idTabPreschuap_Categoria" data-placeholder="Selecione uma opção" data-allow-clear="1">
                                    <option></option>
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
                    </div>

                    <div class="row">
                        <div class="col">
                            <label for="Observacoes" class="form-label"><b>Observações</b> <b class="text-danger">*</b></label>
                            <div class="input-group mb-3">

                                <textarea <?= $opt['disabled'] ?> class="form-control <?php if($validation->getError('Observacoes')): ?>is-invalid<?php endif ?>" id="Observacoes" name="Observacoes" rows="4"><?php echo $data['Observacoes']; ?></textarea>

                                <?php if ($validation->getError('Observacoes')): ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('Observacoes') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <br />
                    <?= $opt['button'] ?>
                <?php } else { ?>
                    <label for="Item" class="form-label"><b>Item</b> <b class="text-danger">*</b></label>
                    <div class="input-group mb-3">
                        <input type="text" id="Item" <?= $opt['disabled'] ?> class="form-control <?php if($validation->getError('Item')): ?>is-invalid<?php endif ?>" autofocus name="Item" value="<?php echo $data['Item']; ?>"/>
                        <?= $opt['button'] ?>
                        <?php if ($validation->getError('Item')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('Item') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text">
                        Descrição sobre o item.
                    </div>
                <?php } ?>

            </div>
        </div>
        <?php if($opt['action'] == 'editar' || $opt['action'] == 'habilitar' || $opt['action'] == 'desabilitar') { ?>
        <input type="hidden" name="idTabPreschuap_<?= $tabela ?>" value="<?= $data['idTabPreschuap_'.$tabela] ?>" />
        <?php } ?>
        <input type="hidden" name="action" value="<?= $opt['action'] ?>" />
    </form>

    <?php
        #/*
        if (isset($lista)) {
            if($tabela == 'Protocolo_Medicamento')
                echo $this->include('admin/tabela/list_tabela_medicamento');
            else
                echo $this->include('admin/tabela/list_tabela');
        }

        else
            echo '
                <div class="text-center">
                    <br /><br />
                    <a class="btn btn-warning" href="'.previous_url(-1).'"><i class="fa-solid fa-arrow-left"></i> Voltar</a>
                </div>
            ';
        #*/
    ?>

</main>

<?= $this->endSection() ?>
