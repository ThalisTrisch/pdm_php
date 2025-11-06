<?php
require_once('../../database/InstanciaBanco.php');


class AvaliacaoService extends InstanciaBanco {
    public function getAvaliacao() {
        $sql = "SELECT * from tb_avaliacao_encontro_dn where id_encontro = ".$_GET['id_encontro']." and id_usuario = ".$_GET['id_usuario'];

        $consulta = $this->conexao->query($sql);
        $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $this->banco->setDados(count($resultados), $resultados);

        if (!$resultados) {
            $this->banco->setDados(0, []);
        }
        
        return $resultados;
    }

    public function getAvaliacoes() {
        $sql = "SELECT * from tb_tipo_avaliacao_dn";

        $consulta = $this->conexao->query($sql);
        $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $this->banco->setDados(count($resultados), $resultados);

        if (!$resultados) {
            $this->banco->setDados(0, []);
        }
        
        return $resultados;
    }

    public function createAvaliacao($id_usuario, $id_encontro, $vl_avaliacao, $id_avaliacao) {
        
        $sql = "INSERT INTO tb_avaliacao_encontro_dn (id_usuario, id_encontro, vl_avaliacao, id_avaliacao) VALUES (:id_usuario, :id_encontro,:vl_avaliacao, :id_avaliacao)";

        $insertavaliacao = $this->conexao->prepare($sql);

        $insertavaliacao->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $insertavaliacao->bindValue(':id_encontro', $id_encontro, PDO::PARAM_INT);
        $insertavaliacao->bindValue(':vl_avaliacao', $vl_avaliacao, PDO::PARAM_STR);
        $insertavaliacao->bindValue(':id_avaliacao', $id_avaliacao, PDO::PARAM_STR);
        $resultados = $insertavaliacao->execute();

        if ($resultados) {
            $this->banco->setDados(1, [$resultados]);
        }else{
            throw new Exception("Não foi possível criar a avaliacao do encontro");
        }
        return $resultados;
    }
    
    
    public function deleteavaliacao($id_avaliacao) {
        $sql = "DELETE FROM tb_avaliacao_dn WHERE id_avaliacao = ".$_POST['id_avaliacao'];

        $deleteuser = $this->conexao->query($sql);
        $responseuser = $deleteuser->fetchAll(PDO::FETCH_ASSOC);
        if (!$responseuser){throw new Exception("Não foi possível deletar o avaliacao");}

        $this->banco->setMensagem(1, "Deletado com sucesso");
        return $responseuser;
    }

    // Exemplo de rotas na url:
    // http://avaliacaohost/pdm/api/v1/refeicao/RefeicaoController.php/?operacao=getRefeicoes
    // http://avaliacaohost/pdm/api/v1/refeicao/RefeicaoController.php/?operacao=getRefeicao&id_refeicao=2
    // http://avaliacaohost/pdm/api/v1/refeicao/RefeicaoController.php/?operacao=createRefeicao
    // http://avaliacaohost/pdm/api/v1/refeicao/RefeicaoController.php/?operacao=updateRefeicao
    // http://avaliacaohost/pdm/api/v1/refeicao/RefeicaoController.php/?operacao=deleteRefeicao
}
