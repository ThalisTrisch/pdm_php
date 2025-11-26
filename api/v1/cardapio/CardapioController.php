<?php
      
    require_once('./CardapioService.php');
    require_once('../../database/Banco.php');

    try {  
        $jsonPostData = json_decode(file_get_contents("php://input"), true);

        $operacao = isset($_REQUEST['operacao']) ? $_REQUEST['operacao'] : "Não informado [Erro]";
    
        $banco = new Banco(null,null,null,null,null,null);
        
        $CardapioService = new CardapioService($banco);
        
        switch ($operacao) {
            case 'getCardapio':
                $CardapioService->getCardapio();
                break;   
            case 'getCardapios':
                $CardapioService->getCardapios();
                break;  
            case 'createCardapio':
                $id_local = isset($_POST['id_local']) ? $_POST['id_local'] : throw new Exception("campo id_local não fornecido");
                $ds_cardapio = isset($_POST['ds_cardapio']) ? $_POST['ds_cardapio'] : throw new Exception("campo ds_cardapio não fornecido");
                $nm_cardapio = isset($_POST['nm_cardapio']) ? $_POST['nm_cardapio'] : throw new Exception("campo nm_cardapio não fornecido");
                $CardapioService->createCardapio($id_local, $ds_cardapio, $nm_cardapio);
                break;    
            case 'deleteCardapio':
                $id_cardapio = isset($_POST['id_cardapio']) ? $_POST['id_cardapio'] : throw new Exception("campo id_cardapio não fornecido");
                $CardapioService->deleteCardapio($id_cardapio);
                break; 
            case 'getCardapiosDisponiveis':
                $CardapioService->getCardapiosDisponiveis();
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
