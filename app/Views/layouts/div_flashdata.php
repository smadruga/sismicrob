<?php if(session()->getFlashdata('success')) { ?>
    <div class="alert alert-success alert-dismissible" id="flashdata">
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        <?php echo session()->getFlashdata('success') ?>
    </div>
<?php } elseif(session()->getFlashdata('failed')) { ?>
    <div class="alert alert-danger alert-dismissible" id="flashdata">
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        <?php echo session()->getFlashdata('failed') ?>
    </div>
<?php } elseif(session()->getFlashdata('nochange')) { ?>
    <div class="alert alert-warning alert-dismissible" id="flashdata">
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        <?php echo session()->getFlashdata('nochange') ?>
    </div>
<?php } ?>
