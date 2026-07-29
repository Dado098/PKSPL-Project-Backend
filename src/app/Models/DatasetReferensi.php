<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DatasetReferensi extends Model
{
    protected $table = 'dataset_referensi';
    protected $primaryKey = 'id_dataset';
    protected $fillable = ['nama_dataset', 'kategori', 'tahun', 'file_dataset', 'tipe_file', 'sumber', 'deskripsi', 'status'];

    protected function casts(): array
    {
        return ['tahun' => 'integer'];
    }

    public function getRouteKeyName(): string
    {
        return 'id_dataset';
    }
}
