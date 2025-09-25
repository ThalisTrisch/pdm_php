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
        $sqlCheck = "SELECT COUNT(*) as total FROM tb_usuario WHERE nu_cpf = :nu_cpf OR vl_email = :vl_email";
        $stmtCheck = $this->conexao->prepare($sqlCheck);
        $stmtCheck->execute([':nu_cpf' => $nu_cpf,':vl_email' => $vl_email]);

        $row = $stmtCheck->fetch(PDO::FETCH_ASSOC);
        if ($row['total'] > 0) {
            throw new Exception ("Já existe um usuário com este CPF ou email.");
        }

        $sql = "select id_sequence from tb_sequence order by id_sequence desc limit 1;";
        $consulta = $this->conexao->query($sql);
        $maiorid = $consulta->fetchAll(PDO::FETCH_ASSOC);

        $novoid = $maiorid[0]['id_sequence'] + 1;
        if (!$maiorid){
            throw new Exception("Maior id não localizado");
        }

        $sqlseq ="INSERT INTO tb_sequence (id_sequence, nm_sequence) VALUES (".$novoid.", 'U')";
        $insertseq = $this->conexao->query($sqlseq);
        $responseseq = $insertseq->fetchAll(PDO::FETCH_ASSOC);
        if (!$responseseq){throw new Exception("Não foi possível criar a sequence do usuario");}
        
        $sqluser = "INSERT INTO tb_usuario (id_usuario, nu_cpf, nm_usuario, vl_email, nm_sobrenome, vl_senha) 
        VALUES (:id_usuario, :nu_cpf, :nm_usuario, :vl_email, :nm_sobrenome, :vl_senha)";
        
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

        if (!$responseUsuarioCriado){
            throw new Exception("Não foi possível criar o usuario");
        }
        
        $this->banco->setDados(1, $responseUsuarioCriado);
    }

    public function updateUsuario($dados) {
        $nu_cpf; $nm_usuario; $vl_email; $nm_sobrenome; $vl_senha;$id_usuario;
        // if (isset($dados['nm_usuario'])) {
        //     throw new Exception("fornecido.");
        // }else{
        //     throw new Exception("Campo 'nm_usuario' não fornecido.");
        // }
        if (!isset($dados['id_usuario'])) {
            throw new Exception("Campo id não fornecido.");
        }else{
            $id_usuario = $dados['id_usuario'];
        }

        $sqluser = "SELECT * FROM tb_usuario where id_usuario = ".$dados['id_usuario'];
        $insertuser = $this->conexao->query($sqluser);
        $responseuser = $insertuser->fetchAll(PDO::FETCH_ASSOC);

        if (isset($updateData['nu_cpf'])) {
            $nu_cpf = $responseuser[0]['nu_cpf'];
            if(strlen($nu_cpf) < 11 || strlen($nu_cpf) > 11){
                throw new Exception("Campo 'cpf' inválido. Deve conter 11 caracteres.");
            }
        }else{
            $nu_cpf = $dados['nu_cpf'];
        }
        if (isset($dados['nm_usuario'])) {
            $nm_usuario = $responseuser[0]['nm_usuario'];
        }
        if (isset($dados['vl_email'])) {
            $vl_email = $responseuser[0]['vl_email'];
        }
        if (isset($dados['nm_sobrenome'])) {
            $nm_sobrenome = $responseuser[0]['nm_sobrenome'];
        }
        if (isset($dados['vl_senha'])) {
            $vl_senha = $responseuser[0]['vl_senha'];
            if(strlen($vl_senha) < 6){
                throw new Exception("Campo 'senha' inválido. Deve conter no mínimo 6 caracteres.");
            }
        }else{
            $metodo_criptografia = 'aes-256-cbc';
            $chave = 'sua_chave_secreta_de_32_bytes';
            $iv = '1234567891234567';
            $vl_senha = openssl_encrypt($updateData['vl_senha'], $metodo_criptografia, $chave, 0, $iv);
            $vl_senha = password_hash($vl_senha, PASSWORD_BCRYPT, $options = ['cost' => 8]);
        }
        if (!filter_var($vl_email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Campo 'email' inválido.");
        }
        $sqluser = "update from tb_usuario set nu_cpf = :nu_cpf  nm_usuario = :nm_usuario vl_email = :vl_email 
        nm_sobrenome = :nm_sobrenome, vl_senha = :vl_senha where id_usuario = :id_usuario";
        
        $updateuser = $this->conexao->prepare($sqluser);
        var_dump($nu_cpf);
        $updateuser->bindValue(':id_usuario', $id_usuario , PDO::PARAM_INT);
        $updateuser->bindValue(':nu_cpf', $nu_cpf, PDO::PARAM_STR);
        $updateuser->bindValue(':nm_usuario', $nm_usuario, PDO::PARAM_STR);
        $updateuser->bindValue(':vl_email', $vl_email, PDO::PARAM_STR);
        $updateuser->bindValue(':nm_sobrenome', $nm_sobrenome, PDO::PARAM_STR);
        $updateuser->bindValue(':vl_senha', $vl_senha, PDO::PARAM_STR);
        $updateuser->execute();

        if (!$updateuser){throw new Exception("Não foi possível atualizar informaçoes");}

        return $updateuser;
    }

    public function deleteUsuario($dados) {

        $sqluser ="DELETE FROM tb_usuario WHERE id_usuario = ".$dados["id_usuario"];
        $insertuser = $this->conexao->query($sqluser);
        $responseuser = $insertuser->fetchAll(PDO::FETCH_ASSOC);
        if (!$responseuser){throw new Exception("Não foi possível deletar usuário");}

        $this->banco->setMensagem(1, "Deletado com sucesso");
        return $responseuser;
    }

    public function loginUsuario($loginData) {
        if (!isset($loginData['vl_email']) || !isset($loginData['vl_senha'])) {
            throw new Exception("Email ou senha não fornecidos.");
        }

        $metodo_criptografia = 'aes-256-cbc';
        $chave = 'sua_chave_secreta_de_32_bytes';
        $iv = '1234567891234567';
        $vl_senha = openssl_encrypt($loginData['vl_senha'], $metodo_criptografia, $chave, 0, $iv);
        $vl_email = $loginData['vl_email'];

        $sql = "SELECT id_usuario, nm_usuario, vl_email, vl_senha FROM tb_usuario WHERE vl_email = :email";

        $consulta = $this->conexao->prepare($sql);
        $consulta->bindValue(':email', $vl_email, PDO::PARAM_STR);
        $consulta->execute();

        $usuario = $consulta->fetch(PDO::FETCH_ASSOC);

        if (password_verify($vl_senha, $usuario['vl_senha'])) {
            $this->banco->setDados(1, $usuario);
            return($usuario);
        } else {
            throw new Exception("Email ou senha inválidos.");
        }

        
    }

    // Exemplo de rotas na url:
    // http://localhost/pdm/api/v1/usuario/UsuarioController.php/?operacao=getUsuarios
    // http://localhost/pdm/api/v1/usuario/UsuarioController.php/?operacao=getUsuario&id_usuario=2
    // http://localhost/pdm/api/v1/usuario/UsuarioController.php/?operacao=createUsuario
    // http://localhost/pdm/api/v1/usuario/UsuarioController.php/?operacao=updateUsuario
    // http://localhost/pdm/api/v1/usuario/UsuarioController.php/?operacao=deleteUsuario&id_usuario=2
}
