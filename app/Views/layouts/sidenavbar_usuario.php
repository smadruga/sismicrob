<a href="<?= base_url('admin/show_user/'.$_SESSION['Usuario']['Usuario']) ?>" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
    <span class="fs-5"><?= $_SESSION['Usuario']['Nome'] ?></span>
</a>
<hr>
<ul class="nav nav-pills flex-column mb-auto">
    <li class="nav-item">
        <a href="<?= base_url('admin/list_perfil/'.$_SESSION['Usuario']['idSishuap_Usuario']) ?>" class="nav-link" aria-current="page">
            <i class="fa-solid fa-chalkboard-user"></i> Perfil
        </a>
    </li>
    <li>
        <?php if ($_SESSION['Usuario']['Inativo'] == 1) { ?>
        <a href="<?= base_url('admin/enable_user/'.$_SESSION['Usuario']['idSishuap_Usuario']) ?>" class="nav-link text-white" aria-current="page">
            <i class="fa-solid fa-user-check"></i> Ativar
        </a>
    <?php } else { ?>
        <a href="<?= base_url('admin/disable_user/'.$_SESSION['Usuario']['idSishuap_Usuario']) ?>" class="nav-link text-white" aria-current="page">
            <i class="fa-solid fa-user-slash"></i> Desativar
        </a>
    <?php } ?>
    </li>
</ul>
