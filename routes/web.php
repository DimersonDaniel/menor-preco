<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();


Route::group(['prefix'    => '/' ],
    function ()
    {

        Route::get('{vue_capture?}', 'HomeController@index')
            ->where('vue_capture', '[\/\w\.-]*');

        Route::group(['prefix'    => '/dashboard' ],

            function ()
            {
                Route::post('/importar', 'HomeController@importar');
                Route::post('/consulta-rapida', 'HomeController@consultaRapida');
                Route::post('/download-planilha-padrao', 'HomeController@download');
                Route::post('/download-consultas', 'HomeController@downloadConsultas');
                Route::post('/novo-produto', 'HomeController@novoProduto');
                Route::post('/pesquisa-enderecos', 'HomeController@pesquisaEnderecos');
                Route::post('/endereco', 'HomeController@endereco');
                Route::post('/listar-enderecos', 'HomeController@listarEnderecos');
                Route::post('/destroy-enderecos', 'HomeController@destroyEnderecos');
                Route::post('/active-enderecos', 'HomeController@activeEnderecos');
                Route::post('/listar-produtos', 'HomeController@listarConsultas');
            });


    });
