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

    public function getUsuario($banco) {
        $sql = "SELECT * FROM tb_usuario where id_usuario = ".$_GET['id_usuario'];

        $consulta = $this->conexao->query($sql);
        $ret = $consulta->fetchAll(PDO::FETCH_ASSOC);

        $banco->setDados(0, $ret);

        if (!$ret) 
        {
            throw new Exception("Usuario nao Localizado");
        }
        return $ret;
    }

    public function createUsuario($banco) {

        $sql = "select id_sequence from tb_sequence order by id_sequence desc limit 1;";
        $consulta = $this->conexao->query($sql);
        $maiorid = $consulta->fetchAll(PDO::FETCH_ASSOC);
        var_dump($maiorid);
        $novoid = $maiorid[0]['id_sequence'] + 1;
        if (!$maiorid){throw new Exception("Maior id não localizado");}

        $sqlseq ="INSERT INTO tb_sequence (id_sequence, nm_sequence) VALUES (".$novoid.", 'U')";
        $insertseq = $this->conexao->query($sqlseq);
        $responseseq = $insertseq->fetchAll(PDO::FETCH_ASSOC);
        if (!$responseseq){throw new Exception("Não foi possível criar a sequence do usuario");}
        
        $sqluser ="INSERT INTO tb_usuario (id_usuario, nu_cpf, nm_usuario, vl_email, nm_sobrenome, vl_senha) VALUES (".$novoid.",'".$_POST['nu_cpf']."', '".$_POST['nm_usuario']."', '".$_POST['vl_email']."', '".$_POST['nm_sobrenome']."', '".$_POST['vl_senha']."')";
        $insertuser = $this->conexao->query($sqluser);
        $responseuser = $insertuser->fetchAll(PDO::FETCH_ASSOC);
        if (!$responseuser){throw new Exception("Não foi possível criar a sequence do usuario");}

        return $responseuser;
    }

    public function updateUsuario($banco) {

        $sqluser = "UPDATE tb_usuario SET nm_usuario = '".$_POST['nm_usuario']."' vl_email = '".$_POST['vl_email']."' nm_sobrenome = '".$_POST['nm_sobrenome']."' vl_senha = '".$_POST['vl_senha']."' WHERE id_usuario = ".$_POST['id_usuario'];
        $insertuser = $this->conexao->query($sqluser);
        $responseuser = $insertuser->fetchAll(PDO::FETCH_ASSOC);
        if (!$responseuser){throw new Exception("Não foi possível criar a sequence do usuario");}

        return $responseuser;
    }

    function deleteUsuario($banco) {
        $sqluser ="DELETE FROM tb_usuario WHERE id_usuario = ".$_POST['id_usuario'];
        $insertuser = $this->conexao->query($sqluser);
        $responseuser = $insertuser->fetchAll(PDO::FETCH_ASSOC);
        if (!$responseuser){throw new Exception("Não foi possível criar a sequence do usuario");}

        $sqlseq ="DELETE FROM tb_sequence WHERE id_sequence = ".$_POST['id_usuario'];
        $insertseq = $this->conexao->query($sqlseq);
        $responseseq = $insertseq->fetchAll(PDO::FETCH_ASSOC);
        if (!$responseseq){throw new Exception("Não foi possível criar a sequence do usuario");}

        return $responseuser;
    }

    public function login($dados) {
        // CORREÇÃO FINAL: A query agora usa os nomes de coluna corretos da sua nova tabela
        // e usa aliases (as id, as nome, as email) para devolver o JSON no formato
        // que o Flutter espera, sem precisar de alterar o código Dart.
        $email = $dados['email'];
        $senha = $dados['senha'];

        if (!isset($email) || !isset($senha)) {
            throw new Exception("Email ou senha não fornecidos.");
        }

        $sql = "SELECT 
                    id_usuario as id, 
                    nm_usuario as nome, 
                    vl_email as email 
                FROM tb_usuario 
                WHERE vl_email = :email AND vl_senha = :senha";

        $consulta = $this->conexao->prepare($sql);
        $consulta->bindValue(':email', $email, PDO::PARAM_STR);
        $consulta->bindValue(':senha', $senha, PDO::PARAM_STR);
        $consulta->execute();

        $usuario = $consulta->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            $this->banco->setDados(1, $usuario);
        } else {
            throw new Exception("Email ou senha inválidos.");
        }

        return $usuario;
    }

    // Exemplo de rotas na url:
    // http://localhost/pdm/api/v1/usuario/UsuarioController.php/?operacao=getUsuarios
    // http://localhost/pdm/api/v1/usuario/UsuarioController.php/?operacao=getUsuario&id_usuario=2
    // http://localhost/pdm/api/v1/usuario/UsuarioController.php/?operacao=newUsuario
    // http://localhost/pdm/api/v1/usuario/UsuarioController.php/?operacao=deleteUsuario&id_usuario=2
}
