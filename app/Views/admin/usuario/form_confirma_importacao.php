<?= $this->extend('layouts/main_content') ?>
<?= $this->section('subcontent') ?>

<main class="container">
    <form method="post" action="<?= base_url('admin/import_user') ?>">
        <?= csrf_field() ?>
        <?php $validation = \Config\Services::validation(); ?>

        <div class="card">
            <div class="card-header bg-warning">
                <b>Confirmar a importação do usuário abaixo?</b>
            </div>
            <div class="card-body">
                <div class="container">
                    <div class="row">
                        <div class="col-2 text-end">
                            <b>Nome:</b>
                        </div>
                        <div class="col">
                            <?= (isset($ad['entries'][0]['cn'][0])) ? esc(mb_convert_encoding($ad['entries'][0]['cn'][0], "UTF-8", "ASCII")) : '' ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-2 text-end">
                            <b>Usuário:</b>
                        </div>
                        <div class="col">
                            <?= (isset($ad['entries'][0]['samaccountname'][0])) ? esc($ad['entries'][0]['samaccountname'][0]) : '' ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-2 text-end">
                            <b>CPF:</b>
                        </div>
                        <div class="col">
                            <?= (isset($ad['entries'][0]['employeeid'][0])) ? esc($func->mascara_cpf($ad['entries'][0]['employeeid'][0])) : '' ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-2 text-end">
                            <b>E-mail:</b>
                        </div>
                        <div class="col">
                            <?= (isset($ad['entries'][0]['othermailbox'][0])) ? esc($ad['entries'][0]['othermailbox'][0]) : '' ?>
                        </div>
                    </div>
                    <br />
                    <div class="row">
                        <div class="col-2 text-end">
                            <a class="btn btn-warning" href="<?= previous_url() ?>"><i class="fa-solid fa-ban"></i> Cancelar</a>
                        </div>
                        <div class="col">
                            <button class="btn btn-primary" id="submit" type="submit"><i class="fa-solid fa-check-circle"></i> Confirmar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" name="Usuario" value="<?= $ad['entries'][0]['samaccountname'][0] ?>"/>

    </form>
</main>

<?= $this->endSection() ?>
