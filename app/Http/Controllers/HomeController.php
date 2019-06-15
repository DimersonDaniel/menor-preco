<?php

namespace App\Http\Controllers;

use App\Exports\ConsultasCSV;
use App\Exports\PlanilhaExemplo;
use App\Helpers\StatusResponse;
use App\Http\Requests\Filtros;
use App\Http\Requests\Importacao;
use App\Jobs\ImportacaoProcess;
use App\Models\StoreFile;
use App\Models\StoreFilters;
use App\Models\StoreProducts;
use App\Repository\ConsultaRapida\BuscarProduto;
use App\Repository\ListarProdutos\ProdutosManagerFilters;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('app');
    }

    public function importar(Importacao $request)
    {
        $filters = StoreFilters::all();

        if ($filters->isEmpty()) {
           return StatusResponse::errors(
             '422',
             'Error',
             'É necessario adicionar filtros de pesquisa!'
           );
        }

        $fileRequest    = $request->file('fileUpload');
        $path           = $fileRequest->store('filesForProcess');

        ImportacaoProcess::dispatch($path);

        return 'sucesso';

    }

    public function download(Request $request)
    {
       return Excel::download(new PlanilhaExemplo(), 'planilhaModelo.xlsx');
    }

    public function downloads()
    {
        $data = StoreFile::select(
            [
                'id',
                'file_name',
                'descricao',
                'created_at'
            ])->orderBy('id', 'desc')
            ->get();

        return $data;
    }

    public function gerarDwonload(Request $request)
    {
        (new ConsultasCSV())->init();
    }

    public function downloadConsultas(Request $request){
        $path = StoreFile::find($request->idFile);
        return Storage::download($path->file_path);
    }

    public function filtros(Filtros $request){
        $config          = new StoreFilters();
        $config->name    = strtoupper($request->name);
        $config->save();
    }

    public function novoProduto(Request $request)
    {
        $novo = new StoreProducts();
        $novo->codigo_barra = $request->codigo_barra;
        $novo->name         = $request->name;
        $novo->save();
    }
    public function listarFiltros()
    {
        return  StoreFilters::all();
    }

    public function pesquisaFiltros(Request $request)
    {
        return StoreFilters::where('name', 'LIKE', '%'.$request->search.'%')->get();
    }

    public function destroyFiltros(Request $request)
    {
        foreach ($request->list  as $item){
            $active = StoreFilters::find($item['id']);
            $active->forcedelete();
        }
    }

    public function consultaRapida(Request $request)
    {
       return (new BuscarProduto())->init($request->search);
    }

    public function listarConsultas(Request $request)
    {
       return (new ProdutosManagerFilters($request))->listar();
    }
}
