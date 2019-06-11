<?php

namespace App\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;

class Importacao implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    private $fullPath;
    public $timeout = 7200;
    public $tries   = 0;

    public function __construct(string $fullPath)
    {
        $this->fullPath         = $fullPath;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try{
            Artisan::call('consulta:preco', ['path' => $this->fullPath]);
        }
        catch (Exception $exception){
            $this->failed($exception);
        }
    }

    public function failed(Exception $exception)
    {
        logger('Erro na importacao');
        logger($exception->getMessage());
    }
}
