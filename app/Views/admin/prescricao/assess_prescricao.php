<hr />

<div class="row">
    <div class="col-3">
        <div>
            <label for="Avaliacao" class="form-label">Avaliação <b class="text-danger">*</b></label>
            <div class="input-group">
                <div class="btn-group">
                    <input type="radio" class="btn-check" name="Avaliacao" autocomplete="off" id="AvaliacaoS" 
                        value="S" <?php echo $radio['Avaliacao']['c'][0] ?> />
                    <label class="btn btn-success <?php echo $radio['Avaliacao']['a'][0] ?>" for="AvaliacaoS"
                        data-mdb-ripple-init>Aprovado<?= $idSismicrob_Tratamento ?></label>
                    <input type="radio" class="btn-check" name="Avaliacao" autocomplete="off" id="AvaliacaoN" 
                        value="N" <?php echo $radio['Avaliacao']['c'][1] ?> />
                    <label class="btn btn-success <?php echo $radio['Avaliacao']['a'][1] ?>" for="AvaliacaoN"
                        data-mdb-ripple-init>Reprovado</label>
                </div>
            </div>
        </div>
    </div>
</div>

<br />
