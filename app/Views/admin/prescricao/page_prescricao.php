<?= $this->extend('layouts/main_content') ?>
<?= $this->section('subcontent') ?>
<?= $this->include('layouts/sidenavbar_paciente') ?>

<div class="col border rounded ms-2 p-4">

    <div class="card">
        <div class="card-header">
            <b>Escolha uma opção</b>
        </div>
        <div class="card-body has-validation">

            <div class="container">
                <div class="row">
                    <div class="col-4">
                        <a class="btn btn-info" href="<?= base_url('prescricao/manage_prescricao/cadastrar') ?>" role="button"><i class="fa-solid fa-circle-plus"></i> Nova Prescrição</a>
                    </div>
                    <div class="col">
                        Cria uma nova prescição médica, iniciando um novo tratamento.
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-4">
                        <a class="btn btn-info" href="<?= base_url('prescricao/copy_prescricao') ?>" role="button"><i class="fa-solid fa-copy"></i> Copiar última Prescrição</a>
                    </div>
                    <div class="col">
                        Copia a maioria dos dados da última prescrição concluída do paciente.
                    </div>
                </div>
                <hr>
                <!--<div class="row">
                    <div class="col-4">
                        <a class="btn btn-info" href="<?= base_url('prescricao/copy_prescricao/0/1') ?>" role="button"><i class="fa-solid fa-repeat"></i> Continuar último Tratamento</a>
                    </div>
                    <div class="col">
                        Continua o tratamento do paciente, com base na última prescrição concluída, copiando alguns dados e atualizando os demais campos.
                    </div>
                </div>-->
            </div>

        </div>
    </div>

</div>


<?= $this->endSection() ?>
