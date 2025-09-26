<?php
      
    require_once('./LocalService.php');
    require_once('../../database/Banco.php');
    require_once('LocalValidacao.php');

    try {  
        $jsonPostData = json_decode(file_get_contents("php://input"), true);

        $operacao = isset($_REQUEST['operacao']) ? $_REQUEST['operacao'] : "Não informado [Erro]";
    
        $banco = new Banco(null,null,null,null,null,null);
        
        $LocalService = new LocalService($banco);
        
        switch ($operacao) {
            case 'getLocal':
                $LocalService->getLocal();
                break;   
            case 'getLocais':
                $LocalService->getLocais();
                break;  
            case 'createLocal':
                $nu_cep = isset($jsonPostData['nu_cep']) ? $jsonPostData['nu_cep'] : "";
                $nu_casa = isset($jsonPostData['nu_casa']) ? $jsonPostData['nu_casa'] : "";
                $id_usuario = isset($jsonPostData['id_usuario']) ? $jsonPostData['id_usuario'] : "";
                $nu_cnpj = isset($jsonPostData['nu_cnpj']) ? $jsonPostData['nu_cnpj'] : "";
                validaDados($nu_cep, $nu_casa, $id_usuario, $nu_cnpj);
                $LocalService->createLocal($nu_cep, $nu_casa, $id_usuario, $nu_cnpj);
                break;    
            case 'deleteLocal':
                $LocalService->deleteLocal();
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
