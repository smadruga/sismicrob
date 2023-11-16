<?= $this->extend('layouts/main_content') ?>
<?= $this->section('subcontent') ?>

<main class="container">
    <div class="text-center">
        <a class="btn btn-warning" href="<?= previous_url() ?>"><i class="fa-solid fa-arrow-left"></i> Voltar</a>
        <br /><br />
    </div>

    <table class="table table-striped table-hover table-bordered">
        <thead>
            <tr>
                <th scope="col">Nome</th>
                <th scope="col">Usuário</th>
                <th scope="col">CPF</th>
                <th scope="col">E-mail</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            <?php for($i=0; $i<$ad['entries']['count']; $i++) { ?>
                <tr>
                    <td><?= (isset($ad['entries'][$i]['cn'][0])) ? esc(mb_convert_encoding($ad['entries'][$i]['cn'][0], "UTF-8", "ASCII")) : '' ?></td>
                    <th><?= (isset($ad['entries'][$i]['samaccountname'][0])) ? esc($ad['entries'][$i]['samaccountname'][0]) : '' ?></th>
                    <td><?= (isset($ad['entries'][$i]['employeeid'][0])) ? esc($func->mascara_cpf($ad['entries'][$i]['employeeid'][0])) : '' ?></th>
                    <td><?= (isset($ad['entries'][$i]['othermailbox'][0])) ? esc($ad['entries'][$i]['othermailbox'][0]) : ''  ?></th>
                    <td><a class="btn btn-info my-2 my-sm-0" href="<?= base_url('admin/get_user/'.$ad['entries'][$i]['samaccountname'][0]) ?>"><i class="fa-solid fa-upload"></i> Importar</a></th>
                </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5" class="text-center">Total: <?= $ad['entries']['count'] ?> resultados</th>
            </tr>
        </tfoot>
    </table>

    <div class="text-center">
        <a class="btn btn-warning" href="<?= previous_url() ?>"><i class="fa-solid fa-arrow-left"></i> Voltar</a>
        <br /><br />
    </div>
</main>

<?= $this->endSection() ?>
