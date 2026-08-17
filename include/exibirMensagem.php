<?php

require_once __DIR__ . '/../tipoMensagem.php';

if (!empty($_GET['msg'])) {
    $mensagem = obterMensagem($_GET['msg']);

    if ($mensagem !== null) {
        $tipo  = htmlspecialchars($mensagem['tipo']);
        $texto = htmlspecialchars($mensagem['texto']);
        echo <<<HTML
        <div class="alert alert-{$tipo} alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Fechar">
                <span aria-hidden="true">&times;</span>
            </button>
            {$texto}
        </div>
        HTML;
    }
}
