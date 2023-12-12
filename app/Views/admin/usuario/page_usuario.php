<?= $this->extend('layouts/main_content') ?>
<?= $this->section('subcontent') ?>

<div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-primary rounded" style="width: 280px;">
    <?= $this->include('layouts/sidenavbar_usuario') ?>
</div>

<div class="col border rounded ms-2 p-4">

    <table class="table table-user-information">
        <tbody>

            <tr>
                <td width="30%"><i class="fa-solid fa-hospital-user"></i> Nome do Usuário:</td>
                <td><b><?= $_SESSION['Usuario']['Nome'] ?></b></td>
            </tr>
            <tr>
                <td><i class="fa-solid fa-address-card"></i> CPF:</td>
                <td><?= $func->mascara_cpf($_SESSION['Usuario']['Cpf']) ?></td>
            </tr>
            <tr class="bg-white">
                <td><i class="fa-solid fa-desktop"></i> Login EBSERH:</td>
                <td><?= $_SESSION['Usuario']['Usuario'] ?></td>
            </tr>
            <tr>
                <td><i class="fa-solid fa-at"></i> E-mail:</td>
                <td><?= $_SESSION['Usuario']['EmailSecundario'] ?></td>
            </tr>

            <tr>
                <td></td>
                <td>
                    <?php
                    #if($_SESSION['Usuario']['Inativo'] == 1) {
                    if(!isset($_SESSION['Usuario']['Permissao']) || !$_SESSION['Usuario']['Permissao'] || $_SESSION['Usuario']['Permissao'] == 'NULL' ) {
                        $bg     = 'danger';
                        $msg    = 'Usuário Inativo';
                        $fa     = 'user-slash';
                    }
                    else {
                        $bg     = 'success';
                        $msg    = 'Usuário Ativo';
                        $fa     = 'user-check';
                    }
                    ?>
                    <h4><span class="badge bg-<?= $bg ?>"><i class="fa-solid fa-<?= $fa ?>"></i> <?= $msg ?></span></h4>
                </td>
            </tr>

        </tbody>
    </table>

</div>

<?= $this->endSection() ?>
