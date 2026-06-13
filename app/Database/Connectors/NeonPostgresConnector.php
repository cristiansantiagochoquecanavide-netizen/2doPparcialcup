<?php

namespace App\Database\Connectors;

use Illuminate\Database\Connectors\PostgresConnector;

/**
 * Conector PostgreSQL compatible con Neon para clientes libpq sin soporte SNI.
 */
class NeonPostgresConnector extends PostgresConnector
{
    protected function getDsn(array $config)
    {
        $dsn = parent::getDsn($config);

        if (!empty($config['endpoint'])) {
            $endpoint = str_replace("'", "\\'", $config['endpoint']);
            $dsn .= ";options='endpoint={$endpoint}'";
        }

        return $dsn;
    }
}
