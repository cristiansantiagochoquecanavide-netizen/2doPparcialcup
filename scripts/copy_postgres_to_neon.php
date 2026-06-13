<?php

declare(strict_types=1);

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

$neonUrl = getenv('NEON_URL');
if (!$neonUrl) {
    fwrite(STDERR, "NEON_URL no esta configurada.\n");
    exit(1);
}

$parts = parse_url($neonUrl);
if ($parts === false || !isset($parts['host'], $parts['user'], $parts['pass'], $parts['path'])) {
    fwrite(STDERR, "NEON_URL no tiene un formato valido.\n");
    exit(1);
}

parse_str($parts['query'] ?? '', $query);
$sslmode = $query['sslmode'] ?? 'require';
$database = ltrim($parts['path'], '/');
$port = $parts['port'] ?? 5432;
$endpoint = explode('.', $parts['host'])[0];
$dsn = "pgsql:host={$parts['host']};port={$port};dbname={$database};sslmode={$sslmode};options='endpoint={$endpoint}'";

$target = new PDO(
    $dsn,
    urldecode($parts['user']),
    urldecode($parts['pass']),
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]
);

$targetTables = (int) $target->query(
    "select count(*) from information_schema.tables where table_schema = 'public'"
)->fetchColumn();

if (in_array('--check', $argv, true)) {
    echo "Conexion Neon correcta. Tablas publicas: {$targetTables}\n";
    exit(0);
}

$tables = [
    'roles',
    'permisos',
    'rol_permiso',
    'usuarios',
    'bitacora',
    'lote_carga_usuario',
    'usuario_importado',
    'gestion_academica',
    'carreras',
    'cupo_carrera_gestion',
    'postulantes',
    'requisitos',
    'postulante_requisito',
    'pagos',
    'inscripciones',
    'grupos',
    'grupo_estudiante',
    'docentes',
    'materias',
    'aulas',
    'carga_horaria',
    'asistencia_clase',
    'asistencia_detalle',
    'evaluacion_config',
    'notas',
    'resultado_admision',
    'password_resets',
    'sessions',
];

$source = DB::connection()->getPdo();
$source->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

$quoteIdentifier = static fn (string $identifier): string => '"' . str_replace('"', '""', $identifier) . '"';

$target->beginTransaction();

try {
    foreach (array_reverse($tables) as $table) {
        $exists = $target->prepare(
            "select exists(select 1 from information_schema.tables where table_schema = 'public' and table_name = ?)"
        );
        $exists->execute([$table]);

        if ($exists->fetchColumn()) {
            $target->exec('TRUNCATE TABLE ' . $quoteIdentifier($table) . ' RESTART IDENTITY CASCADE');
        }
    }

    foreach ($tables as $table) {
        $sourceExists = $source->prepare(
            "select exists(select 1 from information_schema.tables where table_schema = 'public' and table_name = ?)"
        );
        $sourceExists->execute([$table]);

        if (!$sourceExists->fetchColumn()) {
            echo "{$table}: no existe localmente, omitida.\n";
            continue;
        }

        $rows = $source->query('SELECT * FROM ' . $quoteIdentifier($table))->fetchAll();
        if ($rows === []) {
            echo "{$table}: 0 filas.\n";
            continue;
        }

        $columns = array_keys($rows[0]);
        $columnList = implode(', ', array_map($quoteIdentifier, $columns));
        $placeholders = implode(', ', array_fill(0, count($columns), '?'));
        $insert = $target->prepare(
            'INSERT INTO ' . $quoteIdentifier($table) . " ({$columnList}) VALUES ({$placeholders})"
        );

        foreach ($rows as $row) {
            $insert->execute(array_values($row));
        }

        echo "{$table}: " . count($rows) . " filas copiadas.\n";
    }

    $target->commit();
} catch (Throwable $exception) {
    $target->rollBack();
    throw $exception;
}

$serialColumns = $target->query(
    "select table_name, column_name
     from information_schema.columns
     where table_schema = 'public'
       and column_default like 'nextval(%'"
)->fetchAll();

foreach ($serialColumns as $serialColumn) {
    $table = $serialColumn['table_name'];
    $column = $serialColumn['column_name'];
    $sequenceStatement = $target->prepare("select pg_get_serial_sequence(?, ?)");
    $sequenceStatement->execute([$table, $column]);
    $sequence = $sequenceStatement->fetchColumn();

    if (!$sequence) {
        continue;
    }

    $max = (int) $target->query(
        'SELECT COALESCE(MAX(' . $quoteIdentifier($column) . '), 0) FROM ' . $quoteIdentifier($table)
    )->fetchColumn();

    $setValue = max($max, 1);
    $isCalled = $max > 0 ? 'true' : 'false';
    $target->exec("SELECT setval(" . $target->quote($sequence) . ", {$setValue}, {$isCalled})");
}

echo "Copia completada y secuencias ajustadas.\n";
