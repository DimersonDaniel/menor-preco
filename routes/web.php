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
                Route::post('/listar-queues', 'HomeController@listarRegistros');
                Route::post('/consulta-rapida', 'HomeController@consultaRapida');
                Route::post('/download-planilha-padrao', 'HomeController@download');
                Route::post('/gerar-download', 'HomeController@gerarDwonload');
                Route::post('/consulta-download', 'HomeController@downloadConsultas');
                Route::post('/download-listar', 'HomeController@downloads');
                Route::post('/filtros', 'HomeController@filtros');
                Route::post('/listar-produtos', 'HomeController@listarConsultas');
                Route::post('/pesquisa-filtros', 'HomeController@pesquisaFiltros');
                Route::post('/listar-filtros', 'HomeController@listarFiltros');
                Route::post('/destroy-filtros', 'HomeController@destroyFiltros');
            });


    });
