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

    public function createLocal() {
        
        $sql = "INSERT INTO tb_local (id_local, nu_cep, nu_casa, id_usuario, nu_cnpj) VALUES (:id_local, :nu_cep, :nu_casa, :id_usuario, :nu_cnpj);";

        $insertlocal = $this->conexao->prepare($sql);

        $insertlocal->bindValue(':id_usuario', $novoid, PDO::PARAM_INT);
        $insertlocal->bindValue(':nu_cpf', $nu_cpf, PDO::PARAM_STR);
        $insertlocal->bindValue(':nu_cnpj', $nm_usuario, PDO::PARAM_STR);
        $insertlocal->bindValue(':nu_cep', $vl_email, PDO::PARAM_STR);
        $insertlocal->bindValue(':nu_casa', $nm_sobrenome, PDO::PARAM_STR);
        $resultados = $insertlocal->execute();

        if ($resultados) {
            $this->banco->setDados(1, [resultados]);
        }else{
            throw new Exception("Não foi possível criar o local");
        }
        return $resultados;
    }
    
    public function updateLocal() {
        $sql = "";

        $consulta = $this->conexao->query($sql);
        $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $this->banco->setDados(count($resultados), $resultados);

        if (!$resultados) {
            $this->banco->setDados(0, []);
        }
        
        return $resultados;
    }
    public function deleteLocal() {
        $sql = "";

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
