<?php

function obterMensagem(string $codigo): ?array
{
    $mensagens = [
        'cliente_cadastrado' => ['tipo' => 'success', 'texto' => 'Cliente cadastrado com sucesso!'],
        'cliente_editado'    => ['tipo' => 'success', 'texto' => 'Cliente atualizado com sucesso!'],
        'cliente_excluido'   => ['tipo' => 'success', 'texto' => 'Cliente excluído com sucesso!'],
        'cliente_erro'       => ['tipo' => 'danger',  'texto' => 'Ocorreu um erro ao processar o cliente.'],
        'cliente_cpf_duplicado' => ['tipo' => 'danger', 'texto' => 'Já existe um cliente cadastrado com esse CPF.'],

        'veiculo_cadastrado' => ['tipo' => 'success', 'texto' => 'Veículo cadastrado com sucesso!'],
        'veiculo_editado'    => ['tipo' => 'success', 'texto' => 'Veículo atualizado com sucesso!'],
        'veiculo_excluido'   => ['tipo' => 'success', 'texto' => 'Veículo excluído com sucesso!'],
        'veiculo_erro'       => ['tipo' => 'danger',  'texto' => 'Ocorreu um erro ao processar o veículo.'],
        'imagem_invalida'    => ['tipo' => 'danger',  'texto' => 'Formato de imagem inválido. Utilize JPG, JPEG ou PNG.'],
        'imagem_grande'      => ['tipo' => 'danger',  'texto' => 'A imagem é muito grande. Envie um arquivo menor.'],

        'comentario_adicionado' => ['tipo' => 'success', 'texto' => 'Comentário adicionado com sucesso!'],
        'comentario_erro'       => ['tipo' => 'danger',  'texto' => 'Não foi possível adicionar o comentário.'],

        'login_erro'   => ['tipo' => 'danger', 'texto' => 'Login ou senha inválidos.'],
        'login_sair'   => ['tipo' => 'info',   'texto' => 'Você saiu do sistema.'],
        'acesso_negado' => ['tipo' => 'warning', 'texto' => 'É necessário fazer login para acessar esta página.'],

        'campos_obrigatorios' => ['tipo' => 'warning', 'texto' => 'Preencha todos os campos obrigatórios.'],
    ];

    return $mensagens[$codigo] ?? null;
}
