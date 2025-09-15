<?php

require_once('./../database/Base.php');

class Routes extends Base {

    public function getUsuarios($banco) {
        $sql = "SELECT * FROM tb_usuario";

        $consulta = $this->conexao->query($sql);
        $ret = $consulta->fetch(PDO::FETCH_ASSOC);

        $banco->setDados(0, $ret);

        if (!$ret) 
        {
            throw new Exception("Usuario nao Localizado");
        }
        var_dump($ret);
    }
}
