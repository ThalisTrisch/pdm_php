<?php
require_once('../../database/InstanciaBanco.php');


class CardapioService extends InstanciaBanco {
    public function getCardapio() {
        $sql = "SELECT * from tb_cardapio_dn where id_cardapio = ".$_GET['id_cardapio'];

        $consulta = $this->conexao->query($sql);
        $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $this->banco->setDados(count($resultados), $resultados);

        if (!$resultados) {
            $this->banco->setDados(0, []);
        }
        
        return $resultados;
    }

    public function getCardapios() {
         
        $sql = "SELECT * from tb_Cardapio_dn";

        $consulta = $this->conexao->query($sql);
        $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $this->banco->setDados(count($resultados), $resultados);

        if (!$resultados) {
            $this->banco->setDados(0, []);
        }
        
        return $resultados;
    }

    public function createCardapio($id_local, $ds_cardapio, $nm_cardapio) {
        $sql = "select id_sequence from tb_sequence_dn order by id_sequence desc limit 1;";
        $consulta = $this->conexao->query($sql);
        $maiorid = $consulta->fetchAll(PDO::FETCH_ASSOC);

        if (!$maiorid){
            throw new Exception("Maior id não encontrado");
        }
        $novo_id = $maiorid[0]['id_sequence'] + 1;
        
        $sqlseq ="INSERT INTO tb_sequence_dn (id_sequence, nm_sequence) VALUES (".$novo_id.", 'C')";
        $insertseq = $this->conexao->query($sqlseq);
        $responseseq = $insertseq->fetchAll(PDO::FETCH_ASSOC);
        if (!$responseseq){throw new Exception("Não foi possível criar a sequence do usuario");}
        $sql = "INSERT INTO tb_cardapio_dn (id_cardapio, id_local, ds_cardapio, nm_cardapio) VALUES (:id_cardapio, :id_local, :ds_cardapio, :nm_cardapio)";

        $insertCardapio = $this->conexao->prepare($sql);

        $insertCardapio->bindValue(':id_cardapio', $novo_id, PDO::PARAM_INT);
        $insertCardapio->bindValue(':id_local', $id_local, PDO::PARAM_INT);
        $insertCardapio->bindValue(':ds_cardapio', $ds_cardapio, PDO::PARAM_STR);
        $insertCardapio->bindValue(':nm_cardapio', $nm_cardapio, PDO::PARAM_STR);
        $resultados = $insertCardapio->execute();

        if ($resultados) {
            $this->banco->setDados(1, [$resultados]);
        }else{
            throw new Exception("Não foi possível criar o Cardapio");
        }
        return $resultados;
    }
    
    
    public function deleteCardapio($id_cardapio) {
        $sql = "DELETE FROM tb_cardapio_dn WHERE id_cardapio = ".$_POST['id_cardapio'];

        $deleteuser = $this->conexao->query($sql);
        $responseuser = $deleteuser->fetchAll(PDO::FETCH_ASSOC);
        if (!$responseuser){throw new Exception("Não foi possível deletar o cardapio");}

        $this->banco->setMensagem(1, "Deletado com sucesso");
        return $responseuser;
    }

    public function getCardapiosDisponiveis() {
        $sql = "select 
                    c.id_usuario,
                    c.nm_usuario || ' ' || c.nm_sobrenome as nm_usuario_anfitriao,
                    a.id_cardapio,
                    a.ds_cardapio as nm_cardapio,
                    a.preco_refeicao,
                    d.hr_encontro,
                    d.nu_max_convidados,
                    a.id_local,
                    b.nu_cep,
                    b.nu_casa
                from tb_cardapio_dn a 
                inner join tb_local_dn b on a.id_local = b.id_local
                inner join tb_usuario_dn c on b.id_usuario = c.id_usuario
                inner join tb_encontro_dn d on b.id_local = d.id_local
                WHERE d.hr_encontro > now()
                ORDER BY d.hr_encontro ASC";
    

        $consulta = $this->conexao->query($sql);
        $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $this->banco->setDados(count($resultados), $resultados);

        if (!$resultados) {
        $this->banco->setDados(0, []);
        }

        return $resultados;
    }

    public function getMeuCardapio($idLocal) {
        $sql = "SELECT 
                    id_cardapio, 
                    ds_cardapio, 
                    nm_cardapio, 
                    preco_refeicao 
                FROM tb_cardapio_dn 
                WHERE id_local = :idLocal";
    
        $consulta = $this->conexao->prepare($sql);
        $consulta->bindParam(':idLocal', $idLocal, PDO::PARAM_INT);
        $consulta->execute();
    
        $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
    
        if ($resultados) {
            $this->banco->setDados(count($resultados), $resultados);
        } else {
            $this->banco->setDados(0, []);
        }
    
        return $resultados;
    }
    
    // Exemplo de rotas na url:
    // http://Cardapiohost/pdm/api/v1/refeicao/RefeicaoController.php/?operacao=getRefeicoes
    // http://Cardapiohost/pdm/api/v1/refeicao/RefeicaoController.php/?operacao=getRefeicao&id_refeicao=2
    // http://Cardapiohost/pdm/api/v1/refeicao/RefeicaoController.php/?operacao=createRefeicao
    // http://Cardapiohost/pdm/api/v1/refeicao/RefeicaoController.php/?operacao=updateRefeicao
    // http://Cardapiohost/pdm/api/v1/refeicao/RefeicaoController.php/?operacao=deleteRefeicao
}
