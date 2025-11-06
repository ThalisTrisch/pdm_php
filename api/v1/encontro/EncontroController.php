<?php
    require_once('./EncontroService.php');
    require_once('../../database/Banco.php');
    
    try {  
        $postdata = json_decode(file_get_contents("php://input"), true);

        $operacao = isset($_REQUEST['operacao']) ? $_REQUEST['operacao'] : "Não informado [Erro]";
    
        $banco = new Banco(null,null,null,null,null,null);
        
        $EncontroService = new EncontroService($banco);
        
        switch ($operacao) {
            case 'addUsuarioEncontro':
                $id_encontro = isset($_POST['id_encontro']) ? $_POST['id_encontro'] : throw new Exception("campo id_encontro não fornecido");
                $id_usuario = isset($_POST['id_usuario']) ? $_POST['id_usuario'] : throw new Exception("campo id_usuario não fornecido");
                $EncontroService->addUsuarioEncontro($id_usuario, $id_encontro);
                break; 
            case 'getEncontros':
                $EncontroService->getEncontros();
                break;   
            case 'getEncontro':
                $EncontroService->getEncontro();
                break;   
            case 'getUsuarioEncontros':
                $EncontroService->getUsuarioEncontros();
                break;   
            case 'createEncontro':
                $id_local = isset($_POST['id_local']) ? $_POST['id_local'] : throw new Exception("campo id_local não fornecido");
                $hr_encontro = isset($_POST['hr_encontro']) ? $_POST['hr_encontro'] : throw new Exception("campo hr_encontro não fornecido");
                $id_cardapio = isset($_POST['id_cardapio']) ? $_POST['id_cardapio'] : throw new Exception("campo id_cardapio não fornecido");
                $nu_max_convidados = isset($_POST['nu_max_convidados']) ? $_POST['nu_max_convidados'] : throw new Exception("campo nu_max_convidados não fornecido");                
                $EncontroService->createEncontro($id_local, $hr_encontro, $nu_max_convidados, $id_cardapio);
                break;
            case 'updateEncontro':
                $EncontroService->getEncontro();
                break;    
            case 'deleteEncontro':
                $id_encontro = isset($_POST['id_encontro']) ? $_POST['id_encontro'] : throw new Exception("campo id_encontro não fornecido");
                $EncontroService->getEncontro();
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
