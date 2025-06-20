<?php

namespace App\Exports;

use App\Models\MedicalCenter;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MedicalCentersExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return MedicalCenter::with('creator')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'اسم المركز',
            'الرابط المختصر',
            'الوصف',
            'المنطقة',
            'المدينة',
            'العنوان',
            'خط الطول',
            'خط العرض',
            'الهاتف',
            'البريد الإلكتروني',
            'الموقع الإلكتروني',
            'نوع المركز',
            'أنواع الخدمات الطبية',
            'الخصومات الطبية',
            'الحالة',
            'حالة العقد',
            'تاريخ بداية العقد',
            'تاريخ نهاية العقد',
            'الصورة',
            'رابط الموقع',
            'التقييم',
            'عدد التقييمات',
            'عدد المشاهدات',
            'منشئ بواسطة',
            'تاريخ الإنشاء',
            'تاريخ التحديث',
        ];
    }

    /**
     * @param MedicalCenter $medicalCenter
     * @return array
     */
    public function map($medicalCenter): array
    {
        return [
            $medicalCenter->id,
            $medicalCenter->name,
            $medicalCenter->slug,
            $medicalCenter->description,
            $medicalCenter->region,
            $medicalCenter->city,
            $medicalCenter->address,
            $medicalCenter->longitude,
            $medicalCenter->latitude,
            $medicalCenter->phone,
            $medicalCenter->email,
            $medicalCenter->website,
            $medicalCenter->type,
            is_array($medicalCenter->medical_service_types) ? implode(', ', $medicalCenter->medical_service_types) : '',
            is_array($medicalCenter->medical_discounts) ? json_encode($medicalCenter->medical_discounts, JSON_UNESCAPED_UNICODE) : '',
            $medicalCenter->status,
            $medicalCenter->contract_status,
            $medicalCenter->contract_start_date?->format('Y-m-d'),
            $medicalCenter->contract_end_date?->format('Y-m-d'),
            $medicalCenter->image,
            $medicalCenter->location,
            $medicalCenter->rating,
            $medicalCenter->reviews_count,
            $medicalCenter->views_count,
            $medicalCenter->creator?->name ?? '',
            $medicalCenter->created_at?->format('Y-m-d H:i:s'),
            $medicalCenter->updated_at?->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // تنسيق الصف الأول (العناوين)
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => 'FFE2E2E2',
                    ],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],
        ];
    }
}
