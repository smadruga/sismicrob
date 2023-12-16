<div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-primary rounded" style="width: 280px;">
    <a href="<?= base_url('paciente/show_paciente/'.$_SESSION['Paciente']['codigo']) ?>" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        <span class="fs-5"><?= $_SESSION['Paciente']['nome'] ?></span>
    </a>
    <span class="fs-6">Prontuário: <?= $_SESSION['Paciente']['prontuario'] ?></span>
    <hr>
    <ul class="nav navbar-nav flex-column mb-auto">
        <li class="nav-item">
            <a href="<?= base_url('paciente/show_paciente/'.$_SESSION['Paciente']['codigo']) ?>" class="nav-link text-white p-2" aria-current="page">
                <i class="fa-solid fa-hospital-user"></i> Paciente
            </a>
        </li>
        <li class="nav-item">
            <a href="<?= base_url('prescricao/manage_prescricao/cadastrar') ?>" class="nav-link text-white p-2" aria-current="page">
                <i class="fa-solid fa-add"></i> Nova Prescrição
            </a>
        </li>
        <li>
            <a href="<?= base_url('prescricao/list_prescricao/') ?>" class="nav-link text-white p-2" aria-current="page">
                <i class="fa-solid fa-list"></i> Histórico de Prescrições
            </a>
        </li>
        <li>
            <a href="<?= base_url('prescricao/list_atendimento/') ?>" class="nav-link text-white p-2" aria-current="page">
                <i class="fa-solid fa-list"></i> Histórico de Atendimentos
            </a>
        </li>        
    </ul>
</div>
