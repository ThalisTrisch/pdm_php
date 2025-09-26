<?php
require_once('../../database/InstanciaBanco.php');


class LocalService extends InstanciaBanco {
    public function getLocal() {
        $sql = "SELECT * from tb_local where id_local = ".$_GET['id_local'];

        $consulta = $this->conexao->query($sql);
        $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $this->banco->setDados(count($resultados), $resultados);

        if (!$resultados) {
            $this->banco->setDados(0, []);
        }
        
        return $resultados;
    }

    public function createLocal($nu_cep, $nu_casa, $id_usuario, $nu_cnpj) {
        $sql = "select id_sequence from tb_sequence order by id_sequence desc limit 1;";
        $consulta = $this->conexao->query($sql);
        $maiorid = $consulta->fetchAll(PDO::FETCH_ASSOC);

        if (!$maiorid){
            throw new Exception("Maior id não localizado");
        }
        $novo_id = $maiorid[0]['id_sequence'] + 1;
        
        $sqlseq ="INSERT INTO tb_sequence (id_sequence, nm_sequence) VALUES (".$novo_id.", 'L')";
        $insertseq = $this->conexao->query($sqlseq);
        $responseseq = $insertseq->fetchAll(PDO::FETCH_ASSOC);
        if (!$responseseq){throw new Exception("Não foi possível criar a sequence do usuario");}

        $sql = "INSERT INTO tb_local (id_local, id_usuario, nu_cep, nu_casa, nu_cnpj) VALUES (:id_local, :id_usuario, :nu_cep, :nu_casa, :nu_cnpj);";

        $insertlocal = $this->conexao->prepare($sql);

        $insertlocal->bindValue(':id_local', $novo_id, PDO::PARAM_INT);
        $insertlocal->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $insertlocal->bindValue(':nu_cnpj', $nu_cnpj, PDO::PARAM_STR);
        $insertlocal->bindValue(':nu_cep', $nu_cep, PDO::PARAM_STR);
        $insertlocal->bindValue(':nu_casa', $nu_casa, PDO::PARAM_STR);
        $resultados = $insertlocal->execute();

        if ($resultados) {
            $this->banco->setDados(1, [$resultados]);
        }else{
            throw new Exception("Não foi possível criar o local");
        }
        return $resultados;
    }
    
    
    public function deleteLocal() {
        $sql = "DELETE FROM tb_local WHERE id_local = ".$_POST['id_local']." CASCADE";

        $consulta = $this->conexao->query($sql);
        $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $this->banco->setDados(count($resultados), $resultados);

        if (!$resultados) {
            $this->banco->setDados(0, []);
        }
        
        return $resultados;
    }

    // Exemplo de rotas na url:
    // http://localhost/pdm/api/v1/refeicao/RefeicaoController.php/?operacao=getRefeicoes
    // http://localhost/pdm/api/v1/refeicao/RefeicaoController.php/?operacao=getRefeicao&id_refeicao=2
    // http://localhost/pdm/api/v1/refeicao/RefeicaoController.php/?operacao=createRefeicao
    // http://localhost/pdm/api/v1/refeicao/RefeicaoController.php/?operacao=updateRefeicao
    // http://localhost/pdm/api/v1/refeicao/RefeicaoController.php/?operacao=deleteRefeicao
}
