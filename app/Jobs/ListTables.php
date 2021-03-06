<?php

namespace App\Jobs;

use App\Models\Table;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ListTables implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $tables = shell_exec('mdb-tables ' . env('USER_PATH') . 'public/data.mdb');

        Table::truncate();

        foreach (explode(' ',  $tables) as $table) {
            if (empty(trim($table)))
                continue;

            $table = Table::create(['name' => $table]);
            FetchTable::dispatch($table)->onQueue('default');
        }
    }
}
