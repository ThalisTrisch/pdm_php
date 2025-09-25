<?php

require_once('./UsuarioService.php');

function validaDados($nu_cpf, $nm_usuario, $vl_email, $nm_sobrenome, $vl_senha) {
    if (!$nu_cpf) {
        throw new Exception("Campo 'nu_cpf' não fornecido.");
    }
    if (!$nm_usuario) {
        throw new Exception("Campo 'nm_usuario' não fornecido.");
    }
    if (!$vl_email) {
        throw new Exception("Campo 'vl_email' não fornecido.");
    }
    if (!$nm_sobrenome) {
        throw new Exception("Campo 'nm_sobrenome' não fornecido.");
    }
    if (!$vl_senha) {
        throw new Exception("Campo 'vl_senha' não fornecido.");
    }else{
        $metodo_criptografia = 'aes-256-cbc';
        $chave = 'sua_chave_secreta_de_32_bytes';
        $iv = '1234567891234567';
        $vl_senha = openssl_encrypt($vl_senha, $metodo_criptografia, $chave, 0, $iv);
        $vl_senha = password_hash($vl_senha, PASSWORD_BCRYPT, $options = ['cost' => 8]);
    }
    if(strlen($nu_cpf) < 11 || strlen($nu_cpf) > 11){
        throw new Exception("Campo 'nu_cpf' inválido. Deve conter 11 caracteres.");
    }
    if(strlen($vl_senha) < 6){
        throw new Exception("Campo 'vl_senha' inválido. Deve conter no mínimo 6 caracteres.");
    }
    if (!filter_var($vl_email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Campo 'vl_email' inválido.");
    }
}
?>
