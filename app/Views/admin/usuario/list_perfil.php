<?= $this->extend('layouts/main_content') ?>
<?= $this->section('subcontent') ?>

<div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-primary rounded" style="width: 280px;">
    <?= $this->include('layouts/sidenavbar_usuario') ?>
</div>

<div class="col border rounded ms-2 p-2">
    <form method="post" action="<?= base_url('admin/set_perfil') ?>">
        <?= csrf_field() ?>
        <?php $validation = \Config\Services::validation(); ?>

        <div class="card-body has-validation">
            <label for="Pesquisar" class="form-label"><b>Adicionar Perfil</b></label>
            <div class="input-group mb-3">
                <select class="form-select <?php if($validation->getError('Perfil')): ?>is-invalid<?php endif ?>" id="Perfil"
                    name="Perfil" autofocus data-placeholder="Selecione um ou mais perfis" data-allow-clear="1">
                    <option></option>
                    <?php
                    foreach ($select['Perfil'] as $v)
                        #Desconsidera do select todos os perfis já atribuídos ao usuário
                        echo (!isset($list['delete'][$v['idTab_Perfil']])) ? '<option value="' . $v['idTab_Perfil'] . '">' . $v['Perfil'] . ' - ' . $v['Descricao'] . '</option>' : NULL;
                    ?>
                </select>
                <?php if ($validation->getError('Perfil')): ?>
                    <div class="invalid-feedback">
                        <?= $validation->getError('Perfil') ?>
                    </div>
                <?php endif; ?>
            </div>
            <div>
                <button class="btn btn-info" id="submit" type="submit"><i class="fa-solid fa-plus"></i> Adicionar</button>
            </div>
        </div>
    </form>

    <hr />

    <?php
    if(!isset($list['Perfil']) || $list['Perfil'] === FALSE) {
        echo '
        <div class="alert alert-secondary text-center" role="alert">
            <b>Nenhum perfil atribuído ao usuário</b>
        </div>
        ';
    }
    else {
    ?>
    <div class="text-center">
        <h4>Lista de Perfis atribuídos ao Usuário</h4>
    </div>
    <table class="table table-striped">
        <tr>
            <th>Perfil</th>
            <th>Descrição</th>
            <th></th>
        </tr>
        <?php
        foreach ($list['Perfil'] as $val) {
        ?>
            <tr>
                <td class="col-2"><?= $val['Perfil'] ?></td>
                <td class="col"><?= $val['Descricao'] ?></td>
                <td class="col-2 text-end">
                    <a href="<?= base_url('admin/del_perfil/'.$val['idSishuap_Perfil']) ?>" type="button" class="btn btn-danger">
                        <i class="fa-solid fa-trash-can"></i> Excluir</a>
                </td>
            </tr>
        <?php
        }
        ?>
    </table>
    <?php } ?>
</div>

<?= $this->endSection() ?>
