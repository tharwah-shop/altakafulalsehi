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

class SubscribersExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithColumnFormatting
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

        if (!empty($this->filters['start_date'])) {
            $query->whereDate('start_date', '>=', $this->filters['start_date']);
        }

        if (!empty($this->filters['end_date'])) {
            $query->whereDate('end_date', '<=', $this->filters['end_date']);
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
            'الاسم',
            'رقم الجوال',
            'البريد الإلكتروني',
            'المدينة',
            'المنطقة',
            'الجنسية',
            'رقم الهوية/الإقامة',
            'تاريخ البداية',
            'تاريخ النهاية',
            'رقم البطاقة',
            'الباقة',
            'سعر البطاقة',
            'المبلغ الإجمالي',
            'عدد التابعين',
            'الحالة',
            'نسبة الخصم %',
            'مبلغ الخصم',
            'الملاحظات',
            'منشئ بواسطة',
            'تاريخ الإنشاء',
            'تاريخ التحديث',
            'أسماء التابعين',
            'جنسيات التابعين',
            'أرقام هوية التابعين',
            'أسعار التابعين',
            'إجمالي المدفوعات',
            'حالة الدفع',
        ];
    }

    /**
     * @param Subscriber $subscriber
     * @return array
     */
    public function map($subscriber): array
    {
        // جمع بيانات التابعين
        $dependentsNames = $subscriber->dependents->pluck('name')->implode(', ');
        $dependentsNationalities = $subscriber->dependents->pluck('nationality')->implode(', ');
        $dependentsIdNumbers = $subscriber->dependents->pluck('id_number')->implode(', ');
        $dependentsPrices = $subscriber->dependents->pluck('dependent_price')->implode(', ');

        // حساب إجمالي المدفوعات
        $totalPayments = $subscriber->payments->where('status', 'confirmed')->sum('amount');
        $paymentStatus = $subscriber->payments->where('status', 'confirmed')->count() > 0 ? 'مدفوع' : 'غير مدفوع';

        return [
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
            $subscriber->dependents_count,
            $subscriber->status,
            $subscriber->discount_percentage,
            $subscriber->discount_amount,
            $subscriber->notes,
            $subscriber->creator ? $subscriber->creator->name : '',
            $subscriber->created_at ? $subscriber->created_at->format('Y-m-d H:i:s') : '',
            $subscriber->updated_at ? $subscriber->updated_at->format('Y-m-d H:i:s') : '',
            $dependentsNames,
            $dependentsNationalities,
            $dependentsIdNumbers,
            $dependentsPrices,
            $totalPayments,
            $paymentStatus,
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
                        'argb' => 'FF4472C4',
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
            'C' => NumberFormat::FORMAT_TEXT, // رقم الجوال
            'H' => NumberFormat::FORMAT_TEXT, // رقم الهوية
            'I' => NumberFormat::FORMAT_DATE_YYYYMMDD, // تاريخ البداية
            'J' => NumberFormat::FORMAT_DATE_YYYYMMDD, // تاريخ النهاية
            'K' => NumberFormat::FORMAT_TEXT, // رقم البطاقة
            'M' => NumberFormat::FORMAT_NUMBER_00, // سعر البطاقة
            'N' => NumberFormat::FORMAT_NUMBER_00, // المبلغ الإجمالي
            'Q' => NumberFormat::FORMAT_PERCENTAGE_00, // نسبة الخصم
            'R' => NumberFormat::FORMAT_NUMBER_00, // مبلغ الخصم
            'AF' => NumberFormat::FORMAT_NUMBER_00, // إجمالي المدفوعات
        ];
    }
}
