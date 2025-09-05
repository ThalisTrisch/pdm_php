<?php

require_once('../../database/Base.php');

class UsuarioService extends Base {
  
    function __construct($p_banco) {
        parent::__construct($p_banco);
    }

    public function getUsuarios() {
        $sql = "SELECT * FROM tb_usuario";

        $consulta = $this->conexao->query($sql);
        $ret = $consulta->fetch(PDO::FETCH_ASSOC);
        if (!$ret) 
        {
            throw new Exception("Usuario nao Localizado");
        }
        return $ret;
    }
}
