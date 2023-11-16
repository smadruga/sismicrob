<?= $this->extend('layouts/main_content') ?>
<?= $this->section('subcontent') ?>

<main>

    <form method="post" action="<?= base_url('tabela/'.$opt['form'].'/'.$tabela) ?>">
        <?= csrf_field() ?>
        <?php $validation = \Config\Services::validation(); ?>

        <div class="card">
            <div class="card-header <?= $opt['bg'] ?> text-white">
                <b><?= $opt['title'] ?></b>
            </div>
            <div class="card-body has-validation">

                <?php if ($tab['colspan'] == 6) { ?>
                    <div class="row">
                        <div class="col">
                            <label for="Item" class="form-label"><b>Item</b></label>
                            <div class="input-group mb-3">
                                <input type="text" id="Item" <?= $opt['disabled'] ?> class="form-control <?php if($validation->getError('Item')): ?>is-invalid<?php endif ?>" autofocus name="Item" value="<?php echo $data['Item']; ?>"/>
                                <?= $opt['button'] ?>
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
                            <label for="Codigo" class="form-label"><b>Abreviação</b></label>
                            <div class="input-group mb-3">
                                <input type="text" id="Codigo" <?= $opt['disabled'] ?> class="form-control <?php if($validation->getError('Codigo')): ?>is-invalid<?php endif ?>" autofocus maxlength="10" name="Codigo" value="<?php echo set_value('Codigo'); ?>"/>
                                <?= $opt['button'] ?>
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
                    <button class="btn btn-info" type="submit"><i class="fa-solid fa-plus"></i> Cadastrar</button>
                <?php } else { ?>
                    <label for="Item" class="form-label"><b>Item</b></label>
                    <div class="input-group mb-3">
                        <input type="text" id="Item" <?= $opt['disabled'] ?> class="form-control <?php if($validation->getError('Item')): ?>is-invalid<?php endif ?>" autofocus name="Item" value="<?php echo $data['Item']; ?>"/>
                        <?= $opt['button'] ?>
                        <?php if ($validation->getError('Item')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('Item') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text">
                        Descrição sobre o item.
                    </div>
                <?php } ?>

            </div>
        </div>
        <input type="hidden" name="idTabPreschuap_<?= $tabela ?>" value="<?=$data['idTabPreschuap_'.$tabela] ?>" />
        <?= $opt['manage'] ?>
    </form>

    <div class="text-center">
        <br /><br />
        <a class="btn btn-warning" href="<?= previous_url(-1) ?>"><i class="fa-solid fa-arrow-left"></i> Voltar</a>
    </div>

</main>

<?= $this->endSection() ?>
