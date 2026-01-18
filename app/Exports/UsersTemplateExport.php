<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;

class UsersTemplateExport implements WithHeadings, WithTitle, WithEvents
{
    public function title(): string
    {
        return 'Nhập liệu User';
    }

    public function headings(): array
    {
        return [
            'Họ và tên',
            'Email (Duy nhất)',
            'Mật khẩu (Bỏ trống = 12345678)',
            'Vai trò (Chọn từ danh sách)',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = 1000;

                $sheet->getStyle('A1:D1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 12,
                        'color' => ['argb' => 'FFFFFFFF'],
                        'name' => 'Arial',
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FF2563EB'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'FFFFFFFF'],
                        ],
                    ],
                ]);

                $sheet->getRowDimension(1)->setRowHeight(30);

                $sheet->freezePane('A2');

                $sheet->getColumnDimension('A')->setWidth(30);
                $sheet->getColumnDimension('B')->setWidth(35);
                $sheet->getColumnDimension('C')->setWidth(30);
                $sheet->getColumnDimension('D')->setWidth(30);

                $validation = $sheet->getCell('D2')->getDataValidation();
                $validation->setType(DataValidation::TYPE_LIST);
                $validation->setErrorStyle(DataValidation::STYLE_INFORMATION);
                $validation->setAllowBlank(false);
                $validation->setShowInputMessage(true);
                $validation->setShowErrorMessage(true);
                $validation->setShowDropDown(true);
                $validation->setErrorTitle('Lỗi nhập liệu');
                $validation->setError('Vui lòng chọn vai trò từ danh sách xổ xuống.');
                $validation->setPromptTitle('Chọn vai trò');
                $validation->setPrompt('Nhấp vào mũi tên để chọn.');
                
                $validation->setFormula1('"Sinh viên,Giảng viên,Quản trị viên"');

                for ($i = 2; $i <= $lastRow; $i++) {
                    $sheet->getCell("D$i")->setDataValidation(clone $validation);
                }

                $sheet->getStyle("A2:D$lastRow")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'FFE2E8F0'],
                        ],
                    ],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);
                
                $sheet->getStyle("D2:D$lastRow")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}