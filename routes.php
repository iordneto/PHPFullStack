<?php

$app->group('/v1', function() {
    $this->group('/anunciantes', function() {
        $this->get('', '\App\v1\Controllers\AnuncianteController:listarAnunciantes');
        $this->post('', '\App\v1\Controllers\AnuncianteController:inserirAnunciante');
        
        $this->get('/{id:[0-9]+}', '\App\v1\Controllers\AnuncianteController:visualizarAnunciante');
        $this->put('/{id:[0-9]+}', '\App\v1\Controllers\AnuncianteController:atualizarAnunciante');
        $this->delete('/{id:[0-9]+}', '\App\v1\Controllers\AnuncianteController:deletarAnunciante');

        $this->get('/devedores', '\App\v1\Controllers\AnuncianteController:listarDividaAnunciantes');
    });

    $this->group('/anunciantes/{idAnunciante:[0-9]+}/anuncios', function() {
        $this->get('', '\App\v1\Controllers\AnuncioController:listarAnuncios');
        $this->post('', '\App\v1\Controllers\AnuncioController:inserirAnuncio');
        
        $this->get('/{id:[0-9]+}', '\App\v1\Controllers\AnuncioController:visualizarAnuncio');
        $this->put('/{id:[0-9]+}', '\App\v1\Controllers\AnuncioController:atualizarAnuncio');
        $this->delete('/{id:[0-9]+}', '\App\v1\Controllers\AnuncioController:deletarAnuncio');

        $this->get('/{id:[0-9]+}/ativar', '\App\v1\Controllers\AnuncioController:ativarAnuncio');
        $this->get('/{id:[0-9]+}/desativar', '\App\v1\Controllers\AnuncioController:desativarAnuncio');
    });

    $this->group('/auth', function() {
        $this->get('', \App\v1\Controllers\AuthController::class);
    });
});
