<?php

namespace App\Services\Valuation;

use App\Services\Valuation\FormulaHelper;
use Illuminate\Support\Collection;

class RegulatingCalculator
{
    public function calculate(Collection $records): array
    {
        $detail = [];
        $subtotal = '0';

        foreach ($records as $record) {
            $value = $this->calculateRecord($record);
            $subtotal = FormulaHelper::add($subtotal, $value);
            $detail[] = [
                'id' => $record->id_regulating ?? null,
                'kategori_tev' => $record->kategori_tev ?? 'IUV',
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
        $harga = $record->harga ?? null;
        $luas = $record->luas ?? null;

        if ($harga !== null && $luas !== null) {
            return FormulaHelper::safeMultiply(
                $record->nilai_indikator ?? 0,
                $harga,
                $luas,
            );
        }

        return FormulaHelper::normalizeNumber($record->nilai ?? 0);
    }
}
