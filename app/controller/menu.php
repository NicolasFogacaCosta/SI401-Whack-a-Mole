<?php
    // Controller do menu principal: página restrita, só exige a sessão ativa
    require_once __DIR__ . '/../config/sessao.php';
    exigirLogin();

    require_once __DIR__ . '/../view/menu.php';
?>
