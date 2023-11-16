<?= $this->extend('layouts/main_admin') ?>
<?= $this->section('content') ?>
<?= $this->include('layouts/uppernavbar') ?>
<br />

<main class="<?= $_SESSION['config']['class'] ?>">
    <div class="row">

        <div class="col">

            <?= $this->include('layouts/div_flashdata') ?>

            <div class="">
                <div class="row">

                    <?= $this->renderSection('subcontent') ?>

                </div>
            </div>
        </div>


    </div>
</main>

<?= $this->endSection() ?>
