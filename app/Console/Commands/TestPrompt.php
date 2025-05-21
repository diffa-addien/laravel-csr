<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use function Laravel\Prompts\select;

class TestPrompt extends Command
{
    protected $signature = 'test:prompt';
    protected $description = 'Test Laravel Prompts select()';

    public function handle()
    {
        $choice = select(
            label: 'Pilih panel:',
            options: [
                'admin' => 'Admin Panel',
                'staff' => 'Staff Panel',
            ],
            default: 'admin'
        );

        $this->info("Kamu memilih: $choice");

        return 0;
    }
}
