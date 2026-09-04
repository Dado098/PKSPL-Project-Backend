<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Proyek;
use App\Http\Controllers\Api\V1\ProyekController;

$proyeks = Proyek::all();
foreach ($proyeks as $p) {
    if (empty($p->kode_proyek) || str_starts_with($p->kode_proyek, 'PROJ-')) {
        $old = $p->kode_proyek;
        $new = ProyekController::generateNextKodeProyek();
        $p->kode_proyek = $new;
        $p->save();
        echo "Updated ID {$p->id_proyek} ('{$p->nama_proyek}') from '{$old}' to '{$new}'\n";
    }
}

echo "Migration finished.\n";
