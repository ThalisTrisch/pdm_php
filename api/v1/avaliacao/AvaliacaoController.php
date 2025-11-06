<?php
      
    require_once('./AvaliacaoService.php');
    require_once('../../database/Banco.php');

    try {  
        $jsonPostData = json_decode(file_get_contents("php://input"), true);

        $operacao = isset($_REQUEST['operacao']) ? $_REQUEST['operacao'] : "Não informado [Erro]";
    
        $banco = new Banco(null,null,null,null,null,null);
        
        $AvaliacaoService = new AvaliacaoService($banco);
        
        switch ($operacao) {
            case 'getAvaliacao':
                $AvaliacaoService->getAvaliacao();
                break;   
            case 'getAvaliacoes':
                $AvaliacaoService->getAvaliacoes();
                break;  
            case 'createAvaliacao':
                $id_usuario = isset($_POST['id_usuario']) ? $_POST['id_usuario'] : throw new Exception("campo id_usuario não fornecido");
                $id_encontro = isset($_POST['id_encontro']) ? $_POST['id_encontro'] : throw new Exception("campo id_encontro não fornecido");
                $id_avaliacao = isset($_POST['id_avaliacao']) ? $_POST['id_avaliacao'] : throw new Exception("campo id_avaliacao não fornecido");
                $vl_avaliacao = isset($_POST['vl_avaliacao']) ? $_POST['vl_avaliacao'] : throw new Exception("campo vl_avaliacao não fornecido");
                $AvaliacaoService->createAvaliacao($id_usuario, $id_encontro, $vl_avaliacao, $id_avaliacao);
                break;    
            default:
                $banco->setMensagem(1, 'Operação informada não tratada. Operação=' . $operacao);
                break;
        }

        echo $banco->getRetorno();
        unset($banco);
    }
    catch(Exception $e) {   
        if (isset($banco)) {   
            $banco->setMensagem(1, $e->getMessage());
            echo $banco->getRetorno();
            unset($banco);
        } else {
            header("Content-Type: application/json; charset=UTF-8");
            echo json_encode([
                "operacao" => isset($operacao) ? $operacao : 'desconhecida',
                "NumMens" => 1,
                "Mensagem" => $e->getMessage(),
                "registros" => 0,
                "dados" => null
            ]);
        }
    }
            
?>
