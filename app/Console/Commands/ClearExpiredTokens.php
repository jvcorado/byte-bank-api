<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Laravel\Sanctum\PersonalAccessToken;

class ClearExpiredTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tokens:clear-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpa todos os tokens expirados do banco de dados';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Limpando tokens expirados...');

        $expiredTokens = PersonalAccessToken::where('expires_at', '<', now())->get();
        
        if ($expiredTokens->isEmpty()) {
            $this->info('Nenhum token expirado encontrado.');
            return;
        }

        $count = $expiredTokens->count();
        $this->info("Encontrados {$count} tokens expirados.");

        foreach ($expiredTokens as $token) {
            $this->line("Removendo token: {$token->name} (ID: {$token->id})");
            $token->delete();
        }

        $this->info('Tokens expirados removidos com sucesso!');
    }
} 