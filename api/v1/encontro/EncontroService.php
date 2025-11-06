<?php
require_once('../../database/InstanciaBanco.php');

class EncontroService extends InstanciaBanco {
    public function addUsuarioEncontro($id_usuario, $id_encontro) {
        
        $sql = "INSERT INTO tb_encontro_usuario_dn (id_usuario,id_encontro) VALUES (:id_usuario,:id_encontro);";
        
        $insertEncontro = $this->conexao->prepare($sql);
        $insertEncontro->bindValue(':id_encontro', $id_encontro, PDO::PARAM_INT);
        $insertEncontro->bindValue(':id_usuario', $id_usuario, PDO::PARAM_STR);

        $insertEncontro->execute();

        $SqlEncontroCriada = "select * from tb_encontro_usuario_dn where id_encontro = " . $id_encontro . " and id_usuario = " . $id_usuario;
        $getEncontro = $this->conexao->query($SqlEncontroCriada);
        $responseEncontroCriada = $getEncontro->fetchAll(PDO::FETCH_ASSOC);

        if (!$responseEncontroCriada){
            throw new Exception("Não foi possível criar o usuario");
        }
        
        $this->banco->setDados(1, $responseEncontroCriada);
    }

    public function getEncontros() {
        $sql = "SELECT * from tb_encontro_dn";

        $consulta = $this->conexao->query($sql);
        $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $this->banco->setDados(count($resultados), $resultados);

        if (!$resultados) {
            $this->banco->setDados(0, []);
        }
        
        return $resultados;
    }

    public function getEncontro() {
         
        $sql = "SELECT * from tb_encontro_dn where id_encontro = ".$_GET['id_encontro'];

        $consulta = $this->conexao->query($sql);
        $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $this->banco->setDados(count($resultados), $resultados);

        if (!$resultados) {
            $this->banco->setDados(0, []);
        }
        
        return $resultados;
    }

    public function getUsuarioEncontros(){
        $sql = "SELECT * from tb_encontro_dn where id_encontro = ".$_GET['id_usuario'];

        $consulta = $this->conexao->query($sql);
        $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $this->banco->setDados(count($resultados), $resultados);

        if (!$resultados) {
            $this->banco->setDados(0, []);
        }
        
        return $resultados;
    }

    public function createEncontro($id_local, $hr_encontro, $nu_max_convidados, $id_cardapio) {
        $sql = "select id_sequence from tb_sequence_dn order by id_sequence desc limit 1;";
        $consulta = $this->conexao->query($sql);
        $maiorid = $consulta->fetchAll(PDO::FETCH_ASSOC);

        if (!$maiorid){throw new Exception("Maior id não localizado");}
        
        $novoid = $maiorid[0]['id_sequence'] + 1;
        $sqlseq ="INSERT INTO tb_sequence_dn (id_sequence, nm_sequence) VALUES (".$novoid.", 'E')";
        $insertseq = $this->conexao->query($sqlseq);
        $responseseq = $insertseq->fetchAll(PDO::FETCH_ASSOC);
        if (!$responseseq){throw new Exception("Não foi possível criar a sequence do usuario");}
        $sql = "INSERT INTO tb_encontro_dn (id_encontro, id_local, id_cardapio, hr_encontro, nu_max_convidados, fl_anfitriao_confirma) VALUES (:id_encontro,:id_local, :id_cardapio,:hr_encontro,:nu_max_convidados, 'false');";
        
        $insertEncontro = $this->conexao->prepare($sql);
        $insertEncontro->bindValue(':id_encontro', $novoid, PDO::PARAM_INT);
        $insertEncontro->bindValue(':id_local', $id_local, PDO::PARAM_STR);
        $insertEncontro->bindValue(':id_cardapio', $id_cardapio, PDO::PARAM_STR);
        $insertEncontro->bindValue(':hr_encontro', $hr_encontro, PDO::PARAM_STR);
        $insertEncontro->bindValue(':nu_max_convidados', $nu_max_convidados, PDO::PARAM_STR);
        $insertEncontro->execute();

        $SqlEncontroCriada = "select * from tb_encontro_dn where id_encontro = " . $novoid;
        $getEncontro = $this->conexao->query($SqlEncontroCriada);
        $responseEncontroCriada = $getEncontro->fetchAll(PDO::FETCH_ASSOC);

        if (!$responseEncontroCriada){
            throw new Exception("Não foi possível criar o encontro");
        }
        
        $this->banco->setDados(1, $responseEncontroCriada);
    }

    public function updateEncontro() {
        $sql = "";

        $consulta = $this->conexao->query($sql);
        $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $this->banco->setDados(count($resultados), $resultados);

        if (!$resultados) {
            $this->banco->setDados(0, []);
        }
        
        return $resultados;
    }

    public function deleteEncontro() {
        $sql = "DELETE FROM tb_local_dn WHERE id_encontro_dn = ".$POST['id_encontro'];

        $deleteuser = $this->conexao->query($sql);
        $response = $deleteuser->fetchAll(PDO::FETCH_ASSOC);
        if (!$response){throw new Exception("Não foi possível deletar usuário");}

        $this->banco->setMensagem(1, "Deletado com sucesso");
        return $response;
    }

    // Exemplo de rotas na url:
    // http://localhost/pdm/api/v1/Encontro/EncontroController.php/?operacao=getRefeicoes
    // http://localhost/pdm/api/v1/Encontro/EncontroController.php/?operacao=getEncontro&id_Encontro=2
    // http://localhost/pdm/api/v1/Encontro/EncontroController.php/?operacao=createEncontro
    // http://localhost/pdm/api/v1/Encontro/EncontroController.php/?operacao=updateEncontro
    // http://localhost/pdm/api/v1/Encontro/EncontroController.php/?operacao=deleteEncontro
}
