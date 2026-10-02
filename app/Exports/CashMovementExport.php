<?php

namespace App\Exports;

use App\Models\CashMovement;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CashMovementExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    ShouldAutoSize,
    WithStyles
{
    private int $number = 0;

    /**
     * Ambil seluruh histori pergerakan uang.
     */
    public function collection(): Collection
    {
        return CashMovement::query()
            ->with('creator')
            ->latest()
            ->get();
    }

    /**
     * Header Excel.
     */
    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Aktivitas',
            'Jumlah',
            'Dari',
            'Ke',
            'Bank',
            'Bukti',
            'Keterangan',
            'Dibuat Oleh',
        ];
    }

    /**
     * Mapping data setiap baris.
     */
    public function map($movement): array
    {
        $this->number++;

        return [
            $this->number,

            $movement->created_at
                ? $movement->created_at->format('d-m-Y H:i')
                : '-',

            $movement->movement_label ?? '-',

            (float) $movement->amount,

            $this->accountLabel(
                $movement->from_account
            ),

            $this->accountLabel(
                $movement->to_account
            ),

            $movement->bank_name ?: '-',

            $movement->proof ?: '-',

            $movement->notes ?: '-',

            $movement->creator?->name ?? '-',
        ];
    }

    /**
     * Ubah kode account menjadi nama yang mudah dibaca.
     */
    private function accountLabel(?string $account): string
    {
        return match ($account) {
            'admin' => 'Admin',
            'bank' => 'Bank',
            'mosan' => 'Mosan',
            default => '-',
        };
    }

    /**
     * Styling Excel.
     */
    public function styles(Worksheet $sheet): array
    {
        $sheet->freezePane('A2');

        $sheet->getStyle('A1:J1')
            ->getFont()
            ->setBold(true);

        $sheet->getStyle('A1:J1')
            ->getAlignment()
            ->setHorizontal('center');

        $sheet->getStyle('D:D')
            ->getNumberFormat()
            ->setFormatCode('$#,##0.00');

        return [
            1 => [
                'font' => [
                    'bold' => true,
                ],
            ],
        ];
    }
}