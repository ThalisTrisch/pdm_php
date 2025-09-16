<?php

require_once('../../database/InstanciaBanco.php');

class UsuarioService extends InstanciaBanco {
    public function getUsuarios() {
        $sql = "SELECT * FROM tb_usuario";

        $consulta = $this->conexao->query($sql);
        $retorno = $consulta->fetchAll(PDO::FETCH_ASSOC);

        $this->banco->setDados(count($retorno), $retorno);

        if (!$retorno) 
        {
            throw new Exception("Usuario nao Localizado");
        }
        return $retorno;
    }
}
