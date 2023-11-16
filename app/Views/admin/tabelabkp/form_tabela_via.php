<div class="row">
    <div class="col">
        <label for="Item" class="form-label"><b>Item</b> <b class="text-danger">*</b></label>
        <div class="input-group mb-3">
            <input type="text" id="Item" <?= $opt['disabled'] ?> class="form-control <?php if($validation->getError('Item')): ?>is-invalid<?php endif ?>" autofocus name="Item" value="<?php echo $data['Item']; ?>"/>

            <?php if ($validation->getError('Item')): ?>
                <div class="invalid-feedback">
                    <?= $validation->getError('Item') ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="form-text">
            Descrição sobre o item.
        </div>
    </div>
    <div class="col">
        <label for="Codigo" class="form-label"><b>Abreviação</b> <b class="text-danger">*</b></label>
        <div class="input-group mb-3">
            <input type="text" id="Codigo" <?= $opt['disabled'] ?> class="form-control <?php if($validation->getError('Codigo')): ?>is-invalid<?php endif ?>" autofocus maxlength="10" name="Codigo" value="<?php echo $data['Codigo']; ?>"/>

            <?php if ($validation->getError('Codigo')): ?>
                <div class="invalid-feedback">
                    <?= $validation->getError('Codigo') ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="form-text">
            Informe a abreviação do item, com no máximo 10 caracteres.
        </div>
    </div>
</div>
<br />
<?= $opt['button'] ?>
