<?= $this->extend('layouts/main_content') ?>
<?= $this->section('subcontent') ?>

<main class="container">

    <?= $pager->makeLinks($page, $perpage, $count, 'bootstrap') ?>

    <table class="table table-hover table-bordered" id="table"
                data-toggle="table"
                data-locale="pt-BR"
                data-id-field="Id"
                data-sortable="true"
                data-search="true"
                data-show-fullscreen="true"
                data-search-highlight="true"
                data-show-pagination-switch="true"
                data-pagination="true"
                data-page-list="[10, 25, 50, 100, all]"
                >
        <thead>
            <tr>
                <th scope="col" colspan="4" class="bg-light text-center">Tabela: <?= $tabela ?></th>
            </tr>
            <tr>
                <th scope="col" data-field="Id" data-sortable="true">Id</th>
                <th scope="col" data-field="Descrição" data-sortable="true">Descrição</th>
                <th scope="col" data-field="Status" data-sortable="true">Status</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach($lista->getResultArray() as $v) {
                $v['Inativo'] = (!$v['Inativo']) ? 'ATIVO' : 'INATIVO';
                echo '
                <tr>
                    <th>'.$v['idTabPreschuap_'.$tabela].'</th>
                    <td>'.$v[$tabela].'</td>
                    <td>'.$v['Inativo'].'</th>
                    <td></th>
                </tr>
                ';
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5" class="bg-light text-center">Total: <?= $lista->getNumRows().' de '. $count ?> resultado(s).</th>
            </tr>
        </tfoot>
    </table

</main>

<?= $this->endSection() ?>
