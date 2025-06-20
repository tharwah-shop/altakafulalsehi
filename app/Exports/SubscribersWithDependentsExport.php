<?php

namespace App\Exports;

use App\Models\Subscriber;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class SubscribersWithDependentsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithColumnFormatting
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
        $query = Subscriber::with(['package', 'city.region', 'dependents', 'creator', 'payments']);

        // تطبيق الفلاتر
        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('card_number', 'like', "%{$search}%")
                  ->orWhere('id_number', 'like', "%{$search}%");
            });
        }

        if (!empty($this->filters['nationality'])) {
            $query->where('nationality', $this->filters['nationality']);
        }

        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        if (!empty($this->filters['package_id'])) {
            $query->where('package_id', $this->filters['package_id']);
        }

        if (!empty($this->filters['city_id'])) {
            $query->where('city_id', $this->filters['city_id']);
        }

        $subscribers = $query->latest()->get();
        
        // تحويل البيانات لتشمل صف منفصل لكل تابع
        $result = collect();
        
        foreach ($subscribers as $subscriber) {
            if ($subscriber->dependents->count() > 0) {
                foreach ($subscriber->dependents as $dependent) {
                    $result->push((object)[
                        'subscriber' => $subscriber,
                        'dependent' => $dependent,
                        'type' => 'dependent'
                    ]);
                }
            } else {
                $result->push((object)[
                    'subscriber' => $subscriber,
                    'dependent' => null,
                    'type' => 'subscriber'
                ]);
            }
        }
        
        return $result;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'نوع السجل',
            'ID المشترك',
            'اسم المشترك',
            'رقم جوال المشترك',
            'بريد المشترك',
            'مدينة المشترك',
            'منطقة المشترك',
            'جنسية المشترك',
            'رقم هوية المشترك',
            'تاريخ بداية الاشتراك',
            'تاريخ نهاية الاشتراك',
            'رقم البطاقة',
            'الباقة',
            'سعر البطاقة',
            'المبلغ الإجمالي',
            'حالة المشترك',
            'نسبة الخصم %',
            'مبلغ الخصم',
            'ID التابع',
            'اسم التابع',
            'جنسية التابع',
            'رقم هوية التابع',
            'سعر التابع',
            'ملاحظات التابع',
            'ملاحظات المشترك',
            'منشئ بواسطة',
            'تاريخ إنشاء المشترك',
        ];
    }

    /**
     * @param object $row
     * @return array
     */
    public function map($row): array
    {
        $subscriber = $row->subscriber;
        $dependent = $row->dependent;
        
        return [
            $row->type === 'dependent' ? 'تابع' : 'مشترك أساسي',
            $subscriber->id,
            $subscriber->name,
            $subscriber->phone,
            $subscriber->email,
            $subscriber->city ? $subscriber->city->name : '',
            $subscriber->city && $subscriber->city->region ? $subscriber->city->region->name : '',
            $subscriber->nationality,
            $subscriber->id_number,
            $subscriber->start_date ? $subscriber->start_date->format('Y-m-d') : '',
            $subscriber->end_date ? $subscriber->end_date->format('Y-m-d') : '',
            $subscriber->card_number,
            $subscriber->package ? $subscriber->package->name : '',
            $subscriber->card_price,
            $subscriber->total_amount,
            $subscriber->status,
            $subscriber->discount_percentage,
            $subscriber->discount_amount,
            $dependent ? $dependent->id : '',
            $dependent ? $dependent->name : '',
            $dependent ? $dependent->nationality : '',
            $dependent ? $dependent->id_number : '',
            $dependent ? $dependent->dependent_price : '',
            $dependent ? $dependent->notes : '',
            $subscriber->notes,
            $subscriber->creator ? $subscriber->creator->name : '',
            $subscriber->created_at ? $subscriber->created_at->format('Y-m-d H:i:s') : '',
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
                        'argb' => 'FF6F42C1',
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
            'D' => NumberFormat::FORMAT_TEXT, // رقم جوال المشترك
            'I' => NumberFormat::FORMAT_TEXT, // رقم هوية المشترك
            'J' => NumberFormat::FORMAT_DATE_YYYYMMDD, // تاريخ بداية الاشتراك
            'K' => NumberFormat::FORMAT_DATE_YYYYMMDD, // تاريخ نهاية الاشتراك
            'L' => NumberFormat::FORMAT_TEXT, // رقم البطاقة
            'N' => NumberFormat::FORMAT_NUMBER_00, // سعر البطاقة
            'O' => NumberFormat::FORMAT_NUMBER_00, // المبلغ الإجمالي
            'Q' => NumberFormat::FORMAT_PERCENTAGE_00, // نسبة الخصم
            'R' => NumberFormat::FORMAT_NUMBER_00, // مبلغ الخصم
            'V' => NumberFormat::FORMAT_TEXT, // رقم هوية التابع
            'W' => NumberFormat::FORMAT_NUMBER_00, // سعر التابع
        ];
    }
}
