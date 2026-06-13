<?php

namespace App\Providers;

use App\Database\Connectors\NeonPostgresConnector;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Permite conectar Neon desde clientes PostgreSQL antiguos enviando DB_ENDPOINT.
        $this->app->bind('db.connector.pgsql', fn () => new NeonPostgresConnector());
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // En Render todas las URL publicas deben generarse con HTTPS.
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
