<?= $this->extend('layouts/main_content') ?>
<?= $this->section('subcontent') ?>
<?= $this->include('layouts/sidenavbar_paciente') ?>

<main class="col">

    <form method="post" action="<?= base_url('prescricao/manage_prescricao/') ?>">
        <?= csrf_field() ?>
        <?php $validation = \Config\Services::validation(); ?>

        <div class="card">
            <div class="card-header <?= $opt['bg'] ?> text-white">
                <b><?= $opt['title'] ?></b>
            </div>                 

            <div class="container overflow-hidden py-3">
                <div class="row g-3">

                    <div class="col-6">
                        <div>
                            <label for="Indicacao" class="form-label">Indicação <b class="text-danger">*</b></label>
                            <select data-placeholder="Selecione uma opção..." class="form-control select2"
                                    id="idTabSismicrob_Indicacao Chosen" 
                                    onchange="showHideDiv(this.value,this.name,'idTabSismicrob_Indicacao','1|3')"
                                    name="idTabSismicrob_Indicacao">
                                <option value="">Selecione uma opção...</option>
                                <?php

                                foreach ($select['Indicacao']->getResultArray() as $row) {
                                    if ($data['idTabSismicrob_Indicacao'] == $row['idTabSismicrob_Indicacao']) {
                                        echo '<option value="' . $row['idTabSismicrob_Indicacao'] . '" selected="selected">' . $row['Indicacao'] . '</option>';
                                    }
                                    else {
                                        echo '<option value="' . $row['idTabSismicrob_Indicacao'] . '">' . $row['Indicacao'] . '</option>';
                                    }
                                }

                                ?>
                            </select>                            
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-label idTabSismicrob_Indicacao1" id="#idTabSismicrob_Indicacao1" style="display: none;">
                            <label for="IndicacaoTipoCirurgia" class="form-label">Tipo de Cirurgia <b class="text-danger">*</b></label>
                            <input type="text" class="form-control" id="IndicacaoTipoCirurgia" maxlength="250"
                                    name="IndicacaoTipoCirurgia" value="<?php echo $data['IndicacaoTipoCirurgia'] ?>">
                        </div>
                    </div>
                </div>
            </div>                    
                    
            <div class="container overflow-hidden idTabSismicrob_Indicacao3" id="#idTabSismicrob_Indicacao3" style="display: none;">
                <div class="row g-3">
                    <div class="col-6">
                        <label for="idTabSismicrob_DiagnosticoInfeccioso" class="form-label">
                            Diagnóstico Infeccioso <b class="text-danger">*</b></label>
                        <select data-placeholder="Selecione uma opção..." class="form-control select2"
                                id="idTabSismicrob_DiagnosticoInfeccioso" 
                                onchange="showHideDiv(this.value,this.name,'idTabSismicrob_DiagnosticoInfeccioso','7')" 
                                name="idTabSismicrob_DiagnosticoInfeccioso">
                            <option value="">Selecione uma opção...</option>
                            <?php

                            foreach ($select['DiagnosticoInfeccioso']->getResultArray() as $row) {
                                if ($data['idTabSismicrob_DiagnosticoInfeccioso'] == $row['idTabSismicrob_DiagnosticoInfeccioso']) {
                                    echo '<option value="' . $row['idTabSismicrob_DiagnosticoInfeccioso'] . '" selected="selected">' . $row['DiagnosticoInfeccioso'] . '</option>';
                                }
                                else {
                                    echo '<option value="' . $row['idTabSismicrob_DiagnosticoInfeccioso'] . '">' . $row['DiagnosticoInfeccioso'] . '</option>';
                                }
                            }

                            ?>
                        </select>                            
                        <select data-placeholder="Selecione uma opção..." class="form-control select2"
                                id="idTabSismicrob_DiagnosticoInfeccioso" 
                                onchange="showHideDiv(this.value,this.name,'idTabSismicrob_DiagnosticoInfeccioso','7')" 
                                name="idTabSismicrob_DiagnosticoInfeccioso">
                            <option value="">Selecione uma opção...</option>
                            <?php

                            foreach ($select['DiagnosticoInfeccioso']->getResultArray() as $row) {
                                if ($data['idTabSismicrob_DiagnosticoInfeccioso'] == $row['idTabSismicrob_DiagnosticoInfeccioso']) {
                                    echo '<option value="' . $row['idTabSismicrob_DiagnosticoInfeccioso'] . '" selected="selected">' . $row['DiagnosticoInfeccioso'] . '</option>';
                                }
                                else {
                                    echo '<option value="' . $row['idTabSismicrob_DiagnosticoInfeccioso'] . '">' . $row['DiagnosticoInfeccioso'] . '</option>';
                                }
                            }

                            ?>
                        </select>
                    </div>
                    <div class="col-6">
                        <div class="form-label idTabSismicrob_DiagnosticoInfeccioso7" id="#idTabSismicrob_DiagnosticoInfeccioso7" 
                                style="display: none;">
                            <label for="DiagnosticoInfecciosoOutro" class="form-label">Especificar <b class="text-danger">*</b></label>
                            <input type="text" class="form-control" id="DiagnosticoInfecciosoOutro" maxlength="250"
                                    name="DiagnosticoInfecciosoOutro" value="<?php echo $data['DiagnosticoInfecciosoOutro'] ?>">
                        </div>
                    </div>
                </div>
            </div>

            <div class="container overflow-hidden py-3">
                <div class="row g-3">
                    <div class="col-12">
                        <div >
                            <label for="Justificativa" class="form-label">Justificativa</label>
                            <textarea class="form-control" id="Justificativa" maxlength="65000"
                                name="Justificativa"><?php echo $data['Justificativa'] ?></textarea>
                            <small id="JustificativaHelp" class="form-text text-muted">
                                <b class="text-warning">*</b>
                                O campo "Justificativa" será obrigatório se o campo "Indicação" for "Terapêutica".
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <hr/>

            <div class="container overflow-hidden py-3">
                <div class="row g-3">
                    <div class="col-4">
                        <div>
                            <label for="DataInicioTratamento" class="form-label">Data de Início <b class="text-danger">*</b></label>
                            <input type="date" id="DataInicioTratamento" class="form-control" 
                                onchange="calculaTempoTratamento('DataInicioTratamento', 'Duracao', 'DataFimTratamento')"
                                name="DataInicioTratamento" value="<?php echo $data['DataInicioTratamento'] ?>">
                        </div>
                    </div>
                    <div class="col-4">
                        <div>
                            <label for="Duracao" class="form-label">Duração <b class="text-danger">*</b></label>
                            <div class="input-group mb-3">
                                <input type="number" id="Duracao" class="form-control" 
                                    onchange="calculaTempoTratamento('DataInicioTratamento', 'Duracao', 'DataFimTratamento')"
                                    name="Duracao" value="<?php echo $data['Duracao'] ?>">
                                <span class="input-group-text" id="basic-addon2">dia(s)</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div>
                            <label for="DataFimTratamento" class="form-label">Fim do Tratamento</label>
                            <input type="date" id="DataFimTratamento" class="form-control" readonly
                                onchange="calculaTempoTratamento('DataInicioTratamento', 'Duracao', 'DataFimTratamento')"
                                name="DataFimTratamento" value="<?php echo $data['DataFimTratamento'] ?>">
                        </div>
                    </div>
                </div>
            </div>
     
            <div class="container overflow-hidden py-3">
                <div class="row g-3">
                    <div class="col-4">
                        <div>
                            <label for="DosePosologica" class="form-label">Dose Posológica de Manutenção <b class="text-danger">*</b></label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="DosePosologica" maxlength="18"
                                    onkeyup="calculaProduto('DosePosologica', 'Intervalo', 'DoseDiaria')"
                                    name="DosePosologica" value="<?php echo $data['DosePosologica'] ?>">

                                <div class="btn-group">
                                    <input type="radio" class="btn-check" name="UnidadeMedida" id="UnidadeMedidaG" autocomplete="off" 
                                        onchange="calculaProduto('DosePosologica', 'Intervalo', 'DoseDiaria')" 
                                        <?php echo $radio['UnidadeMedida']['c'][0] ?>/>
                                    <label class="btn btn-secondary <?php echo $radio['UnidadeMedida']['a'][0] ?>" for="UnidadeMedidaG" 
                                        data-mdb-ripple-init>g</label>

                                    <input type="radio" class="btn-check" name="UnidadeMedida" id="UnidadeMedidamG" autocomplete="off" 
                                        onchange="calculaProduto('DosePosologica', 'Intervalo', 'DoseDiaria')"
                                        <?php echo $radio['UnidadeMedida']['c'][1] ?>/>
                                    <label class="btn btn-secondary <?php echo $radio['UnidadeMedida']['a'][1] ?>" for="UnidadeMedidamG" 
                                        data-mdb-ripple-init>mg</label>

                                    <input type="radio" class="btn-check" name="UnidadeMedida" id="UnidadeMedidaUI" autocomplete="off" 
                                        onchange="calculaProduto('DosePosologica', 'Intervalo', 'DoseDiaria')"
                                        <?php echo $radio['UnidadeMedida']['c'][2] ?>/>
                                    <label class="btn btn-secondary <?php echo $radio['UnidadeMedida']['a'][2] ?>" for="UnidadeMedidaUI" 
                                        data-mdb-ripple-init>UI</label>
                                </div>
                            </div>
                        </div>                         
                    </div>
                    <div class="col-4">
                        <div>
                            <label for="Intervalo" class="form-label">Intervalo <b class="text-danger">*</b></label>
                            <div class="input-group mb-3">
                                <select data-placeholder="Selecione uma opção..." class="form-control select2"
                                        id="Intervalo" 
                                        onchange="calculaProduto('DosePosologica', 'Intervalo', 'DoseDiaria')"
                                        name="Intervalo">
                                    <option value="">Selecione uma opção...</option>
                                    <?php

                                    foreach ($select['Intervalo']->getResultArray() as $row) {
                                        if ($data['idTabSismicrob_Intervalo'] == $row['Intervalo'].'#'.$row['Codigo']) {
                                            echo '<option value="' . $row['Intervalo'].'#'.$row['Codigo'] . '" selected="selected">'.$row['Intervalo'].' '.$row['Codigo'].'</option>';
                                        }
                                        else {
                                            echo '<option value="' . $row['Intervalo'].'#'.$row['Codigo'] . '">'.$row['Intervalo'].' '.$row['Codigo'].'</option>';
                                        }
                                    }

                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div>
                            <label for="DoseDiaria" class="form-label">Dose diária</label>
                            <input type="text" class="form-control" id="DoseDiaria" readonly
                                name="DoseDiaria" value="<?php echo $data['DoseDiaria'] ?>">
                        </div>
                    </div>
                </div>
            </div>

                    <div class="col-12">
                        <div >
                            <hr />

                            <?= $opt['button'] ?>
                            <a class="btn btn-warning" href="javascript:history.go(-1)"><i class="fa-solid fa-arrow-left"></i> Voltar</a>
                        </div>
                    </div>


            <input type="hidden" name="Idade" id="Idade" value="<?= $_SESSION['Paciente']['idade'] ?>" />
            <input type="hidden" name="Sexo" id="Sexo" value="<?= $_SESSION['Paciente']['sexo'] ?>" />
            <input type="hidden" name="action" value="<?= $opt['action'] ?>" />
            <?php if($opt['action'] == 'editar' || $opt['action'] == 'excluir' || $opt['action'] == 'concluir') { ?>
            <input type="hidden" name="idPreschuap_Prescricao" value="<?= $data['idPreschuap_Prescricao'] ?>" />
            <?php } ?>

            
        </div>

    </form>

</main>

<?= $this->endSection() ?>
