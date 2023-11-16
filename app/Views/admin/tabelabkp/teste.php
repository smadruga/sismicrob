<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Hello, Bootstrap Table!</title>

    <!-- Styles and scripts -->
    <link href="<?= base_url('/assets/bootstrap-table-1.19.1/dist/bootstrap-table.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('/assets/select2/dist/css/select2.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('/assets/select2-bootstrap-5-theme-master/dist/select2-bootstrap-5-theme.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('/assets/css/simple-datatables@latest-style.css') ?>" rel="stylesheet">
    <link href="<?= base_url('/assets/css/bootswatch-flatly-bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('/assets/fontawesome-free-6.0.0-web/css/all.min.css') ?>" rel="stylesheet">

    <!-- Favicons -->
    <link href="<?= base_url('/favicon.ico') ?>" rel="shortcut icon" type="image/png"/>
    <link href="<?= base_url('/assets/img/caduceus/caduceus-128.png') ?>" sizes="180x180" rel="apple-touch-icon">
    <link href="<?= base_url('/assets/img/caduceus/caduceus-32.png') ?>" sizes="32x32" type="image/png" rel="icon">
    <link href="<?= base_url('/assets/img/caduceus/caduceus-16.png') ?>" sizes="16x16" type="image/png" rel="icon">
    <link href="<?= base_url('/favicon.ico') ?>" rel="icon">
</head>
<body>
    <table

    data-toggle="table"
    data-locale="pt-BR"
    data-id-field="Id"
    data-sortable="true"
    data-search="true"
    data-show-fullscreen="true"
    data-search-highlight="true"
    data-show-pagination-switch="true"
    data-pagination="true"
    data-show-button-icons="true"
    data-show-button-text="true"

    >
        <thead>
            <tr>
                <th>Item ID</th>
                <th>Item Name</th>
                <th>Item Price</th>
            </tr>
        </thead>
        <tbody>
            <?php
            for($i=0;$i<100;$i++) {
            ?>
            <tr>
                <td><?= $i ?></td>
                <td>Item <?= $i ?></td>
                <td>$<?= $i ?></td>
            </tr>
            <?php
            }
            ?>
        </tbody>
    </table>

    <script src="<?= base_url('/assets/js/jquery-3.6.0.min.js') ?>" crossorigin="anonymous"></script>


    <script src="<?= base_url('/assets/select2/dist/js/select2.min.js') ?>" crossorigin="anonymous"></script>
    <script src="<?= base_url('/assets/select2/dist/js/i18n/pt-BR.js') ?>" crossorigin="anonymous"></script>
    <script src="<?= base_url('/assets/bootstrap/dist/js/bootstrap.bundle.min.js') ?>" crossorigin="anonymous"></script>
    
    <script src="<?= base_url('/assets/bootstrap-table-1.19.1/dist/bootstrap-table.min.js') ?>" crossorigin="anonymous"></script>
        <script src="<?= base_url('/assets/bootstrap-table-1.19.1/dist/locale/bootstrap-table-pt-BR.min.js') ?>" crossorigin="anonymous"></script>

    <script src="<?= base_url('/assets/jquery.countdown-2.2.0/jquery.countdown.min.js') ?>" crossorigin="anonymous"></script>



    <script src="<?= base_url('/assets/js/HUAP_ready_jquery.js') ?>" crossorigin="anonymous"></script>
    <script src="<?= base_url('/assets/js/HUAP_jquery.js') ?>" crossorigin="anonymous"></script>

</body>
</html>
