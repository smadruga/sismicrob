<?= $this->extend('layouts/main_content') ?>
<?= $this->section('subcontent') ?>

<main class="container">
    <div class="text-center">
        <a class="btn btn-warning" href="<?= base_url('paciente/find_paciente') ?>"><i class="fa-solid fa-arrow-left"></i> Voltar</a>
        <br /><br />
    </div>

    <?= $pager->makeLinks($page, $perpage, $_SESSION['pager']['count'], 'bootstrap') ?>

    <table class="table table-hover table-bordered">
        <thead>
            <tr>
                <th scope="col">Nome</th>
                <th scope="col">Prontuário</th>
                <th scope="col">Nascimento</th>
                <th scope="col">Nome Mãe</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach($paciente['array'] as $v) {
                $opt = 'class="clickable-row" data-href="' . base_url('paciente/show_paciente/'.$v['codigo']) . '"';
                echo '
                <tr>
                    <th '.$opt.'>'.$v['nome'].'</th>
                    <td '.$opt.'>'.$v['prontuario'].'</td>
                    <td '.$opt.'>'.$func->mascara_data($v['dt_nascimento'], 'barras', TRUE, FALSE, TRUE).'</th>
                    <td '.$opt.'>'.$v['nome_mae'].'</th>
                    <td></th>
                </tr>
                ';
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5" class="text-center">Total: <?= $paciente['count'] . ' de ' . $_SESSION['pager']['count'] ?> resultados.</th>
            </tr>
        </tfoot>
    </table>

    <?= $pager->makeLinks($page, $perpage, $_SESSION['pager']['count'], 'bootstrap') ?>

    <div class="text-center">
        <a class="btn btn-warning" href="<?= base_url('paciente/find_paciente') ?>"><i class="fa-solid fa-arrow-left"></i> Voltar</a>
        <br /><br />
    </div>
</main>

<?= $this->endSection() ?>
