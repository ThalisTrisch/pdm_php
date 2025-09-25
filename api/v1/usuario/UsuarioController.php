<?php
      
    require_once('UsuarioService.php');
    require_once('../../database/Banco.php');
    require_once('UsuarioValidacao.php');

    try {  
        $jsonPostData = json_decode(file_get_contents("php://input"), true);
 
        $operacao = isset($_REQUEST['operacao']) ? $_REQUEST['operacao'] : "Não informado [Erro]";

        $banco = new Banco(null,null,null,null,null,null);
        
        $usuarioService = new UsuarioService($banco);

        switch ($operacao) {
            case 'getUsuarios':
                $usuarioService->getUsuarios();
                break;   
            case 'getUsuario':
                $usuarioService->getUsuario();
                break;
            case 'createUsuario':
                $nu_cpf = isset($jsonPostData['nu_cpf']) ? $jsonPostData['nu_cpf'] : "";
                $nm_usuario = isset($jsonPostData['nm_usuario']) ? $jsonPostData['nm_usuario'] : "";
                $vl_email = isset($jsonPostData['vl_email']) ? $jsonPostData['vl_email'] : "";
                $nm_sobrenome = isset($jsonPostData['nm_sobrenome']) ? $jsonPostData['nm_sobrenome'] : "";
                $vl_senha = isset($jsonPostData['vl_senha']) ? $jsonPostData['vl_senha'] : "";
                validaDados($nu_cpf, $nm_usuario, $vl_email, $nm_sobrenome, $vl_senha);
                $usuarioService->createUsuario($nu_cpf, $nm_usuario, $vl_email, $nm_sobrenome, $vl_senha);
                break;
            case 'deleteUsuario':
                $usuarioService->deleteUsuario($jsonPostData);
                break;
            case 'updateUsuario':
                $usuarioService->updateUsuario(dados: $jsonPostData);
                break;
            case 'loginUsuario':
                $usuarioService->loginUsuario(loginData: $jsonPostData);
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