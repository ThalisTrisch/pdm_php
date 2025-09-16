<?php
      
    require_once('./UsuarioService.php');
    require_once('../../database/Banco.php');
    
    try {  
        $jsonData = json_decode(file_get_contents("php://input"), true);
 
        $operacao = isset($_REQUEST['operacao']) ? $_REQUEST['operacao'] : "Não informado [Erro]";
        //$nome = isset($jsonData['nome']) ? $jsonData['nome'] :""; <- exemplo json POST
    
        $banco = new Banco(null,null,null,null,null,null);
        
        $routes = new UsuarioService($banco);
        
        switch ($operacao) {
            case 'getUsuarios':
                $routes->getUsuarios($banco);
                break;   
            
            default:
                $banco->setMensagem(1,'Operacão informada nao tratada. Operação=' . $operacao );
                break;
        }

        echo $banco->getRetorno();
        unset($banco);
    }
    catch(Exception $e) {   
        if (isset($banco)) {   
            $banco->setMensagem(1,$e->getMessage());
            echo $banco->getRetorno();
            unset($banco);
        }   
        else {
            echo $e.getMessage();
        }
    }
            
?>