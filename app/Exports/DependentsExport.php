<?php

namespace App\Exports;

use App\Models\Dependent;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class DependentsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithColumnFormatting
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Dependent::with(['subscriber.package', 'subscriber.city.region']);

        // تطبيق الفلاتر
        if (!empty($this->filters['subscriber_id'])) {
            $query->where('subscriber_id', $this->filters['subscriber_id']);
        }

        if (!empty($this->filters['nationality'])) {
            $query->where('nationality', $this->filters['nationality']);
        }

        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('id_number', 'like', "%{$search}%")
                  ->orWhereHas('subscriber', function($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%")
                        ->orWhere('card_number', 'like', "%{$search}%");
                  });
            });
        }

        if (!empty($this->filters['subscriber_status'])) {
            $query->whereHas('subscriber', function($q) {
                $q->where('status', $this->filters['subscriber_status']);
            });
        }

        return $query->latest()->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'اسم التابع',
            'جنسية التابع',
            'رقم هوية التابع',
            'سعر التابع',
            'ملاحظات التابع',
            'اسم المشترك الأساسي',
            'رقم جوال المشترك',
            'رقم بطاقة المشترك',
            'حالة المشترك',
            'باقة المشترك',
            'مدينة المشترك',
            'منطقة المشترك',
            'تاريخ بداية الاشتراك',
            'تاريخ نهاية الاشتراك',
            'تاريخ إضافة التابع',
            'تاريخ تحديث التابع',
        ];
    }

    /**
     * @param Dependent $dependent
     * @return array
     */
    public function map($dependent): array
    {
        return [
            $dependent->id,
            $dependent->name,
            $dependent->nationality,
            $dependent->id_number,
            $dependent->dependent_price,
            $dependent->notes,
            $dependent->subscriber ? $dependent->subscriber->name : '',
            $dependent->subscriber ? $dependent->subscriber->phone : '',
            $dependent->subscriber ? $dependent->subscriber->card_number : '',
            $dependent->subscriber ? $dependent->subscriber->status : '',
            $dependent->subscriber && $dependent->subscriber->package ? $dependent->subscriber->package->name : '',
            $dependent->subscriber && $dependent->subscriber->city ? $dependent->subscriber->city->name : '',
            $dependent->subscriber && $dependent->subscriber->city && $dependent->subscriber->city->region ? $dependent->subscriber->city->region->name : '',
            $dependent->subscriber && $dependent->subscriber->start_date ? $dependent->subscriber->start_date->format('Y-m-d') : '',
            $dependent->subscriber && $dependent->subscriber->end_date ? $dependent->subscriber->end_date->format('Y-m-d') : '',
            $dependent->created_at ? $dependent->created_at->format('Y-m-d H:i:s') : '',
            $dependent->updated_at ? $dependent->updated_at->format('Y-m-d H:i:s') : '',
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // تنسيق الرأس
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => [
                        'argb' => 'FFFFFFFF',
                    ],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => 'FF28A745',
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_TEXT, // رقم هوية التابع
            'E' => NumberFormat::FORMAT_NUMBER_00, // سعر التابع
            'H' => NumberFormat::FORMAT_TEXT, // رقم جوال المشترك
            'I' => NumberFormat::FORMAT_TEXT, // رقم بطاقة المشترك
            'N' => NumberFormat::FORMAT_DATE_YYYYMMDD, // تاريخ بداية الاشتراك
            'O' => NumberFormat::FORMAT_DATE_YYYYMMDD, // تاريخ نهاية الاشتراك
        ];
    }
}
