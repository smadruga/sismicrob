<?= $this->extend('layouts/main_content') ?>
<?= $this->section('subcontent') ?>

<main>

    <?php if ( ($tabela != 'Categoria' && $tabela != 'Subcategoria') ||
                (
                    ($tabela == 'Categoria' || $tabela == 'Subcategoria') && ($opt['action'] == 'habilitar' || $opt['action'] == 'desabilitar')
                )
            ) { ?>

        <form method="post" action="<?= base_url('tabela/list_tabela/'.$tabela) ?>">
            <?= csrf_field() ?>
            <?php $validation = \Config\Services::validation(); ?>

            <div class="card">
                <div class="card-header <?= $opt['bg'] ?> text-white">
                    <b><?= $opt['title'] ?></b>
                </div>
                <div class="card-body has-validation row g-3">

                    <?php if ($tabela == 'ViaAdministracao' || $tabela == 'Intervalo') { ?>
                        <div class="col-md-6">
                            <label for="Item" class="form-label"><b>Item</b> <b class="text-danger">*</b></label>
                            <div class="input-group mb-3">
                                <input type="text" id="Item" <?= $opt['disabled'] ?>  maxlength="100"
                                    class="form-control <?php if($validation->getError('Item')): ?>is-invalid<?php endif ?>"
                                    autofocus name="Item" value="<?php echo esc($data['Item']); ?>"/>

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
                        <div class="col-md-6">
                            <label for="Codigo" class="form-label"><b>Abreviação</b> <b class="text-danger">*</b></label>
                            <div class="input-group mb-3">
                                <input type="text" id="Codigo" <?= $opt['disabled'] ?>
                                    class="form-control <?php if($validation->getError('Codigo')): ?>is-invalid<?php endif ?>"
                                    maxlength="10" name="Codigo" value="<?php echo $data['Codigo']; ?>"/>

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
                        <br />
                        <?= $opt['button'] ?>
                    <?php } elseif ($tabela == 'Categoria' || $tabela == 'Subcategoria') { ?>
                        <div class="col-md-6">
                            <label for="Item" class="form-label"><b>Item</b> <b class="text-danger">*</b></label>
                            <div class="input-group mb-3">
                                <input type="text" id="Item" <?= $opt['disabled'] ?>  maxlength="100"
                                    class="form-control <?php if($validation->getError('Item')): ?>is-invalid<?php endif ?>"
                                    autofocus name="Item" value="<?php echo esc($data['Item']); ?>"/>

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
                        <div class="col-md-6">
                            <label for="Codigo" class="form-label"><b>Código</b> <b class="text-danger">*</b></label>
                            <div class="input-group mb-3">
                                <input type="text" id="Codigo" <?= $opt['disabled'] ?>
                                    class="form-control <?php if($validation->getError('Codigo')): ?>is-invalid<?php endif ?>"
                                    maxlength="10" name="Codigo" value="<?php echo $data['idTabSismicrob_'.$tabela]; ?>"/>

                                <?php if ($validation->getError('Codigo')): ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('Codigo') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="form-text">
                                Código do item.
                            </div>
                        </div>
                        <br />
                        <?= $opt['button'] ?>
                    <?php } elseif ($tabela == 'Protocolo') { ?>
                        <div class="col-md-6">
                            <label for="Item" class="form-label"><b>Protocolo</b> <b class="text-danger">*</b></label>
                            <div class="input-group mb-3">
                                <input <?= $opt['disabled'] ?> type="text" id="Item" <?= $opt['disabled'] ?> maxlength="100"
                                    class="form-control <?php if($validation->getError('Item')): ?>is-invalid<?php endif ?>"
                                    autofocus name="Item" value="<?php echo esc($data['Item']); ?>"/>

                                <?php if ($validation->getError('Item')): ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('Item') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="Aplicabilidade" class="form-label"><b>Aplicabilidade</b> <b class="text-danger">*</b></label>
                            <div class="input-group mb-3">

                                <select <?= $opt['disabled'] ?>
                                    class="form-select <?php if($validation->getError('Aplicabilidade')): ?>is-invalid<?php endif ?>"
                                    id="Aplicabilidade" name="Aplicabilidade" data-placeholder="Selecione uma opção" data-allow-clear="1">
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

                        <div class="col-md-6">
                            <label for="idTabSismicrob_TipoTerapia" class="form-label"><b>Tipo de Terapia</b> <b class="text-danger">*</b></label>
                            <div class="input-group mb-3">

                                <select <?= $opt['disabled'] ?>
                                    class="form-select <?php if($validation->getError('idTabSismicrob_TipoTerapia')): ?>is-invalid<?php endif ?>"
                                    id="idTabSismicrob_TipoTerapia" name="idTabSismicrob_TipoTerapia" data-placeholder="Selecione uma opção"
                                    data-allow-clear="1">
                                    <option></option>
                                    <?php
                                    foreach ($select['TipoTerapia']->getResultArray() as $val) {
                                        $selected = ($data['idTabSismicrob_TipoTerapia'] == $val['idTabSismicrob_TipoTerapia']) ? 'selected' : '';
                                        echo '<option value="'.$val['idTabSismicrob_TipoTerapia'].'" '.$selected.'>'.$val['TipoTerapia'].'</option>';
                                    }
                                    ?>
                                </select>

                                <?php if ($validation->getError('idTabSismicrob_TipoTerapia')): ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('idTabSismicrob_TipoTerapia') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="idTabSismicrob_Categoria" class="form-label"><b>Categoria</b> <b class="text-danger">*</b></label>
                            <div class="input-group mb-3">

                                <select <?= $opt['disabled'] ?> class="form-select select2 <?php if($validation->getError('idTabSismicrob_Categoria')): ?>is-invalid<?php endif ?>"
                                    id="idTabSismicrob_Categoria" name="idTabSismicrob_Categoria" data-placeholder="Selecione uma opção"
                                    data-allow-clear="1">
                                    <option></option>
                                    <?php
                                    foreach ($select['Categoria']->getResultArray() as $val) {
                                        $selected = ($data['idTabSismicrob_Categoria'] == $val['idTabSismicrob_Categoria']) ? 'selected' : '';
                                        echo '<option value="'.$val['idTabSismicrob_Categoria'].'" '.$selected.'>'.$val['idTabSismicrob_Categoria'].' - '.$val['Categoria'].'</option>';
                                    }
                                    ?>
                                </select>

                                <?php if ($validation->getError('idTabSismicrob_Categoria')): ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('idTabSismicrob_Categoria') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="col-md-12">
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

                        <br />
                        <?= $opt['button'] ?>

                    <?php } elseif ($tabela == 'Protocolo_Medicamento') { ?>
                        <div class="col-md-12">
                            <b>OBSERVAÇÃO:</b><br>
                            - Campos marcados com <b class="text-danger">*</b> (asterisco vermelho) são obrigatórios para todos os medicamentos;<br>
                            - Campos marcados com <b class="text-info">*</b> (asterisco azul) são obrigatórios <b>apenas</b> para os medicamentos marcados como VIA ENDOVENOSA no campo VIA DE ADMINISTRAÇÃO.<br><br>
                        </div>
                        
                        
                        <div class="col-md-8">
                            <label for="Item" class="form-label"><b>Medicamento</b> <b class="text-danger">*</b></label>
                            <div class="input-group mb-3">

                                <select <?= $opt['disabled'] ?> autofocus
                                    class="form-select select2 <?php if($validation->getError('Item')): ?>is-invalid<?php endif ?>" id="Item"
                                    name="Item" data-placeholder="Selecione uma opção" data-allow-clear="1">
                                    <option></option>
                                    <?php
                                    foreach ($select['Medicamento']->getResultArray() as $val) {
                                        $selected = ($data['Item'] == $val['idTabSismicrob_Medicamento']) ? 'selected' : '';
                                        echo '<option value="'.$val['idTabSismicrob_Medicamento'].'" '.$selected.'>'.$val['Medicamento'].'</option>';
                                    }
                                    ?>
                                </select>

                                <?php if ($validation->getError('Item')): ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('Item') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="idTabSismicrob_EtapaTerapia" class="form-label"><b>Etapa da Terapia</b> <b class="text-danger">*</b></label>
                            <div class="input-group mb-3">

                                <select <?= $opt['disabled'] ?>
                                    class="form-select <?php if($validation->getError('idTabSismicrob_EtapaTerapia')): ?>is-invalid<?php endif ?>"
                                    id="idTabSismicrob_EtapaTerapia" name="idTabSismicrob_EtapaTerapia" data-placeholder="Selecione uma opção"
                                    data-allow-clear="1">
                                    <option></option>
                                    <?php
                                    foreach ($select['EtapaTerapia']->getResultArray() as $val) {
                                        $selected = ($data['idTabSismicrob_EtapaTerapia'] == $val['idTabSismicrob_EtapaTerapia']) ? 'selected' : '';
                                        echo '<option value="'.$val['idTabSismicrob_EtapaTerapia'].'" '.$selected.'>'.$val['EtapaTerapia'].'</option>';
                                    }
                                    ?>
                                </select>

                                <?php if ($validation->getError('idTabSismicrob_EtapaTerapia')): ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('idTabSismicrob_EtapaTerapia') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="Dose" class="form-label"><b>Dose</b> <b class="text-danger">*</b></label>
                            <div class="input-group mb-3">

                                <input type="text" id="Dose" <?= $opt['disabled'] ?> placeholder="0,00"
                                    class="form-control <?php if($validation->getError('Dose')): ?>is-invalid<?php endif ?>"
                                    maxlength="9" name="Dose" value="<?php echo $data['Dose']; ?>"/>

                                <select <?= $opt['disabled'] ?> class="form-select <?php if($validation->getError('idTabSismicrob_UnidadeMedida')): ?>is-invalid<?php endif ?>" id="idTabSismicrob_UnidadeMedida"
                                    name="idTabSismicrob_UnidadeMedida" data-placeholder="Selecione uma opção" data-allow-clear="1">
                                    <option></option>
                                    <?php
                                    foreach ($select['UnidadeMedida']->getResultArray() as $val) {
                                        $selected = ($data['idTabSismicrob_UnidadeMedida'] == $val['idTabSismicrob_UnidadeMedida']) ? 'selected' : '';
                                        echo '<option value="'.$val['idTabSismicrob_UnidadeMedida'].'" '.$selected.'>'.$val['Representacao'].'</option>';
                                    }
                                    ?>
                                </select>

                                <?php if ($validation->getError('Dose')) { ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('Dose') ?>
                                    </div>
                                <?php } elseif ($validation->getError('idTabSismicrob_UnidadeMedida')) { ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('idTabSismicrob_UnidadeMedida') ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="idTabSismicrob_ViaAdministracao" class="form-label"><b>Via de Administração</b> <b class="text-danger">*</b></label>
                            <div class="input-group mb-3">

                                <select <?= $opt['disabled'] ?>
                                    class="form-select <?php if($validation->getError('idTabSismicrob_ViaAdministracao')): ?>is-invalid<?php endif ?>"
                                    id="idTabSismicrob_ViaAdministracao" name="idTabSismicrob_ViaAdministracao" data-placeholder="Selecione uma opção"
                                    data-allow-clear="1">
                                    <option></option>
                                    <?php
                                    foreach ($select['ViaAdministracao']->getResultArray() as $val) {
                                        $selected = ($data['idTabSismicrob_ViaAdministracao'] == $val['idTabSismicrob_ViaAdministracao']) ? 'selected' : '';
                                        echo '<option value="'.$val['idTabSismicrob_ViaAdministracao'].'" '.$selected.'>'.$val['ViaAdministracao'].'</option>';
                                    }
                                    ?>
                                </select>

                                <?php if ($validation->getError('idTabSismicrob_ViaAdministracao')): ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('idTabSismicrob_ViaAdministracao') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="idTabSismicrob_Diluente" class="form-label"><b>Diluente</b> <b class="text-info">*</b></label>
                            <div class="input-group mb-3">

                                <select <?= $opt['disabled'] ?>
                                    class="form-select <?php if($validation->getError('idTabSismicrob_Diluente')): ?>is-invalid<?php endif ?>"
                                    id="idTabSismicrob_Diluente" name="idTabSismicrob_Diluente" data-placeholder="Selecione uma opção"
                                    data-allow-clear="1">
                                    <option></option>
                                    <?php
                                    foreach ($select['Diluente']->getResultArray() as $val) {
                                        $selected = ($data['idTabSismicrob_Diluente'] == $val['idTabSismicrob_Diluente']) ? 'selected' : '';
                                        echo '<option value="'.$val['idTabSismicrob_Diluente'].'" '.$selected.'>'.$val['Diluente'].'</option>';
                                    }
                                    ?>
                                </select>

                                <?php if ($validation->getError('idTabSismicrob_Diluente')): ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('idTabSismicrob_Diluente') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label for="Volume" class="form-label"><b>Volume</b> <b class="text-info">*</b></label>
                            <div class="input-group mb-3">

                                <input type="text" id="Volume" <?= $opt['disabled'] ?> placeholder="0,00"
                                    class="form-control <?php if($validation->getError('Volume')): ?>is-invalid<?php endif ?>"
                                    maxlength="9" name="Volume" value="<?php echo $data['Volume']; ?>"/>

                                <?php if ($validation->getError('Volume')): ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('Volume') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="TempoInfusao" class="form-label"><b>Tempo de Infusão</b> <b class="text-info">*</b></label>
                            <div class="input-group mb-3">

                                <input type="text" id="TempoInfusao" <?= $opt['disabled'] ?>
                                    class="form-control <?php if($validation->getError('TempoInfusao')): ?>is-invalid<?php endif ?>"
                                    maxlength="100" name="TempoInfusao" value="<?php echo $data['TempoInfusao']; ?>"/>

                                <?php if ($validation->getError('TempoInfusao')): ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('TempoInfusao') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="idTabSismicrob_Posologia" class="form-label"><b>Posologia</b> <b class="text-danger">*</b></label>
                            <div class="input-group mb-3">

                                <select <?= $opt['disabled'] ?>
                                    class="form-select select2 <?php if($validation->getError('idTabSismicrob_Posologia')): ?>is-invalid<?php endif ?>"
                                    id="idTabSismicrob_Posologia" name="idTabSismicrob_Posologia" data-placeholder="Selecione uma opção"
                                    data-allow-clear="1">
                                    <option></option>
                                    <?php
                                    foreach ($select['Posologia']->getResultArray() as $val) {
                                        $selected = ($data['idTabSismicrob_Posologia'] == $val['idTabSismicrob_Posologia']) ? 'selected' : '';
                                        echo '<option value="'.$val['idTabSismicrob_Posologia'].'" '.$selected.'>'.$val['Posologia'].'</option>';
                                    }
                                    ?>
                                </select>

                                <?php if ($validation->getError('idTabSismicrob_Posologia')): ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('idTabSismicrob_Posologia') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <input type="hidden" name="idTabSismicrob_Protocolo" value="<?= $data['idTabSismicrob_Protocolo'] ?>" />
                        <input type="hidden" name="OrdemInfusao" value="<?= $data['OrdemInfusao'] ?>" />
                        <?= $opt['button'] ?>
                    <?php } else { ?>
                        <label for="Item" class="form-label"><b>Item</b> <b class="text-danger">*</b></label>
                        <div class="col-md-12">
                            <div class="input-group mb-3">
                                <input type="text" id="Item" <?= $opt['disabled'] ?>
                                    class="form-control <?php if($validation->getError('Item')): ?>is-invalid<?php endif ?>" autofocus maxlength="100"
                                    name="Item" value="<?php echo esc($data['Item']); ?>"/>
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
                        <?= $opt['button'] ?>
                    <?php } ?>

                </div>
            </div>
            <?php if($opt['action'] == 'editar' || $opt['action'] == 'habilitar' || $opt['action'] == 'desabilitar') { ?>
            <input type="hidden" name="idTabSismicrob_<?= $tabela ?>" value="<?= $data['idTabSismicrob_'.$tabela] ?>" />
            <?php } ?>
            <input type="hidden" name="action" value="<?= $opt['action'] ?>" />
        </form>
    <?php } ?>

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
