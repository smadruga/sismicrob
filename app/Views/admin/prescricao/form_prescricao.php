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
                <div class="row">

                    <div class="col-6">

                            <label for="idTabSismicrob_Indicacao" class="form-label">Indicação <b class="text-danger">*</b></label>
                            <div class="input-group mb-3">
                                <select <?= $opt['disabled'] ?> class="form-control select2
                                    <?php if($validation->getError('idTabSismicrob_Indicacao')): ?>is-invalid<?php endif ?>"
                                        id="idTabSismicrob_Indicacao" name="idTabSismicrob_Indicacao" data-placeholder="Selecione uma opção..."
                                        onchange="showHideDiv(this.value,this.name,'idTabSismicrob_Indicacao','1|3')"
                                        data-allow-clear="1">
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                        foreach ($select['Indicacao']->getResultArray() as $val) {
                                            $selected = ($data['idTabSismicrob_Indicacao'] == $val['idTabSismicrob_Indicacao']) ? 'selected' : '';
                                            echo '<option value="'.$val['idTabSismicrob_Indicacao'].'" '.$selected.'>'.$val['Indicacao'].'</option>';
                                        }
                                    ?>
                                </select>

                                <?php if ($validation->getError('idTabSismicrob_Indicacao')): ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('idTabSismicrob_Indicacao') ?>
                                    </div>
                                <?php endif; ?>
                                                        
                            </div>

                    </div>
                    <div class="col-6">
                        <div class="form-label idTabSismicrob_Indicacao1" id="#idTabSismicrob_Indicacao1" 
                            <?= $div['idTabSismicrob_Indicacao1'] ?>>
                            <label for="IndicacaoTipoCirurgia" class="form-label">Tipo de Cirurgia <b class="text-danger">*</b></label>
                            <input type="text" class="form-control 
                                <?php if($validation->getError('IndicacaoTipoCirurgia')): ?>is-invalid<?php endif ?>" 
                                id="IndicacaoTipoCirurgia" maxlength="50" name="IndicacaoTipoCirurgia" 
                                value="<?php echo $data['IndicacaoTipoCirurgia'] ?>">
                            <?php if ($validation->getError('IndicacaoTipoCirurgia')): ?>
                                <div class="invalid-feedback">
                                    <?= $validation->getError('IndicacaoTipoCirurgia') ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>                    
                    
            <div class="container overflow-hidden idTabSismicrob_Indicacao3" id="#idTabSismicrob_Indicacao3" 
                <?php echo $div['idTabSismicrob_Indicacao3'] ?>>
                <div class="row">
                    <div class="col-6">
                        <label for="idTabSismicrob_DiagnosticoInfeccioso" class="form-label">
                            Diagnóstico Infeccioso <b class="text-danger">*</b></label>
                        <select data-placeholder="Selecione uma opção..." class="form-control select2
                                <?php if($validation->getError('idTabSismicrob_DiagnosticoInfeccioso')): ?>is-invalid<?php endif ?>"
                                id="idTabSismicrob_DiagnosticoInfeccioso" 
                                onchange="showHideDiv(this.value,this.name,'idTabSismicrob_DiagnosticoInfeccioso','7')" 
                                name="idTabSismicrob_DiagnosticoInfeccioso">
                            <option value="">Selecione uma opção...</option>
                            <?php

                            foreach ($select['DiagnosticoInfeccioso']->getResultArray() as $row) {
                                $selected = ($data['idTabSismicrob_DiagnosticoInfeccioso'] == $row['idTabSismicrob_DiagnosticoInfeccioso']) ? 'selected' : '';
                                echo '<option value="'.$row['idTabSismicrob_DiagnosticoInfeccioso'].'" '.$selected.'>'.$row['DiagnosticoInfeccioso'].'</option>';
                            }

                            ?>
                        </select>      
                        <?php if ($validation->getError('idTabSismicrob_DiagnosticoInfeccioso')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('idTabSismicrob_DiagnosticoInfeccioso') ?>
                            </div>
                        <?php endif; ?>                                              
                    </div>
                    <div class="col-6">
                        <div class="form-label idTabSismicrob_DiagnosticoInfeccioso7" id="#idTabSismicrob_DiagnosticoInfeccioso7" 
                            <?php echo $div['idTabSismicrob_DiagnosticoInfeccioso'] ?>>
                            <label for="DiagnosticoInfecciosoOutro" class="form-label">Especificar <b class="text-danger">*</b></label>
                            <input type="text" class="form-control 
                                    <?php if($validation->getError('DiagnosticoInfecciosoOutro')): ?>is-invalid<?php endif ?>" 
                                    id="DiagnosticoInfecciosoOutro" maxlength="250"
                                    name="DiagnosticoInfecciosoOutro" value="<?php echo $data['DiagnosticoInfecciosoOutro'] ?>">
                        </div>
                    </div>
                    <?php if ($validation->getError('DiagnosticoInfecciosoOutro')): ?>
                        <div class="invalid-feedback">
                            <?= $validation->getError('DiagnosticoInfecciosoOutro') ?>
                        </div>
                    <?php endif; ?>                    
                </div>
            </div>

            <div class="container overflow-hidden py-3">
                
                <div class="row g-3">
                    <div class="col-12">
                        <div >
                            <label for="Justificativa" class="form-label">Justificativa</label>
                            <textarea class="form-control 
                                <?php if($validation->getError('Justificativa')): ?>is-invalid<?php endif ?>" 
                                id="Justificativa" maxlength="65000" name="Justificativa" ><?php echo $data['Justificativa'] ?></textarea>
                            <small id="JustificativaHelp" class="form-text text-muted">
                                <b class="text-warning">*</b>
                                O campo "Justificativa" será obrigatório se o campo "Indicação" for "Terapêutica".
                            </small>
                            <?php if ($validation->getError('Justificativa')): ?>
                                <div class="invalid-feedback">
                                    <?= $validation->getError('Justificativa') ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <hr/>

                <div class="row g-3">
                    <div class="col-md-12">
                            <label for="Medicamento" class="form-label">Medicamento <b class="text-danger">*</b></label>
                            <div class="input-group mb-3">
                                <select <?= $opt['disabled'] ?> class="form-select select2 
                                    <?php if($validation->getError('Medicamento')): ?>is-invalid<?php endif ?>"
                                        id="Medicamento" name="Medicamento" data-placeholder="Selecione uma opção"
                                        data-allow-clear="1">
                                        <option value="">Selecione uma opção</option>
                                        <?php
                                        foreach ($select['Medicamento']->getResultArray() as $row) {
                                            $selected = ($data['Medicamento'] == $row['Codigo'].'#'.$row['Medicamento']) ? 'selected' : '';
                                            echo '<option value="'.$row['Codigo'].'#'.$row['Medicamento'].'" '.$selected.'>'.$row['Medicamento'].'</option>';
                                        }
                                    ?>
                                </select>

                                <?php if ($validation->getError('Medicamento')): ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('Medicamento') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                    <div class="col-4">
                        <div>
                            <label for="DataInicioTratamento" class="form-label">Data de Início <b class="text-danger">*</b></label>
                            <input type="date" id="DataInicioTratamento" class="form-control 
                                <?php if($validation->getError('DataInicioTratamento')): ?>is-invalid<?php endif ?>" 
                                onchange="calculaTempoTratamento('DataInicioTratamento', 'Duracao', 'DataFimTratamento')"
                                name="DataInicioTratamento" value="<?php echo $data['DataInicioTratamento'] ?>">
                            <?php if ($validation->getError('DataInicioTratamento')): ?>
                                <div class="invalid-feedback">
                                    <?= $validation->getError('DataInicioTratamento') ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-4">
                        <div>
                            <label for="Duracao" class="form-label">Duração <b class="text-danger">*</b></label>
                            <div class="input-group mb-3">
                                <input type="number" id="Duracao" class="form-control
                                    <?php if($validation->getError('Duracao')): ?>is-invalid<?php endif ?>" 
                                    onchange="calculaTempoTratamento('DataInicioTratamento', 'Duracao', 'DataFimTratamento')"
                                    name="Duracao" value="<?php echo $data['Duracao'] ?>">
                                <span class="input-group-text" id="basic-addon2">dia(s)</span>
                                
                                <?php if ($validation->getError('Duracao')): ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('Duracao') ?>
                                    </div>
                                <?php endif; ?>
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

                <div class="row g-3">
                    <div class="col-4">
                        <div>
                            <label for="DosePosologica" class="form-label">Dose Posológica de Manutenção <b class="text-danger">*</b></label>
                            <div class="input-group">
                                <input type="text" class="form-control
                                <?php if($validation->getError('DosePosologica')): ?>is-invalid<?php endif ?>" 
                                    onkeyup="calculaProduto('DosePosologica', 'idTabSismicrob_Intervalo', 'DoseDiaria')"
                                    name="DosePosologica" id="DosePosologica" maxlength="18" value="<?php echo $data['DosePosologica'] ?>">

                                
                                    <input type="radio" class="btn-check" name="UnidadeMedida" id="UnidadeMedidaG" autocomplete="off" value="g"
                                        onchange="calculaProduto('DosePosologica', 'idTabSismicrob_Intervalo', 'DoseDiaria')" 
                                        <?php echo $radio['UnidadeMedida']['c'][0] ?>/>
                                    <label class="btn btn-success <?php echo $radio['UnidadeMedida']['a'][0] ?>" for="UnidadeMedidaG" 
                                        data-mdb-ripple-init name="UnidadeMedida">g</label>

                                    <input type="radio" class="btn-check" name="UnidadeMedida" id="UnidadeMedidamG" autocomplete="off" value="mg"
                                        onchange="calculaProduto('DosePosologica', 'idTabSismicrob_Intervalo', 'DoseDiaria')"
                                        <?php echo $radio['UnidadeMedida']['c'][1] ?>/>
                                    <label class="btn btn-success <?php echo $radio['UnidadeMedida']['a'][1] ?>" for="UnidadeMedidamG" 
                                        data-mdb-ripple-init name="UnidadeMedida">mg</label>

                                    <input type="radio" class="btn-check" name="UnidadeMedida" id="UnidadeMedidaUI" autocomplete="off" value="UI"
                                        onchange="calculaProduto('DosePosologica', 'idTabSismicrob_Intervalo', 'DoseDiaria')" 
                                        <?php echo $radio['UnidadeMedida']['c'][2] ?>/>
                                    <label class="btn btn-success <?php echo $radio['UnidadeMedida']['a'][2] ?>" for="UnidadeMedidaUI" 
                                        data-mdb-ripple-init name="UnidadeMedida">UI</label>

                                    <?php if ($validation->getError('DosePosologica')): ?>
                                        <div class="invalid-feedback">
                                            <?= $validation->getError('DosePosologica') ?>
                                        </div>
                                    <?php elseif ($validation->getError('UnidadeMedida')): ?>
                                        <div class="invalid-feedback">
                                            <?= $validation->getError('UnidadeMedida') ?>
                                        </div>                                    
                                    <?php endif; ?>   
                                
                            </div>
                        </div>                         
                    </div>
                    <div class="col-4">
                        <div>
                            <label for="idTabSismicrob_Intervalo" class="form-label"><?= $data['mascara']['Intervalo'] ?> <b class="text-danger">*</b></label>
                            <div class="input-group mb-3">
                                <select data-placeholder="Selecione uma opção..." class="form-control select2
                                    <?php if($validation->getError('idTabSismicrob_Intervalo')): ?>is-invalid<?php endif ?>"
                                    onchange="calculaProduto('DosePosologica', 'idTabSismicrob_Intervalo', 'DoseDiaria')"
                                    id="idTabSismicrob_Intervalo" name="idTabSismicrob_Intervalo">

                                    <option value="">Selecione uma opção...</option>
                                    <?php

                                    foreach ($select['Intervalo']->getResultArray() as $row) {
                                        $selected = ($data['idTabSismicrob_Intervalo'] == $row['Intervalo'].'#'.$row['Codigo'].'#'.$row['idTabSismicrob_Intervalo']) ? 'selected' : '';
                                        echo '<option value="'.$row['Intervalo'].'#'.$row['Codigo'].'#'.$row['idTabSismicrob_Intervalo'].'" '.$selected.'>'.$row['Intervalo'].' '.$row['Codigo'].'</option>';
                                    }

                                    ?>
                                </select>
                                <?php if ($validation->getError('idTabSismicrob_Intervalo')): ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('idTabSismicrob_Intervalo') ?>
                                    </div>                                    
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div>
                            <label for="DoseDiaria" class="form-label"><?= $data['mascara']['DoseDiaria'] ?></label>
                            <input type="text" class="form-control" id="DoseDiaria" readonly
                                name="DoseDiaria" value="<?php echo $data['DoseDiaria'] ?>">
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-3">
                        <div>
                            <label for="DoseAtaque" class="form-label"><?= $data['mascara']['DoseAtaque'] ?> <b class="text-danger">*</b></label>
                            <div class="input-group">
                                <div class="btn-group" data-toggle="buttons">
                                    <input type="radio" class="btn-check" name="DoseAtaque" autocomplete="off"
                                        id="DoseAtaqueS" value="S" <?php echo $radio['DoseAtaque']['c'][0] ?>/>
                                    <label class="btn btn-success <?php echo $radio['DoseAtaque']['a'][0] ?>" for="DoseAtaqueS" 
                                        data-mdb-ripple-init>Sim</label>
                                    <input type="radio" class="btn-check" name="DoseAtaque" autocomplete="off" 
                                        id="DoseAtaqueN" value="N" <?php echo $radio['DoseAtaque']['c'][1] ?>/>
                                    <label class="btn btn-success <?php echo $radio['DoseAtaque']['a'][1] ?>" for="DoseAtaqueN" 
                                        data-mdb-ripple-init>Não</label>
                                </div>
                            </div>
                        </div>                         
                    </div>
                    <div class="col-3">
                        <div>
                            <label for="Hemodialise" class="form-label">Hemodiálise <b class="text-danger">*</b></label>
                            <div class="input-group">
                                <div class="btn-group">
                                    <input type="radio" class="btn-check" name="Hemodialise" autocomplete="off" id="HemodialiseS" 
                                        value="S" <?php echo $radio['Hemodialise']['c'][0] ?>/>
                                    <label class="btn btn-success <?php echo $radio['Hemodialise']['a'][0] ?>" for="HemodialiseS"
                                        data-mdb-ripple-init>Sim</label>
                                    <input type="radio" class="btn-check" name="Hemodialise" autocomplete="off" id="HemodialiseN" 
                                        value="N" <?php echo $radio['Hemodialise']['c'][1] ?>/>
                                    <label class="btn btn-success <?php echo $radio['Hemodialise']['a'][1] ?>" for="HemodialiseN"
                                        data-mdb-ripple-init>Não</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div>
                            <label for="idTabSismicrob_ViaAdministracao" class="form-label">Via de Administração <b class="text-danger">*</b></label>
                            <div class="input-group mb-3">
                                <select data-placeholder="Selecione uma opção..." class="form-control select2
                                    <?php if($validation->getError('idTabSismicrob_ViaAdministracao')): ?>is-invalid<?php endif ?>"
                                    id="idTabSismicrob_ViaAdministracao" name="idTabSismicrob_ViaAdministracao">
                                    <option value="">Selecione uma opção...</option>
                                    <?php
                                    foreach ($select['ViaAdministracao']->getResultArray() as $row) {   
                                        $selected = ($data['idTabSismicrob_ViaAdministracao'] == $row['idTabSismicrob_ViaAdministracao']) ? 'selected' : '';
                                        echo '<option value="'.$row['idTabSismicrob_ViaAdministracao'].'" '.$selected.'>'.$row['Codigo'].' '.$row['ViaAdministracao'].'</option>';
                                    }
                                    ?>
                                </select>
                                <?php if ($validation->getError('idTabSismicrob_ViaAdministracao')): ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('idTabSismicrob_ViaAdministracao') ?>
                                    </div>                                    
                                <?php endif; ?>                                
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div>
                            <label for="idTabSismicrob_Especialidade" class="form-label">Especialidade <b class="text-danger">*</b></label>
                            <div class="input-group mb-3">
                                <select data-placeholder="Selecione uma opção..." class="form-control select2
                                    <?php if($validation->getError('idTabSismicrob_Especialidade')): ?>is-invalid<?php endif ?>"
                                    id="idTabSismicrob_Especialidade" name="idTabSismicrob_Especialidade">
                                    <option value="">Selecione uma opção...</option>
                                    <?php
                                    foreach ($select['Especialidade']->getResultArray() as $row) {   
                                        $selected = ($data['idTabSismicrob_Especialidade'] == $row['idTabSismicrob_Especialidade']) ? 'selected' : '';
                                        echo '<option value="'.$row['idTabSismicrob_Especialidade'].'" '.$selected.'>'.$row['Especialidade'].'</option>';                                            
                                    }
                                    ?>
                                </select>
                                <?php if ($validation->getError('idTabSismicrob_Especialidade')): ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('idTabSismicrob_Especialidade') ?>
                                    </div>                                    
                                <?php endif; ?>                                
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-3 idTabSismicrob_Indicacao1" id="idTabSismicrob_Indicacao1" <?php echo $div['idTabSismicrob_Indicacao1'] ?>>
                        <div>
                            <label for="idTabSismicrob_AntibioticoMantido" class="form-label">Antibiótico após cirurgia <b class="text-danger">*</b></label>
                            <div class="input-group mb-3">
                                <select data-placeholder="Selecione uma opção..." class="form-control select2
                                    <?php if($validation->getError('idTabSismicrob_AntibioticoMantido')): ?>is-invalid<?php endif ?>"
                                    id="idTabSismicrob_AntibioticoMantido" name="idTabSismicrob_AntibioticoMantido">
                                    <option value="">Selecione uma opção...</option>
                                    <?php
                                    foreach ($select['AntibioticoMantido']->getResultArray() as $row) {   
                                        $selected = ($data['idTabSismicrob_AntibioticoMantido'] == $row['idTabSismicrob_AntibioticoMantido']) ? 'selected' : '';
                                        echo '<option value="'.$row['idTabSismicrob_AntibioticoMantido'].'" '.$selected.'>'.$row['AntibioticoMantido'].'</option>';                                                                                    
                                    }
                                    ?>
                                </select>
                                <?php if ($validation->getError('idTabSismicrob_AntibioticoMantido')): ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('idTabSismicrob_AntibioticoMantido') ?>
                                    </div>                                    
                                <?php endif; ?>                                
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div>
                            <label for="Peso" class="form-label">Peso <b class="text-danger">*</b></label>
                            <div class="input-group">
                                <input type="text" class="form-control
                                    <?php if($validation->getError('Peso')): ?>is-invalid<?php endif ?>" 
                                    id="Peso" maxlength="18" name="Peso" value="<?php echo $data['Peso'] ?>"
                                    onkeyup="clearanceCreatinina('Peso', 'Creatinina', 'Sexo', 'Idade', 'Clearance')">
                                <span class="input-group-text" id="basic-addon2">kg</span>
                                <?php if ($validation->getError('Peso')): ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('Peso') ?>
                                    </div>                                    
                                <?php endif; ?>
                            </div>
                        </div>                         
                    </div>
                    <div class="col-3">
                        <div>
                            <label for="Creatinina" class="form-label">Creatinina <b class="text-danger">*</b></label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control
                                    <?php if($validation->getError('Creatinina')): ?>is-invalid<?php endif ?>" 
                                    onkeyup="clearanceCreatinina('Peso', 'Creatinina', 'Sexo', 'Idade', 'Clearance')"
                                    id="Creatinina" maxlength="18" name="Creatinina" value="<?php echo $data['Creatinina'] ?>">
                                <span class="input-group-text" id="basic-addon2">mg/dL</span>
                                <?php if ($validation->getError('Creatinina')): ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('Creatinina') ?>
                                    </div>                                    
                                <?php endif; ?>                                
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div>
                            <label for="Clearance" class="form-label">Filtração Glomerular</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" id="Clearance" readonly
                                    name="Clearance" value="<?php echo $data['Clearance'] ?>">
                                <span class="input-group-text" id="basic-addon2">mL/min/1.73m²</span>
                            </div>
                        </div>
                    </div>
                </div>

                <hr />                               
                    
                <div class="row g-3">
                    <div class="col-6">
                        
                        <?= $opt['button'] ?>
                        
                        <!--<button type="submit" class="btn btn-primary" name="submit" value="1">
                            <i class="fas fa-save" aria-hidden="true"></i> Salvar e Finalizar
                        </button>
                        <button type="submit" class="btn btn-info" name="submit2" value="2">
                            <i class="fas fa-plus" aria-hidden="true"></i> Salvar e Incluir Outro Tratamento
                        </button>-->
                    </div>       
                    <div class="col-6 text-end">
                        <a class="btn btn-warning" href="javascript:history.go(-1)"><i class="fa-solid fa-arrow-left"></i> Cancelar</a>
                    </div>
                </div>

            </div>


            <input type="hidden" name="Idade" id="Idade" value="<?= $_SESSION['Paciente']['idade'] ?>" />
            <input type="hidden" name="Sexo" id="Sexo" value="<?= $_SESSION['Paciente']['sexo'] ?>" />
            <input type="hidden" name="action" value="<?= $opt['action'] ?>" />
            <?php if($opt['action'] == 'editar' || $opt['action'] == 'excluir' || $opt['action'] == 'concluir') { ?>
            <input type="hidden" name="idSismicrob_Tratamento" value="<?= $data['idSismicrob_Tratamento'] ?>" />
            <?php } ?>

            
        </div>

    </form>

</main>

<?= $this->endSection() ?>
