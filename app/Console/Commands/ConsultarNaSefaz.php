<?php

namespace App\Console\Commands;

use App\Repository\QuerySefaz;
use Illuminate\Console\Command;

class ConsultarNaSefaz extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'consulta {cod_produto} {name_produto}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    private $cod_produto;
    private $name_produto;
    public function handle()
    {
        $this->cod_produto = $this->argument('cod_produto');
        $this->name_produto = $this->argument('name_produto');

        $result = (new QuerySefaz())->queryProduto($this->cod_produto);
        print_r($result);
    }
}
