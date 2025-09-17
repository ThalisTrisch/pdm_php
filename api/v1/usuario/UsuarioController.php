<?php
      
    require_once('./UsuarioService.php');
    require_once('../../database/Banco.php');
    
    try {  
        $jsonPostData = json_decode(file_get_contents("php://input"), true);
 
        $operacao = isset($_REQUEST['operacao']) ? $_REQUEST['operacao'] : "Não informado [Erro]";
        //$nome = isset($jsonPostData['nome']) ? $jsonPostData['nome'] :""; <- exemplo json POST
    
        $banco = new Banco(null,null,null,null,null,null);
        
        $usuarioService = new UsuarioService($banco);
        
        switch ($operacao) {
            case 'getUsuarios':
                $usuarioService->getUsuarios($banco);
                break;   
            case 'getUsuario':
                $UsuarioService->getUsuario($banco);
                break;
            case 'createUsuario':
                $UsuarioService->newUsuario($banco);
                break;
            case 'deleteUsuario':
                $UsuarioService->deleteUsuario($banco);
                break;
            case 'login':
                $usuarioService->login(dados: $dados);
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