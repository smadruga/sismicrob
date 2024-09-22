<div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-primary rounded" style="width: 280px;">
    <a href="<?= base_url('paciente/show_paciente/'.$_SESSION['Paciente']['codigo']) ?>" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        <span class="fs-5"><?= $_SESSION['Paciente']['nome'] ?></span>
    </a>
    <span class="fs-6">Prontuário: <?= $_SESSION['Paciente']['prontuario'] ?></span>
    <hr>
    <span class="fs-6">Internação: <?= $_SESSION['Paciente']['internacao']['dthr_internacao'] ?></span>
    <span class="fs-6"><?= $_SESSION['Paciente']['internacao']['lto_lto_id'].'  '.$_SESSION['Paciente']['internacao']['sigla'] ?></span>
    <hr>
    <ul class="nav navbar-nav flex-column mb-auto">
        <li class="nav-item">
            <a href="<?= base_url('paciente/show_paciente/'.$_SESSION['Paciente']['codigo']) ?>" class="nav-link text-white p-2" aria-current="page">
                <i class="fa-solid fa-hospital-user"></i> Paciente
            </a>
        </li>
        <?php if (!empty(array_intersect(array_keys($_SESSION['Sessao']['Perfil']), [1,3]))) { ?>
        <li class="nav-item">
            <a href="<?= base_url('prescricao/manage_prescricao/cadastrar') ?>" class="nav-link text-white p-2" aria-current="page">
                <i class="fa-solid fa-add"></i> Nova Prescrição
            </a>
        </li>
        <?php } ?>
        <li>
            <a href="<?= base_url('prescricao/list_prescricao/') ?>" class="nav-link text-white p-2" aria-current="page">
                <i class="fa-solid fa-pills"></i> Histórico de Prescrições
            </a>
        </li>
        <li>
            <a href="<?= base_url('prescricao/list_atendimento/') ?>" class="nav-link text-white p-2" aria-current="page">
                <i class="fa-solid fa-hospital"></i> Histórico de Atendimentos
            </a>
        </li>        
        <li>
            <a href="<?= base_url('prescricao/list_cultura/') ?>" class="nav-link text-white p-2" aria-current="page">
                <i class="fa-solid fa-bacterium"></i> Resultado de Cultura
            </a>
        </li>
    </ul>
</div>
