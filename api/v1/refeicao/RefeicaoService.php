<?php
require_once('../../database/InstanciaBanco.php');

class RefeicaoService extends InstanciaBanco {
    public function getRefeicoes() {
        $sql = "SELECT 
                    b.id_usuario, 
                    b.nm_usuario || ' ' || b.nm_sobrenome as nm_usuario_anfitriao,
                    a.dc_cardapio as nm_cardapio,
                    c.id_refeicao,
                    c.hr_encontro,
                    c.nu_max_convidados, 
                    c.preco_refeicao,
                    d.id_local,
                    d.nu_cep,
                    d.nu_casa
                FROM tb_cardapio a
                INNER JOIN tb_usuario b ON a.id_usuario = b.id_usuario
                INNER JOIN tb_refeicao c ON a.id_cardapio = c.id_cardapio
                INNER JOIN tb_local d ON c.id_local = d.id_local
                WHERE c.hr_encontro > now()
                ORDER BY c.hr_encontro ASC";

        $consulta = $this->conexao->query($sql);
        $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $this->banco->setDados(count($resultados), $resultados);

        if (!$resultados) {
            $this->banco->setDados(0, []);
        }
        
        return $resultados;
    }

    public function getRefeicao() {
        $sql = "SELECT 
                    b.id_usuario, 
                    b.nm_usuario || ' ' || b.nm_sobrenome as nm_usuario_anfitriao,
                    a.dc_cardapio as nm_cardapio,
                    c.id_refeicao,
                    c.hr_encontro,
                    c.nu_max_convidados, 
                    c.preco_refeicao,
                    d.id_local,
                    d.nu_cep,
                    d.nu_casa
                FROM tb_cardapio a
                INNER JOIN tb_usuario b ON a.id_usuario = b.id_usuario
                INNER JOIN tb_refeicao c ON a.id_cardapio = c.id_cardapio
                INNER JOIN tb_local d ON c.id_local = d.id_local
                WHERE c.hr_encontro > now()
                ORDER BY c.hr_encontro ASC";

        $consulta = $this->conexao->query($sql);
        $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $this->banco->setDados(count($resultados), $resultados);

        if (!$resultados) {
            $this->banco->setDados(0, []);
        }
        
        return $resultados;
    }

    public function createRefeicao() {
        $sql = "INSERT INTO tb_refeicao (id_refeicao, id_local, id_cardapio, hr_encontro, nu_max_convidados, preco_refeicao) VALUES (:id_refeicao,:id_local,:hr_encontro,:nu-max_convidados,:preco_refeicao);";

        $consulta = $this->conexao->query($sql);
        $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $this->banco->setDados(count($resultados), $resultados);

        if (!$resultados) {
            $this->banco->setDados(0, []);
        }
        
        return $resultados;
    }
    public function updateRefeicao() {
        $sql = "";

        $consulta = $this->conexao->query($sql);
        $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $this->banco->setDados(count($resultados), $resultados);

        if (!$resultados) {
            $this->banco->setDados(0, []);
        }
        
        return $resultados;
    }
    public function deleteRefeicao() {
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
