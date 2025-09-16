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
}
