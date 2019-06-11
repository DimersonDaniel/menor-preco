<?php

namespace App\Console\Commands;

use App\Repository\Importacao\ImportarExcelProdutos;
use App\Repository\QuerySefaz;
use Illuminate\Console\Command;

class ConsultarPreco extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'consulta:preco {path}';

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
    private $path;
    public function handle()
    {
        $this->path = $this->argument('path');

        (new ImportarExcelProdutos($this->path))->execute();
    }
}
