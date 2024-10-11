<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ReverbEnvGenerate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reverb:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup Reverb environment variables in .env file with auto-generated values';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $envFile = base_path('.env');

        if (!File::exists($envFile)) {
            $this->error('.env file not found!');
            return;
        }

        $envContents = File::get($envFile);

        $variables = [
            'REVERB_APP_ID' => $this->generateAppId(),
            'REVERB_APP_KEY' => $this->generateKey(),
            'REVERB_APP_SECRET' => $this->generateSecret()
        ];

        foreach ($variables as $variable => $value) {
            $pattern = "/^{$variable}=.*$/m";
            if (preg_match($pattern, $envContents)) {
                $envContents = preg_replace($pattern, "{$variable}={$value}", $envContents);
            } else {
                $envContents .= "\n{$variable}={$value}";
            }
        }

        File::put($envFile, $envContents);

        $this->info('Reverb environment variables have been updated successfully with auto-generated values!');
    }

    private function generateAppId()
    {
        return mt_rand(100000, 999999);
    }

    private function generateKey()
    {
        return Str::lower(Str::random(20));
    }

    private function generateSecret()
    {
        return Str::lower(Str::random(20));
    }
}
