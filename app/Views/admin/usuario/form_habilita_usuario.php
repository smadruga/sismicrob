<?= $this->extend('layouts/main_content') ?>
<?= $this->section('subcontent') ?>

<div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-primary rounded" style="width: 280px;">
    <?= $this->include('layouts/sidenavbar_usuario') ?>
</div>

<div class="col border rounded ms-2 p-4">

    <div class="alert alert-warning" role="alert">
        Deseja ativar o usuário abaixo?
    </div>
    <form method="post" action="<?= base_url('admin/enable_user/'.$_SESSION['Usuario']['idSishuap_Usuario']) ?>">
        <?= csrf_field() ?>
        <div class="container">
            <div class="row">
                <div class="col-2 text-end">
                    <b>Nome:</b>
                </div>
                <div class="col">
                    <?= $_SESSION['Usuario']['Nome'] ?>
                </div>
            </div>
            <div class="row">
                <div class="col-2 text-end">
                    <b>Usuário:</b>
                </div>
                <div class="col">
                    <?= $_SESSION['Usuario']['Usuario'] ?>
                </div>
            </div>
            <div class="row">
                <div class="col-2 text-end">
                    <b>CPF:</b>
                </div>
                <div class="col">
                    <?= $_SESSION['Usuario']['Cpf'] ?>
                </div>
            </div>
            <div class="row">
                <div class="col-2 text-end">
                    <b>E-mail:</b>
                </div>
                <div class="col">
                    <?= $_SESSION['Usuario']['EmailSecundario'] ?>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col text-start" colspan="2">
                    <input type="hidden" name="Habilitar" value="1"/>
                    <a class="btn btn-warning" href="<?= previous_url() ?>"><i class="fa-solid fa-ban"></i> Cancelar</a>
                    <button class="btn btn-primary" id="submit" type="submit"><i class="fa-solid fa-check-circle"></i> Habilitar</button>
                </div>
            </div>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
