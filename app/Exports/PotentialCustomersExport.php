<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class PotentialCustomersExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle
{
    protected $customers;

    public function __construct($customers)
    {
        $this->customers = $customers;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->customers;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'الرقم',
            'الاسم',
            'البريد الإلكتروني',
            'رقم الجوال',
            'المدينة',
            'الحالة',
            'المصدر',
            'نوع الجهاز',
            'عنوان IP',
            'User Agent',
            'رابط الإحالة',
            'الصفحة المقصودة',
            'UTM Source',
            'UTM Medium',
            'UTM Campaign',
            'UTM Term',
            'UTM Content',
            'ملخص المكالمة',
            'تاريخ الطلب',
            'تاريخ الإنشاء',
            'تاريخ التحديث'
        ];
    }

    /**
     * @param mixed $customer
     * @return array
     */
    public function map($customer): array
    {
        return [
            $customer->id,
            $customer->name,
            $customer->email ?? '',
            $customer->phone,
            $customer->city ?? '',
            $customer->status ?? 'لم يتم التواصل',
            $this->getSourceDisplay($customer->source, $customer->referrer_url),
            $this->getDeviceTypeDisplay($customer->device_type),
            $customer->ip_address ?? '',
            $customer->user_agent ?? '',
            $customer->referrer_url ?? '',
            $customer->landing_page ?? '',
            $customer->utm_source ?? '',
            $customer->utm_medium ?? '',
            $customer->utm_campaign ?? '',
            $customer->utm_term ?? '',
            $customer->utm_content ?? '',
            $customer->call_summary ?? '',
            $customer->created_at->format('Y-m-d H:i:s'), // تاريخ الطلب
            $customer->created_at->format('Y-m-d H:i:s'), // تاريخ الإنشاء
            $customer->updated_at->format('Y-m-d H:i:s')  // تاريخ التحديث
        ];
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 8,   // الرقم
            'B' => 20,  // الاسم
            'C' => 25,  // البريد الإلكتروني
            'D' => 15,  // رقم الجوال
            'E' => 15,  // المدينة
            'F' => 15,  // الحالة
            'G' => 20,  // المصدر
            'H' => 12,  // نوع الجهاز
            'I' => 15,  // عنوان IP
            'J' => 30,  // User Agent
            'K' => 30,  // رابط الإحالة
            'L' => 30,  // الصفحة المقصودة
            'M' => 15,  // UTM Source
            'N' => 15,  // UTM Medium
            'O' => 20,  // UTM Campaign
            'P' => 15,  // UTM Term
            'Q' => 15,  // UTM Content
            'R' => 30,  // ملخص المكالمة
            'S' => 18,  // تاريخ الطلب
            'T' => 18,  // تاريخ الإنشاء
            'U' => 18,  // تاريخ التحديث
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // تنسيق الهيدر
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ]
            ],
            // تنسيق البيانات
            'A:U' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_RIGHT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ],
                'font' => [
                    'size' => 10
                ]
            ]
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'العملاء المحتملين';
    }

    /**
     * Get source display name with referrer URL
     */
    private function getSourceDisplay($source, $referrerUrl = null)
    {
        // إذا كان هناك رابط إحالة، اعرضه
        if (!empty($referrerUrl)) {
            $domain = parse_url($referrerUrl, PHP_URL_HOST);
            if ($domain) {
                // إزالة www. من بداية النطاق
                $domain = preg_replace('/^www\./', '', $domain);
                return $domain;
            }
        }

        // إذا لم يكن هناك رابط إحالة، استخدم التصنيف التقليدي
        $sources = [
            'google_ads' => 'إعلانات جوجل',
            'facebook_ads' => 'إعلانات فيسبوك',
            'direct' => 'دخول مباشر',
            'organic' => 'بحث طبيعي',
            'referral' => 'إحالة',
            'social' => 'وسائل التواصل',
            'card_request' => 'طلب بطاقة'
        ];

        return $sources[$source] ?? 'غير محدد';
    }

    /**
     * Get device type display name
     */
    private function getDeviceTypeDisplay($deviceType)
    {
        $types = [
            'mobile' => 'جوال',
            'desktop' => 'كمبيوتر',
            'tablet' => 'تابلت'
        ];

        return $types[$deviceType] ?? 'غير محدد';
    }
}
