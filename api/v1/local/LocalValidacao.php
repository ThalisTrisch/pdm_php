<?php

require_once('./LocalService.php');

function validaDados($nu_cep, $nu_casa, $id_usuario, $nu_cnpj) {
    if (!$nu_casa) {
        throw new Exception("Campo 'nu_casa' não fornecido.");
    }
    if (!$nu_cep) {
        throw new Exception("Campo 'nu_cep' não fornecido.");
    }
    if (!$id_usuario) {
        throw new Exception("Campo 'id_usuario' não fornecido.");
    }
    if (!$nu_cnpj) {
        $nu_cnpj = null;
    }
}
?>
