<?php

namespace App\Exports;

use App\Models\Product;
use App\Models\StockIn;
use App\Models\StockOut;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class InventoryReportExport implements FromArray, WithEvents, WithTitle
{
    private ?string $startDate;
    private ?string $endDate;

    private int $titleRow;
    private int $periodRow;

    private int $summaryTitleRow;
    private int $summaryHeaderRow;
    private int $summaryStartRow;
    private int $summaryEndRow;

    private int $salesTitleRow;
    private int $salesHeaderRow;
    private int $salesStartRow;
    private int $salesEndRow;
    private int $salesTotalRow;

    private int $inventoryTitleRow;
    private int $inventoryHeaderRow;
    private int $inventoryStartRow;
    private int $inventoryEndRow;

    private int $lastRow;

    private bool $hasSales = false;
    private bool $hasProducts = false;

    private array $summaryCurrencyRows = [];

    public function __construct(
        ?string $startDate = null,
        ?string $endDate = null
    ) {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function array(): array
    {
        $rows = [];

        $addRow = function (array $row) use (&$rows): int {
            $rows[] = $row;

            return count($rows);
        };

        /*
        |--------------------------------------------------------------------------
        | Mengambil data laporan
        |--------------------------------------------------------------------------
        */

        $totalProducts = Product::count();
        $totalCurrentStock = Product::sum('stock');

        $stockInQuery = StockIn::query();
        $stockOutQuery = StockOut::query();

        $this->applyDateFilter($stockInQuery);
        $this->applyDateFilter($stockOutQuery);

        $totalStockIn = (int) $stockInQuery->sum('quantity');
        $totalStockOut = (int) $stockOutQuery->sum('quantity');

        $salesQuery = StockOut::with([
            'product',
            'customer',
        ]);

        $this->applyDateFilter($salesQuery);

        $sales = $salesQuery
            ->orderBy('transaction_date')
            ->orderBy('id')
            ->get();

        $totalSales = (float) $sales->sum('subtotal');

        $totalCapital = (float) $sales->sum(function ($sale) {
            return (float) $sale->unit_purchase_price
                * (int) $sale->quantity;
        });

        $totalProfit = (float) $sales->sum('total_profit');

        $products = Product::withSum([
            'stockIns as total_stock_in' => function ($query) {
                $this->applyDateFilter($query);
            },
        ], 'quantity')
            ->withSum([
                'stockOuts as total_stock_out' => function ($query) {
                    $this->applyDateFilter($query);
                },
            ], 'quantity')
            ->orderBy('product_name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Judul laporan
        |--------------------------------------------------------------------------
        */

        $this->titleRow = $addRow([
            'LAPORAN INVENTARIS DULMAR SATELLITE STORE',
        ]);

        $this->periodRow = $addRow([
            'Periode Laporan',
            $this->getPeriodText(),
        ]);

        $addRow([
            'Tanggal Ekspor',
            now()->format('d-m-Y H:i'),
        ]);

        $addRow(['']);

        /*
        |--------------------------------------------------------------------------
        | Ringkasan laporan
        |--------------------------------------------------------------------------
        */

        $this->summaryTitleRow = $addRow([
            'RINGKASAN LAPORAN',
        ]);

        $this->summaryHeaderRow = $addRow([
            'Keterangan',
            'Nilai',
        ]);

        $this->summaryStartRow = $addRow([
            'Total Produk',
            (int) $totalProducts,
        ]);

        $addRow([
            'Stok Saat Ini',
            (int) $totalCurrentStock,
        ]);

        $addRow([
            'Total Stok Masuk',
            $totalStockIn,
        ]);

        $addRow([
            'Total Stok Keluar',
            $totalStockOut,
        ]);

        $subtotalRow = $addRow([
            'Subtotal Penjualan',
            $totalSales,
        ]);

        $capitalRow = $addRow([
            'Total Modal',
            $totalCapital,
        ]);

        $profitRow = $addRow([
            'Total Keuntungan',
            $totalProfit,
        ]);

        $this->summaryCurrencyRows = [
            $subtotalRow,
            $capitalRow,
            $profitRow,
        ];

        $this->summaryEndRow = count($rows);

        $addRow(['']);

        /*
        |--------------------------------------------------------------------------
        | Laporan penjualan dan keuntungan
        |--------------------------------------------------------------------------
        */

        $this->salesTitleRow = $addRow([
            'LAPORAN PENJUALAN DAN KEUNTUNGAN',
        ]);

        $this->salesHeaderRow = $addRow([
            'No',
            'Tanggal Jual',
            'Nama Produk',
            'Pelanggan',
            'Jumlah Keluar',
            'Harga Beli',
            'Harga Jual',
            'Total Jual',
            'Laba per Unit',
            'Total Laba',
        ]);

        $this->salesStartRow = count($rows) + 1;
        $this->hasSales = $sales->isNotEmpty();

        if ($this->hasSales) {
            foreach ($sales as $index => $sale) {
                $hargaBeli = (float) $sale->unit_purchase_price;
                $hargaJual = (float) $sale->unit_selling_price;
                $jumlah = (int) $sale->quantity;

                $addRow([
                    $index + 1,
                    $sale->transaction_date->format('d-m-Y'),
                    $sale->product?->product_name
                        ?? 'Produk telah dihapus',
                    $sale->customer?->customer_name ?? '-',
                    $jumlah,
                    $hargaBeli,
                    $hargaJual,
                    (float) $sale->subtotal,
                    $hargaJual - $hargaBeli,
                    (float) $sale->total_profit,
                ]);
            }

            $this->salesEndRow = count($rows);
        } else {
            $this->salesEndRow = $addRow([
                'Belum ada transaksi penjualan pada periode ini.',
            ]);
        }

        $this->salesTotalRow = $addRow([
            '',
            '',
            '',
            '',
            'TOTAL',
            '',
            '',
            $totalSales,
            '',
            $totalProfit,
        ]);

        $addRow(['']);

        /*
        |--------------------------------------------------------------------------
        | Ringkasan inventaris produk
        |--------------------------------------------------------------------------
        */

        $this->inventoryTitleRow = $addRow([
            'RINGKASAN INVENTARIS PRODUK',
        ]);

        $this->inventoryHeaderRow = $addRow([
            'No',
            'Nama Produk',
            'Kategori',
            'Harga Beli',
            'Harga Jual',
            'Total Stok Masuk',
            'Total Stok Keluar',
            'Stok Saat Ini',
            'Nilai Stok',
        ]);

        $this->inventoryStartRow = count($rows) + 1;
        $this->hasProducts = $products->isNotEmpty();

        if ($this->hasProducts) {
            foreach ($products as $index => $product) {
                $hargaBeli = (float) $product->purchase_price;
                $stokSaatIni = (int) $product->stock;

                $addRow([
                    $index + 1,
                    $product->product_name,
                    $product->category,
                    $hargaBeli,
                    (float) $product->selling_price,
                    (int) ($product->total_stock_in ?? 0),
                    (int) ($product->total_stock_out ?? 0),
                    $stokSaatIni,
                    $hargaBeli * $stokSaatIni,
                ]);
            }

            $this->inventoryEndRow = count($rows);
        } else {
            $this->inventoryEndRow = $addRow([
                'Belum ada produk.',
            ]);
        }

        $this->lastRow = count($rows);

        return $rows;
    }

    public function title(): string
    {
        return 'Laporan Inventaris';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                /*
                |--------------------------------------------------------------------------
                | Pengaturan umum
                |--------------------------------------------------------------------------
                */

                $sheet->setShowGridlines(false);
                $sheet->getDefaultRowDimension()->setRowHeight(22);

                $sheet->getStyle("A1:J{$this->lastRow}")
                    ->getFont()
                    ->setName('Calibri')
                    ->setSize(11);

                $sheet->getStyle("A1:J{$this->lastRow}")
                    ->getAlignment()
                    ->setVertical(Alignment::VERTICAL_CENTER);

                /*
                |--------------------------------------------------------------------------
                | Lebar kolom
                |--------------------------------------------------------------------------
                */

                $sheet->getColumnDimension('A')->setWidth(7);
                $sheet->getColumnDimension('B')->setWidth(20);
                $sheet->getColumnDimension('C')->setWidth(32);
                $sheet->getColumnDimension('D')->setWidth(24);
                $sheet->getColumnDimension('E')->setWidth(17);
                $sheet->getColumnDimension('F')->setWidth(17);
                $sheet->getColumnDimension('G')->setWidth(18);
                $sheet->getColumnDimension('H')->setWidth(18);
                $sheet->getColumnDimension('I')->setWidth(18);
                $sheet->getColumnDimension('J')->setWidth(18);

                /*
                |--------------------------------------------------------------------------
                | Judul utama
                |--------------------------------------------------------------------------
                */

                $sheet->mergeCells(
                    "A{$this->titleRow}:J{$this->titleRow}"
                );

                $sheet->getStyle(
                    "A{$this->titleRow}:J{$this->titleRow}"
                )->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 18,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => 'solid',
                        'startColor' => ['rgb' => '1F4E78'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $sheet->getRowDimension($this->titleRow)
                    ->setRowHeight(34);

                /*
                |--------------------------------------------------------------------------
                | Periode dan tanggal ekspor
                |--------------------------------------------------------------------------
                */

                $sheet->mergeCells(
                    "B{$this->periodRow}:J{$this->periodRow}"
                );

                $sheet->mergeCells(
                    'B' . ($this->periodRow + 1)
                    . ':J' . ($this->periodRow + 1)
                );

                $sheet->getStyle(
                    "A{$this->periodRow}:J"
                    . ($this->periodRow + 1)
                )->applyFromArray([
                    'fill' => [
                        'fillType' => 'solid',
                        'startColor' => ['rgb' => 'D9EAF7'],
                    ],
                    'borders' => $this->thinBorders(),
                ]);

                $sheet->getStyle(
                    "A{$this->periodRow}:A"
                    . ($this->periodRow + 1)
                )->getFont()->setBold(true);

                /*
                |--------------------------------------------------------------------------
                | Judul setiap bagian
                |--------------------------------------------------------------------------
                */

                $sectionRows = [
                    $this->summaryTitleRow,
                    $this->salesTitleRow,
                    $this->inventoryTitleRow,
                ];

                foreach ($sectionRows as $row) {
                    $sheet->mergeCells("A{$row}:J{$row}");

                    $sheet->getStyle("A{$row}:J{$row}")
                        ->applyFromArray([
                            'font' => [
                                'bold' => true,
                                'size' => 13,
                                'color' => ['rgb' => 'FFFFFF'],
                            ],
                            'fill' => [
                                'fillType' => 'solid',
                                'startColor' => ['rgb' => '305496'],
                            ],
                            'alignment' => [
                                'horizontal' =>
                                    Alignment::HORIZONTAL_LEFT,
                            ],
                        ]);

                    $sheet->getRowDimension($row)->setRowHeight(27);
                }

                /*
                |--------------------------------------------------------------------------
                | Ringkasan laporan
                |--------------------------------------------------------------------------
                */

                $sheet->getStyle(
                    "A{$this->summaryHeaderRow}:B{$this->summaryHeaderRow}"
                )->applyFromArray($this->headerStyle('4472C4'));

                $sheet->getStyle(
                    "A{$this->summaryHeaderRow}:B{$this->summaryEndRow}"
                )->applyFromArray([
                    'borders' => $this->thinBorders(),
                ]);

                $sheet->getStyle(
                    "A{$this->summaryStartRow}:A{$this->summaryEndRow}"
                )->getFont()->setBold(true);

                $sheet->getStyle(
                    "B{$this->summaryStartRow}:B{$this->summaryEndRow}"
                )->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                $sheet->getStyle(
                    "B{$this->summaryStartRow}:B"
                    . ($this->summaryStartRow + 3)
                )->getNumberFormat()
                    ->setFormatCode('#,##0');

                foreach ($this->summaryCurrencyRows as $row) {
                    $sheet->getStyle("B{$row}")
                        ->getNumberFormat()
                        ->setFormatCode('$#,##0.00;[Red]-$#,##0.00');
                }

                /*
                |--------------------------------------------------------------------------
                | Tabel penjualan
                |--------------------------------------------------------------------------
                */

                $sheet->getStyle(
                    "A{$this->salesHeaderRow}:J{$this->salesHeaderRow}"
                )->applyFromArray($this->headerStyle('FFC000'));

                $sheet->getStyle(
                    "A{$this->salesHeaderRow}:J{$this->salesTotalRow}"
                )->applyFromArray([
                    'borders' => $this->thinBorders(),
                ]);

                $sheet->getStyle(
                    "A{$this->salesHeaderRow}:J{$this->salesTotalRow}"
                )->getAlignment()->setWrapText(true);

                if ($this->hasSales) {
                    $sheet->getStyle(
                        "E{$this->salesStartRow}:E{$this->salesEndRow}"
                    )->getNumberFormat()->setFormatCode('#,##0');

                    $sheet->getStyle(
                        "F{$this->salesStartRow}:J{$this->salesEndRow}"
                    )->getNumberFormat()
                        ->setFormatCode('$#,##0.00;[Red]-$#,##0.00');

                    $sheet->setAutoFilter(
                        "A{$this->salesHeaderRow}:J{$this->salesEndRow}"
                    );
                } else {
                    $sheet->mergeCells(
                        "A{$this->salesStartRow}:J{$this->salesStartRow}"
                    );

                    $sheet->getStyle(
                        "A{$this->salesStartRow}:J{$this->salesStartRow}"
                    )->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }

                $sheet->getStyle(
                    "A{$this->salesTotalRow}:J{$this->salesTotalRow}"
                )->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'fill' => [
                        'fillType' => 'solid',
                        'startColor' => ['rgb' => 'FFF2CC'],
                    ],
                    'borders' => $this->thinBorders(),
                ]);

                $sheet->getStyle(
                    "H{$this->salesTotalRow}"
                )->getNumberFormat()
                    ->setFormatCode('$#,##0.00');

                $sheet->getStyle(
                    "J{$this->salesTotalRow}"
                )->getNumberFormat()
                    ->setFormatCode('$#,##0.00');

                /*
                |--------------------------------------------------------------------------
                | Tabel inventaris
                |--------------------------------------------------------------------------
                */

                $sheet->getStyle(
                    "A{$this->inventoryHeaderRow}:I"
                    . $this->inventoryHeaderRow
                )->applyFromArray($this->headerStyle('70AD47'));

                $sheet->getStyle(
                    "A{$this->inventoryHeaderRow}:I"
                    . $this->inventoryEndRow
                )->applyFromArray([
                    'borders' => $this->thinBorders(),
                ]);

                $sheet->getStyle(
                    "A{$this->inventoryHeaderRow}:I"
                    . $this->inventoryEndRow
                )->getAlignment()->setWrapText(true);

                if ($this->hasProducts) {
                    $sheet->getStyle(
                        "D{$this->inventoryStartRow}:E"
                        . $this->inventoryEndRow
                    )->getNumberFormat()
                        ->setFormatCode('$#,##0.00;[Red]-$#,##0.00');

                    $sheet->getStyle(
                        "F{$this->inventoryStartRow}:H"
                        . $this->inventoryEndRow
                    )->getNumberFormat()->setFormatCode('#,##0');

                    $sheet->getStyle(
                        "I{$this->inventoryStartRow}:I"
                        . $this->inventoryEndRow
                    )->getNumberFormat()
                        ->setFormatCode('$#,##0.00;[Red]-$#,##0.00');
                } else {
                    $sheet->mergeCells(
                        "A{$this->inventoryStartRow}:I"
                        . $this->inventoryStartRow
                    );

                    $sheet->getStyle(
                        "A{$this->inventoryStartRow}:I"
                        . $this->inventoryStartRow
                    )->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }

                /*
                |--------------------------------------------------------------------------
                | Posisi angka dan isi tabel
                |--------------------------------------------------------------------------
                */

                $sheet->getStyle("A1:A{$this->lastRow}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->getStyle(
                    "E{$this->salesHeaderRow}:J{$this->salesTotalRow}"
                )->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_RIGHT);

               /*
|--------------------------------------------------------------------------
| Tampilan dan pengaturan cetak
|--------------------------------------------------------------------------
*/

// Jangan membekukan banyak baris agar tabel bagian bawah terlihat.
$sheet->unfreezePane();

// Perkecil tampilan awal agar lebih banyak bagian laporan terlihat.
$sheet->getSheetView()->setZoomScale(80);

// Tampilkan laporan dari sel paling atas.
$sheet->setSelectedCell('A1');

// Posisi halaman horizontal di tengah ketika dicetak.
$sheet->getPageSetup()
    ->setHorizontalCentered(true);

// Gunakan posisi kertas mendatar.
$sheet->getPageSetup()
    ->setOrientation(
        PageSetup::ORIENTATION_LANDSCAPE
    );

// Gunakan ukuran kertas A4.
$sheet->getPageSetup()
    ->setPaperSize(PageSetup::PAPERSIZE_A4);

// Sesuaikan seluruh kolom agar masuk ke satu halaman.
$sheet->getPageSetup()
    ->setFitToPage(true);

$sheet->getPageSetup()
    ->setFitToWidth(1);

$sheet->getPageSetup()
    ->setFitToHeight(0);

// Tentukan area yang akan dicetak.
$sheet->getPageSetup()
    ->setPrintArea("A1:J{$this->lastRow}");

// Atur margin halaman.
$sheet->getPageMargins()->setTop(0.5);
$sheet->getPageMargins()->setBottom(0.5);
$sheet->getPageMargins()->setLeft(0.3);
$sheet->getPageMargins()->setRight(0.3);
$sheet->getPageMargins()->setHeader(0.2);
$sheet->getPageMargins()->setFooter(0.2);
            },
        ];
    }

    private function applyDateFilter($query): void
    {
        if ($this->startDate) {
            $query->whereDate(
                'transaction_date',
                '>=',
                $this->startDate
            );
        }

        if ($this->endDate) {
            $query->whereDate(
                'transaction_date',
                '<=',
                $this->endDate
            );
        }
    }

    private function getPeriodText(): string
    {
        if ($this->startDate && $this->endDate) {
            return date('d-m-Y', strtotime($this->startDate))
                . ' sampai '
                . date('d-m-Y', strtotime($this->endDate));
        }

        if ($this->startDate) {
            return 'Mulai '
                . date('d-m-Y', strtotime($this->startDate))
                . ' sampai hari ini';
        }

        if ($this->endDate) {
            return 'Awal sampai '
                . date('d-m-Y', strtotime($this->endDate));
        }

        return 'Awal sampai hari ini';
    }

    private function headerStyle(string $color): array
    {
        return [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => 'solid',
                'startColor' => ['rgb' => $color],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'borders' => $this->thinBorders(),
        ];
    }

    private function thinBorders(): array
    {
        return [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['rgb' => 'B7C9D6'],
            ],
        ];
    }
}