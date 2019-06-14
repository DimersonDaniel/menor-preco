<?php

namespace App\Http\Controllers;

use App\Exports\Consultas;
use App\Exports\PlanilhaExemplo;
use App\Models\StoreEndereco;
use App\Models\StoreProducts;
use App\Repository\ConsultaRapida\BuscarProduto;
use App\Repository\ListarProdutos\ProdutosManagerFilters;
use App\Repository\QuerySefaz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
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

    public function importar(Request $request)
    {
        $fileRequest    = $request->file('fileUpload');
        $path           = $fileRequest->store('filesForProcess');

        Artisan::call('consulta:preco', ['path' => $path]);
    }

    public function download(Request $request)
    {
       return Excel::download(new PlanilhaExemplo(), 'planilhaModelo.xlsx');
    }

    public function downloadConsultas(Request $request)
    {
        return Excel::download(new Consultas(), 'consultas.xlsx');
    }

    public function configuracao(Request $request){
        $config             = new StoreEndereco();
        $config->id_user    = Auth::user()->id;
        $config->local      = $request->local;
        $config->apelido    = $request->apelido;
        $config->save();
    }

    public function novoProduto(Request $request)
    {

        $novo = new StoreProducts();
        $novo->codigo_barra = $request->codigo_barra;
        $novo->name         = $request->name;
        $novo->save();

    }

    public function listarEnderecos()
    {
        return  StoreEndereco::with('user:id,name,email')->get();
    }

    public function endereco(Request $request)
    {
        $endereco = StoreEndereco::where('id','=',$request->idEndereco)->first();
        return $endereco;
    }

    public function pesquisaEnderecos(Request $request)
    {
        $query = StoreEndereco::where('local', 'LIKE', '%'.$request->search.'%');

        if($request->active){
            $query->where('active', '=', $request->active);
        }

        return $query->with('user:id,name,email')->get();

    }

    public function destroyEnderecos(Request $request)
    {
        foreach ($request->list  as $item){
            $active = StoreEndereco::find($item['id']);
            $active->active = 2;
            $active->save();
        }
    }

    public function activeEnderecos(Request $request)
    {
        foreach ($request->list  as $item){
            $active = StoreEndereco::find($item['id']);
            $active->active = 1;
            $active->save();
        }
    }

    public function consultaRapida(Request $request)
    {
        //AMBIENTE DE TESTE
        //NEW COMMIT
        //return (new QuerySefaz())->queryProduto($request->search,1);

       return (new BuscarProduto())->init($request->search);
    }

    public function listarConsultas(Request $request)
    {
       return (new ProdutosManagerFilters($request))->listar();
    }
}
