<!DOCTYPE html>
<html>
    <head>
        <title><?= HUAP_APPNAME ?></title>
        <meta charset="UTF-8">
        <meta name="description" content="PRESCHUAP WEB - Prescrição médica eletrônica de média e alta complexidade.">
        <meta name="author" content="Rodrigo Campos - rodrigopc@id.uff.br" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="theme-color" content="#7952b3">
        <meta http-equiv="refresh" content="<?= env('huap.session.expires')+1 ?>">

        <!-- Styles and scripts -->
        <link href="<?= base_url('/assets/select2/dist/css/select2.min.css') ?>" rel="stylesheet">
        <link href="<?= base_url('/assets/select2-bootstrap-5-theme-master/dist/select2-bootstrap-5-theme.min.css') ?>" rel="stylesheet">
        <link href="<?= base_url('/assets/css/simple-datatables@latest-style.css') ?>" rel="stylesheet">
        <link href="<?= base_url('/assets/css/bootswatch-flatly-bootstrap.min.css') ?>" rel="stylesheet">
        <link href="<?= base_url('/assets/bootstrap-table-1.19.1/dist/bootstrap-table.min.css') ?>" rel="stylesheet">
        <link href="<?= base_url('/assets/fontawesome/css/all.min.css') ?>" rel="stylesheet">

        <!-- Favicons -->
        <link href="<?= base_url('/favicon.ico') ?>" rel="shortcut icon" type="image/png"/>
        <link href="<?= base_url('/assets/img/caduceus/caduceus-128.png') ?>" sizes="180x180" rel="apple-touch-icon">
        <link href="<?= base_url('/assets/img/caduceus/caduceus-32.png') ?>" sizes="32x32" type="image/png" rel="icon">
        <link href="<?= base_url('/assets/img/caduceus/caduceus-16.png') ?>" sizes="16x16" type="image/png" rel="icon">
        <link href="<?= base_url('/favicon.ico') ?>" rel="icon">


    </head>
    <body style="padding-top: 35px;">

        <?= $this->renderSection('content') ?>

        <script src="<?= base_url('/assets/js/jquery.min.js') ?>" crossorigin="anonymous"></script>
        <script src="<?= base_url('/assets/select2/dist/js/select2.min.js') ?>" crossorigin="anonymous"></script>
        <script src="<?= base_url('/assets/select2/dist/js/i18n/pt-BR.js') ?>" crossorigin="anonymous"></script>
        <script src="<?= base_url('/assets/bootstrap/dist/js/bootstrap.bundle.min.js') ?>" crossorigin="anonymous"></script>
        <script src="<?= base_url('/assets/bootstrap-table-1.19.1/dist/bootstrap-table.min.js') ?>" crossorigin="anonymous"></script>
        <script src="<?= base_url('/assets/bootstrap-table-1.19.1/dist/locale/bootstrap-table-pt-BR.min.js') ?>" crossorigin="anonymous"></script>
        <script src="<?= base_url('/assets/jquery.countdown-2.2.0/jquery.countdown.min.js') ?>" crossorigin="anonymous"></script>
        <script src="<?= base_url('/assets/js/jquery.maskedinput.min.js') ?>" crossorigin="anonymous"></script>
        <script src="<?= base_url('/assets/js/jquery.maskMoney.min.js') ?>" crossorigin="anonymous"></script>
        <script src="<?= base_url('/assets/js/HUAP_ready_jquery.js') ?>" crossorigin="anonymous"></script>
        <script src="<?= base_url('/assets/js/HUAP_jquery.js') ?>" crossorigin="anonymous"></script>


    </body>
</html>
