<?php

namespace App\Jobs;

use App\Repository\Importacao\ImportarExcelProdutos;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportacaoProcess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    private $path;
    private $fileName;
    private $id_user;

    public function __construct($path, $fileName,$id_user)
    {
        $this->path = $path;
        $this->fileName = $fileName;
        $this->id_user = $id_user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */

    public function handle()
    {
        (new ImportarExcelProdutos($this->path, $this->fileName, $this->id_user))->execute();
    }
}
