<hr />

<div class="row g-3">
    <div class="col-12">
        <div>
            <label for="Avaliacao" class="form-label">Avaliação <b class="text-danger">*</b></label>
            <div class="input-group">
                <div class="btn-group">
                    <input type="radio" class="btn-check" name="Avaliacao" autocomplete="off" id="AvaliacaoS" 
                        value="S" <?php echo $radio['Avaliacao']['c'][0] ?> />
                    <label class="btn btn-<?php echo $radio['Avaliacao']['b'][0] ?> <?php echo $radio['Avaliacao']['a'][0] ?>" for="AvaliacaoS"
                        data-mdb-ripple-init>Sim</label>
                    <input type="radio" class="btn-check" name="Avaliacao" autocomplete="off" id="AvaliacaoN" 
                        value="N" <?php echo $radio['Avaliacao']['c'][1] ?> />
                    <label class="btn btn-<?php echo $radio['Avaliacao']['b'][1] ?> <?php echo $radio['Avaliacao']['a'][1] ?>" for="AvaliacaoN"
                        data-mdb-ripple-init>Não</label>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div>
            <label for="AvaliacaoDoseObs" class="form-label">Dose:</label>
            <div class="input-group">
                <input type="text" class="form-control" 
                    id="AvaliacaoDoseObs" maxlength="18" name="AvaliacaoDoseObs" value="<?php echo $data['AvaliacaoDoseObs'] ?>" 
                    placeholder="Justificativa">
            </div>
        </div>
    </div>    
    <div class="col-6">
        <div>
            <label for="AvaliacaoDuracaoObs" class="form-label">Duração:</label>
            <div class="input-group">
                <input type="text" class="form-control" 
                    id="AvaliacaoDuracaoObs" maxlength="18" name="AvaliacaoDuracaoObs" value="<?php echo $data['AvaliacaoDuracaoObs'] ?>" 
                    placeholder="Justificativa">
            </div>
        </div>
    </div>
    <div class="col-6">
        <div>
            <label for="AvaliacaoIntervaloObs" class="form-label">Intervalo:</label>
            <div class="input-group">
                <input type="text" class="form-control" 
                    id="AvaliacaoIntervaloObs" maxlength="18" name="AvaliacaoIntervaloObs" value="<?php echo $data['AvaliacaoIntervaloObs'] ?>" 
                    placeholder="Justificativa">
            </div>
        </div>
    </div>    
    <div class="col-6">
        <div>
            <label for="AvaliacaoIndicacaoObs" class="form-label">Indicação:</label>
            <div class="input-group">
                <input type="text" class="form-control" 
                    id="AvaliacaoIndicacaoObs" maxlength="18" name="AvaliacaoIndicacaoObs" value="<?php echo $data['AvaliacaoIndicacaoObs'] ?>" 
                    placeholder="Justificativa">
            </div>
        </div>
    </div>    
    <div class="col-6">
        <div>
            <label for="AvaliacaoPreenchimentoInadequadoObs" class="form-label">Preenchimento Inadequado:</label>
            <div class="input-group">
                <input type="text" class="form-control" 
                    id="AvaliacaoPreenchimentoInadequadoObs" maxlength="18" name="AvaliacaoPreenchimentoInadequadoObs" value="<?php echo $data['AvaliacaoPreenchimentoInadequadoObs'] ?>" 
                    placeholder="Justificativa">
            </div>
        </div>
    </div>    
    <div class="col-12">
        <div>
            <label for="AvaliacaoOutrosObs" class="form-label">Observações:</label>
            <div class="input-group">
                <textarea class="form-control" id="AvaliacaoOutrosObs" maxlength="65000" name="AvaliacaoOutrosObs" ><?php echo $data['AvaliacaoOutrosObs'] ?></textarea>
            </div>
        </div>
    </div>    

</div>

<br />
