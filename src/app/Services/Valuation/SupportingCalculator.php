<?php

namespace App\Services\Valuation;

use App\Services\Valuation\FormulaHelper;
use Illuminate\Support\Collection;

class SupportingCalculator
{
    public function calculate(Collection $records): array
    {
        $detail = [];
        $subtotal = '0';

        foreach ($records as $record) {
            $value = $this->calculateRecord($record);
            $subtotal = FormulaHelper::add($subtotal, $value);
            $detail[] = [
                'id' => $record->id_supporting ?? null,
                'kategori_tev' => $record->kategori_tev ?? 'OV',
                'nilai' => $value,
            ];
        }

        return [
            'subtotal' => $subtotal,
            'detail' => $detail,
        ];
    }

    public function calculateRecord(object $record): string
    {
        if ($record->referensi !== null && $record->referensi !== '') {
            return FormulaHelper::normalizeNumber($record->referensi);
        }

        return FormulaHelper::normalizeNumber($record->nilai ?? 0);
    }
}
