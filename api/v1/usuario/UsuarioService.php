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

    public function getUsuario() {
        $sql = "SELECT * FROM tb_usuario where id_usuario = ".$_GET['id_usuario'];

        $consulta = $this->conexao->query($sql);
        $ret = $consulta->fetchAll(PDO::FETCH_ASSOC);

        $this->banco->setDados(0, $ret);

        if (!$ret) 
        {
            throw new Exception("Usuario nao Localizado");
        }
        return $ret;
    }

    public function createUsuario($nu_cpf, $nm_usuario, $vl_email, $nm_sobrenome, $vl_senha) {

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
        }

        $sqlCheck = "SELECT COUNT(*) as total FROM tb_usuario WHERE nu_cpf = :nu_cpf OR vl_email = :vl_email";
        $stmtCheck = $this->conexao->prepare($sqlCheck);
        $stmtCheck->execute([
            ':nu_cpf' => $nu_cpf,
            ':vl_email' => $vl_email
        ]);
        $row = $stmtCheck->fetch(PDO::FETCH_ASSOC);
        if ($row['total'] > 0) {
            throw new Exception("Já existe um usuário com este CPF ou email.");
        }

        $sql = "select id_sequence from tb_sequence order by id_sequence desc limit 1;";
        $consulta = $this->conexao->query($sql);
        $maiorid = $consulta->fetchAll(PDO::FETCH_ASSOC);

        $novoid = $maiorid[0]['id_sequence'] + 1;
        if (!$maiorid){throw new Exception("Maior id não localizado");}

        $sqlseq ="INSERT INTO tb_sequence (id_sequence, nm_sequence) VALUES (".$novoid.", 'U')";
        $insertseq = $this->conexao->query($sqlseq);
        $responseseq = $insertseq->fetchAll(PDO::FETCH_ASSOC);
        if (!$responseseq){throw new Exception("Não foi possível criar a sequence do usuario");}
        
        $sqluser = "
        INSERT INTO tb_usuario 
        (id_usuario, nu_cpf, nm_usuario, vl_email, nm_sobrenome, vl_senha) 
        VALUES 
        (:id_usuario, :nu_cpf, :nm_usuario, :vl_email, :nm_sobrenome, :vl_senha)
        ";
        
        $insertuser = $this->conexao->prepare($sqluser);
        
        $insertuser->bindValue(':id_usuario', $novoid, PDO::PARAM_INT);
        $insertuser->bindValue(':nu_cpf', $nu_cpf, PDO::PARAM_STR);
        $insertuser->bindValue(':nm_usuario', $nm_usuario, PDO::PARAM_STR);
        $insertuser->bindValue(':vl_email', $vl_email, PDO::PARAM_STR);
        $insertuser->bindValue(':nm_sobrenome', $nm_sobrenome, PDO::PARAM_STR);
        $insertuser->bindValue(':vl_senha', $vl_senha, PDO::PARAM_STR);
        
        $insertuser->execute();

        $sqlUsuarioCriado = "select * from tb_usuario where id_usuario = " . $novoid;
        $getUsuario = $this->conexao->query($sqlUsuarioCriado);
        $responseUsuarioCriado = $getUsuario->fetchAll(PDO::FETCH_ASSOC);

        if (!$responseUsuarioCriado){throw new Exception("Não foi possível criar o usuario");}
        
        $this->banco->setDados(1, $responseUsuarioCriado);
    }

    public function updateUsuario() {

        $sqluser = "UPDATE tb_usuario SET nm_usuario = '".$_POST['nm_usuario']."' vl_email = '".$_POST['vl_email']."' nm_sobrenome = '".$_POST['nm_sobrenome']."' vl_senha = '".$_POST['vl_senha']."' WHERE id_usuario = ".$_POST['id_usuario'];
        $insertuser = $this->conexao->query($sqluser);
        $responseuser = $insertuser->fetchAll(PDO::FETCH_ASSOC);
        if (!$responseuser){throw new Exception("Não foi possível criar a sequence do usuario");}

        return $responseuser;
    }

    function deleteUsuario($dados) {

        $sqluser ="DELETE FROM tb_usuario WHERE id_usuario = ".$dados["id_usuario"];
        $insertuser = $this->conexao->query($sqluser);
        $responseuser = $insertuser->fetchAll(PDO::FETCH_ASSOC);
        if (!$responseuser){throw new Exception("Não foi possível deletar usuário");}

        $this->banco->setMensagem(1, "Deletado com sucesso");
        return $responseuser;
    }

    public function login($email, $senha) {

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
