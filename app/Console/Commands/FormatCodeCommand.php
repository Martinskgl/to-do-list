<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FormatCodeCommand extends Command
{
    protected $signature = 'format';

    protected $description = 'Executa formatação e lint do código';

    public function handle()
    {
        $this->info('🎨 Formatando código...');

        $this->line('📝 Laravel Pint...');
        exec('./vendor/bin/pint 2>&1', $output, $return);
        $this->info($return === 0 ? '✅ Pint OK' : '❌ Pint Error');

        exec('./vendor/bin/php-cs-fixer fix 2>&1', $output, $return);
        $this->info($return === 0 ? '✅ CS Fixer OK' : '❌ CS Fixer Error');
    }

    private function getSailCommand($command)
    {
        if (file_exists('./vendor/bin/sail')) {
            return "./vendor/bin/sail {$command}";
        }

        return "docker-compose exec laravel.test {$command}";
    }
}