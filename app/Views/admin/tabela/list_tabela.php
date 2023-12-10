<hr>

<div class="alert alert-warning text-center" role="alert">
    <b><i class="fa-solid fa-circle-exclamation"></i> IMPORTANTE:</b> A edição dos itens da tabela é permitida até <b>30 dias após a Data de Cadastro</b>.
</div>

<table class="table table-hover table-bordered" id="table"
    data-toggle="table"
    data-locale="pt-BR"
    data-id-field="Id"
    data-sortable="true"
    data-search="true"
    data-pagination="true"
    >
    <thead>
        <tr>
            <th scope="col" colspan="<?= $tab['colspan'] ?>" class="bg-light text-center">Tabela: <?= $tabela ?></th>
        </tr>
        <tr>
            <th scope="col" class="col-1" data-field="Id" data-sortable="true">Id</th>
            <th scope="col" data-field="Descrição" data-sortable="true">Descrição</th>
            <?php if ($tab['colspan'] == 6) { ?>
                <th scope="col" data-field="Abreviação" data-sortable="true">Abreviação</th>
            <?php } ?>
            <th scope="col" data-field="Status" data-sortable="true">Status</th>
            <th scope="col" data-field="DataCadastro" data-sortable="true">Data Cadastro</th>
            <th scope="col" class="col-2"></th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach($lista->getResultArray() as $v) {
            $va = ($tab['colspan'] == 6) ? '<td>'.$v['Codigo'].'</td>' : NULL;

            if (!$v['Inativo']) {
                $v['Inativo'] = '<span class="badge rounded-pill bg-success">ATIVO</span>';
                $manage = '<a href="'.base_url('tabela/list_tabela/'.$tabela.'/desabilitar/'.$v['idTabSismicrob_'.$tabela]).'" type="button" class="btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Desabilitar"><i class="fa-solid fa-ban"></i></a>';
            }
            else {
                $v['Inativo'] = '<span class="badge rounded-pill bg-danger">INATIVO</span>';
                $manage = '<a href="'.base_url('tabela/list_tabela/'.$tabela.'/habilitar/'.$v['idTabSismicrob_'.$tabela]).'" type="button" class="btn btn-success" data-bs-toggle="tooltip" data-bs-placement="top" title="Habilitar"><i class="fa-solid fa-circle-check"></i></a>';
            }

            $diff = ($func->dateDifference($v['DataCadastro'], date('Y-m-d H:i:s')) < 30 && $tabela != 'Categoria' && $tabela != 'Subcategoria') ? '<a href="'.base_url('tabela/list_tabela/'.$tabela.'/editar/'.$v['idTabSismicrob_'.$tabela]).'" type="button" class="btn btn-warning" data-bs-toggle="tooltip" data-bs-placement="top" title="Editar" ><i class="fa-solid fa-pen-to-square"></i></a>' : NULL;

            $medicamentos = ($tabela == 'Protocolo') ? '<a href="'.base_url('tabela/list_tabela/Protocolo_Medicamento/cadastrar/'.$v['idTabSismicrob_'.$tabela]).'" type="button" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Medicamentos"><i class="fa-solid fa-pills"></i></a>' : NULL;

            echo '
                <tr>
                    <td>'.$v['idTabSismicrob_'.$tabela].'</td>
                    <td>'.$v[$tabela].'</td>
                        '.$va.'
                    <td>'.$v['Inativo'].'</d>
                    <td>'.$v['Cadastro'].'</td>
                    <td class="text-center">
                        '.$medicamentos.'
                        '.$diff.'
                        '.$manage.'
                    </td>
                </tr>
            ';
        }
        ?>
    </tbody>
</table>
