<div class="text-center">

</div>

<hr>

<div class="alert alert-info text-center" role="alert">
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
            <th scope="col" colspan="10" class="bg-light text-center">Tabela: <?= $tabela ?></th>
        </tr>
        <tr>
            <th scope="col" data-field="Ordem Infusão" data-sortable="true">
                <a href="<?= base_url('tabela/sort_medicamento/'.$data['idTabPreschuap_Protocolo']) ?>" type="button" class="btn btn-sm btn-warning" title="Reorganizar Ordem de Infusão - Para quando houver dois ou mais itens com a mesma ordem."><i class="fa-solid fa-shuffle"></i></a>
                Ordem Infusão
            </th>
            <th scope="col" data-field="Medicamento" data-sortable="true">Medicamento</th>
            <th scope="col" data-field="EtapaTerapia" data-sortable="true">Etapa Terapia</th>
            <th scope="col" data-field="Dose" data-sortable="true">Dose</th>
            <th scope="col" data-field="Via Administração" data-sortable="true">Via Administração</th>
            <th scope="col" data-field="Diluente" data-sortable="true">Diluente</th>
            <th scope="col" data-field="Volume" data-sortable="true">Volume (ml)</th>
            <th scope="col" data-field="Tempo Infusão" data-sortable="true">Tempo Infusão</th>
            <th scope="col" data-field="Posologia" data-sortable="true">Posologia</th>
            <th scope="col" class="col-1"></th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach($lista->getResultArray() as $v) {

            if($v['Inativo'] == 1)
                $sort = NULL;
            else {
                if($v['OrdemInfusao'] == 1)
                    $sort = '
                        <a href="'.base_url('tabela/sort_item/'.$v['idTabPreschuap_Protocolo'].'/'.$v['OrdemInfusao'].'/down').'" type="button" class="btn btn-sm btn-outline-info" title="Alterar para '.($v['OrdemInfusao']+1).'"><i class="fa-solid fa-sort-down"></i></a>
                    ';
                elseif($v['OrdemInfusao'] == $count)
                    $sort = '
                        <a href="'.base_url('tabela/sort_item/'.$v['idTabPreschuap_Protocolo'].'/'.$v['OrdemInfusao'].'/up').'" type="button" class="btn btn-sm btn-outline-info" title="Alterar para '.($v['OrdemInfusao']-1).'"><i class="fa-solid fa-sort-up"></i></a>
                    ';
                else
                    $sort = '
                        <a href="'.base_url('tabela/sort_item/'.$v['idTabPreschuap_Protocolo'].'/'.$v['OrdemInfusao'].'/up').'" type="button" class="btn btn-sm btn-outline-info" title="Alterar para '.($v['OrdemInfusao']-1).'"><i class="fa-solid fa-sort-up"></i></a>
                        <a href="'.base_url('tabela/sort_item/'.$v['idTabPreschuap_Protocolo'].'/'.$v['OrdemInfusao'].'/down').'" type="button" class="btn btn-sm btn-outline-info" title="Alterar para '.($v['OrdemInfusao']+1).'"><i class="fa-solid fa-sort-down"></i></a>
                    ';
            }

            if (!$v['Inativo']) {
                $v['Inativo'] = '<span class="badge rounded-pill bg-success"><i class="fa-solid fa-circle-check" title="Ativo" ></i></span>';
                $manage = '<a href="'.base_url('tabela/list_tabela/'.$tabela.'/desabilitar/'.$v['idTabPreschuap_'.$tabela].'/'.$v['idTabPreschuap_Protocolo']).'" type="button" class="btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Desabilitar"><i class="fa-solid fa-ban"></i></a>';
            }
            else {
                $v['Inativo'] = '<span class="badge rounded-pill bg-danger"><i class="fa-solid fa-ban" title="Inativo"></i></span>';
                $manage = '<a href="'.base_url('tabela/list_tabela/'.$tabela.'/habilitar/'.$v['idTabPreschuap_'.$tabela].'/'.$v['idTabPreschuap_Protocolo']).'" type="button" class="btn btn-info" data-bs-toggle="tooltip" data-bs-placement="top" title="Habilitar"><i class="fa-solid fa-circle-exclamation"></i></a>';
            }

            echo '
                <tr>
                    <td>
                        <div class="container">
                            <div class="row">
                                <div class="col-2">
                                    '.$v['OrdemInfusao'].'
                                </div>
                                <div class="col text-left">
                                    '.$sort.'
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>'.$v['Medicamento'].' '.$v['Inativo'].'</td>
                    <td>'.$v['EtapaTerapia'].'</td>
                    <td>'.$v['Dose'].'</td>
                    <td>'.$v['ViaAdministracao'].'</td>
                    <td>'.$v['Diluente'].'</td>
                    <td>'.$v['Volume'].'</td>
                    <td>'.$v['TempoInfusao'].'</td>
                    <td>'.$v['Posologia'].'</td>
                    <td class="text-center">
                        <a href="'.base_url('tabela/list_tabela/'.$tabela.'/editar/'.$v['idTabPreschuap_'.$tabela]).'" type="button" class="btn btn-warning" data-bs-toggle="tooltip" data-bs-placement="top" title="Editar" ><i class="fa-solid fa-pen-to-square"></i></a>
                        '.$manage.'
                    </td>
                </tr>
            ';
        }
        ?>
    </tbody>
</table>
