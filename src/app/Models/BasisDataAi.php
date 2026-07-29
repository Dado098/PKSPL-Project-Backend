<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BasisDataAi extends Model
{
    protected $table = 'basis_data_ai';
    protected $primaryKey = 'id_basis';
    protected $fillable = ['nama_basis', 'versi', 'deskripsi', 'path_file', 'status', 'jenis_basis', 'model_embedding', 'jumlah_dokumen'];

    protected function casts(): array
    {
        return ['jumlah_dokumen' => 'integer'];
    }

    public function getRouteKeyName(): string
    {
        return 'id_basis';
    }
}
