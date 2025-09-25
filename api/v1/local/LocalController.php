<?php
      
    require_once('./LocalService.php');
    require_once('../../database/Banco.php');
    
    try {  
        $jsonPostData = json_decode(file_get_contents("php://input"), true);

        $operacao = isset($_REQUEST['operacao']) ? $_REQUEST['operacao'] : "Não informado [Erro]";
    
        $banco = new Banco(null,null,null,null,null,null);
        
        $LocalService = new LocalService($banco);
        
        switch ($operacao) {
            case 'getLocal':
                $LocalService->getLocal();
                break;   
            case 'createLocal':
                $LocalService->getRefeicoes();
                break;
            case 'updateLocal':
                $LocalService->getRefeicoes();
                break;    
            case 'deleteLocal':
                $LocalService->getRefeicoes();
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
