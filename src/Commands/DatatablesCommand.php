<?php

namespace Designbycode\Datatables\Commands;

use Illuminate\Console\Command;

class DatatablesCommand extends Command
{
    public $signature = 'laravel-datatables';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
