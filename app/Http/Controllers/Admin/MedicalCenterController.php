<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MedicalCenter;
use App\Helpers\CitiesHelper;
use App\Traits\MedicalCenterManagement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class MedicalCenterController extends Controller
{
    use MedicalCenterManagement;
    /**
     * Display a listing of the medical centers.
     */
    public function index(Request $request)
    {
        $query = MedicalCenter::query();

        // البحث
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // فلترة حسب المنطقة
        if ($request->filled('region')) {
            $query->where('region', $request->region);
        }

        // فلترة حسب الحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $medicalCenters = $query->latest()->paginate(15);

        // جمع المناطق للفلترة
        $regions = MedicalCenter::distinct()->pluck('region')->filter()->sort();

        return view('admin.medical-centers.index', compact('medicalCenters', 'regions'));
    }

    /**
     * Show the form for creating a new medical center.
     */
    public function create()
    {
        $regions = CitiesHelper::getAllRegions();

        // تجميع المدن حسب المناطق
        $citiesByRegion = [];
        foreach ($regions as $region) {
            $citiesByRegion[$region['name']] = $region['cities'];
        }

        return view('admin.medical-centers.create', compact('citiesByRegion'));
    }

    /**
     * Store a newly created medical center in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $this->processMedicalCenterCreation($request);

            MedicalCenter::create($validated);

            $messages = $this->getSuccessMessages();
            return redirect()->route('admin.medical-centers.index')
                ->with('success', $messages['created']);

        } catch (\Exception $e) {
            return $this->handleValidationError($e, $request->all());
        }
    }

    /**
     * Display the specified medical center.
     */
    public function show(MedicalCenter $medicalCenter)
    {
        return view('admin.medical-centers.show', compact('medicalCenter'));
    }

    /**
     * Show the form for editing the specified medical center.
     */
    public function edit(MedicalCenter $medicalCenter)
    {
        $regions = CitiesHelper::getAllRegions();

        // تجميع المدن حسب المناطق
        $citiesByRegion = [];
        foreach ($regions as $region) {
            $citiesByRegion[$region['name']] = $region['cities'];
        }

        return view('admin.medical-centers.edit', compact('medicalCenter', 'citiesByRegion'));
    }

    /**
     * Update the specified medical center in storage.
     */
    public function update(Request $request, MedicalCenter $medicalCenter)
    {
        try {
            $validated = $this->processMedicalCenterUpdate($request, $medicalCenter);

            $medicalCenter->update($validated);

            $messages = $this->getSuccessMessages();
            return redirect()->route('admin.medical-centers.index')
                ->with('success', $messages['updated']);

        } catch (\Exception $e) {
            return $this->handleValidationError($e, $request->all());
        }
    }

    /**
     * Remove the specified medical center from storage.
     */
    public function destroy(MedicalCenter $medicalCenter)
    {
        try {
            // حذف الصورة
            if ($medicalCenter->image) {
                \App\Helpers\ImageHelper::delete($medicalCenter->image);
            }

            $medicalCenter->delete();

            $messages = $this->getSuccessMessages();
            return redirect()->route('admin.medical-centers.index')
                ->with('success', $messages['deleted']);

        } catch (\Exception $e) {
            return redirect()->route('admin.medical-centers.index')
                ->with('error', 'حدث خطأ أثناء حذف المركز الطبي: ' . $e->getMessage());
        }
    }

    /**
     * Toggle the status of the medical center.
     */
    public function toggleStatus(MedicalCenter $medicalCenter)
    {
        $newStatus = $medicalCenter->status === 'active' ? 'inactive' : 'active';
        $medicalCenter->update(['status' => $newStatus]);

        $message = $newStatus === 'active' ? 'تم تفعيل المركز الطبي' : 'تم إيقاف المركز الطبي';

        return redirect()->back()->with('success', $message);
    }

    /**
     * Export medical centers to CSV
     */
    public function exportCsv(Request $request)
    {
        $filename = 'medical_centers_' . date('Y-m-d_H-i-s') . '.csv';

        $medicalCenters = MedicalCenter::with('creator')->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($medicalCenters) {
            $file = fopen('php://output', 'w');

            // Add UTF-8 BOM for proper Arabic display
            fwrite($file, "\xEF\xBB\xBF");

            // CSV Headers
            fputcsv($file, [
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
            ]);

            // Data rows
            foreach ($medicalCenters as $center) {
                fputcsv($file, [
                    $center->id,
                    $center->name,
                    $center->slug,
                    $center->description,
                    $center->region,
                    $center->city,
                    $center->address,
                    $center->longitude,
                    $center->latitude,
                    $center->phone,
                    $center->email,
                    $center->website,
                    $center->type,
                    is_array($center->medical_service_types) ? implode(', ', $center->medical_service_types) : '',
                    is_array($center->medical_discounts) ? json_encode($center->medical_discounts, JSON_UNESCAPED_UNICODE) : '',
                    $center->status,
                    $center->contract_status,
                    $center->contract_start_date,
                    $center->contract_end_date,
                    $center->image,
                    $center->location,
                    $center->rating,
                    $center->reviews_count,
                    $center->views_count,
                    $center->creator ? $center->creator->name : '',
                    $center->created_at ? $center->created_at->format('Y-m-d H:i:s') : '',
                    $center->updated_at ? $center->updated_at->format('Y-m-d H:i:s') : '',
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Import medical centers from CSV
     */
    public function importCsv(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt|max:10240', // 10MB max
        ]);

        try {
            $file = $request->file('csv_file');
            $path = $file->getRealPath();

            $csv = array_map('str_getcsv', file($path));

            // Remove BOM if present
            if (!empty($csv[0][0])) {
                $csv[0][0] = preg_replace('/^\x{FEFF}/u', '', $csv[0][0]);
            }

            $headers = array_shift($csv); // Remove header row

            $imported = 0;
            $errors = [];

            foreach ($csv as $index => $row) {
                $rowNumber = $index + 2; // +2 because we removed header and arrays are 0-indexed

                try {
                    // Skip empty rows
                    if (empty(array_filter($row))) {
                        continue;
                    }

                    // Map CSV data to array
                    $data = $this->mapCsvRowToData($headers, $row);

                    // Validate required fields
                    if (empty($data['name']) || empty($data['region']) || empty($data['city'])) {
                        $errors[] = "الصف {$rowNumber}: الحقول المطلوبة مفقودة (اسم المركز، المنطقة، المدينة)";
                        continue;
                    }

                    // Create or update medical center
                    $slug = \Illuminate\Support\Str::slug($data['name']);
                    $data['slug'] = $slug;
                    $data['created_by'] = auth()->id();

                    MedicalCenter::updateOrCreate(
                        ['slug' => $slug],
                        $data
                    );

                    $imported++;

                } catch (\Exception $e) {
                    $errors[] = "الصف {$rowNumber}: " . $e->getMessage();
                }
            }

            if (!empty($errors)) {
                return redirect()->back()
                    ->with('warning', "تم استيراد {$imported} مركز طبي بنجاح، مع وجود " . count($errors) . " أخطاء.")
                    ->with('import_errors', $errors);
            }

            return redirect()->route('admin.medical-centers.index')
                ->with('success', "تم استيراد {$imported} مركز طبي بنجاح!");

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء الاستيراد: ' . $e->getMessage());
        }
    }

    /**
     * Download CSV template
     */
    public function downloadCsvTemplate()
    {
        $filename = 'medical_centers_template.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');

            // Add UTF-8 BOM for proper Arabic display
            fwrite($file, "\xEF\xBB\xBF");

            // CSV Headers
            fputcsv($file, [
                'اسم المركز',
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
                'رابط الموقع',
            ]);

            // Sample data row
            fputcsv($file, [
                'مستشفى الملك فهد',
                'مستشفى متخصص في جميع التخصصات الطبية',
                'الرياض',
                'الرياض',
                'شارع الملك فهد، الرياض',
                '46.6753',
                '24.6877',
                '0112345678',
                'info@hospital.com',
                'https://hospital.com',
                'hospital',
                'طب عام, جراحة, أطفال',
                '{"general": 10, "surgery": 15}',
                'active',
                'active',
                '2024-01-01',
                '2025-01-01',
                'https://maps.google.com/...',
            ]);

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Handle bulk actions
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'selected_centers' => 'required|string',
        ]);

        $centerIds = explode(',', $request->selected_centers);
        $centerIds = array_filter($centerIds); // Remove empty values

        if (empty($centerIds)) {
            return redirect()->back()->with('error', 'لم يتم تحديد أي مراكز طبية');
        }

        $action = $request->action;
        $count = 0;

        try {
            switch ($action) {
                case 'activate':
                    $count = MedicalCenter::whereIn('id', $centerIds)->update(['status' => 'active']);
                    $message = "تم تفعيل {$count} مركز طبي بنجاح";
                    break;

                case 'deactivate':
                    $count = MedicalCenter::whereIn('id', $centerIds)->update(['status' => 'inactive']);
                    $message = "تم إيقاف {$count} مركز طبي بنجاح";
                    break;

                case 'delete':
                    $count = MedicalCenter::whereIn('id', $centerIds)->delete();
                    $message = "تم حذف {$count} مركز طبي بنجاح";
                    break;

                default:
                    return redirect()->back()->with('error', 'إجراء غير صحيح');
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تنفيذ العملية: ' . $e->getMessage());
        }
    }

    /**
     * Map CSV row data to medical center data array
     */
    private function mapCsvRowToData($headers, $row)
    {
        $data = [];

        foreach ($headers as $index => $header) {
            $value = isset($row[$index]) ? trim($row[$index]) : '';

            switch (trim($header)) {
                case 'اسم المركز':
                    $data['name'] = $value;
                    break;
                case 'الوصف':
                    $data['description'] = $value;
                    break;
                case 'المنطقة':
                    $data['region'] = $value;
                    break;
                case 'المدينة':
                    $data['city'] = $value;
                    break;
                case 'العنوان':
                    $data['address'] = $value;
                    break;
                case 'خط الطول':
                    $data['longitude'] = $value ? (float)$value : null;
                    break;
                case 'خط العرض':
                    $data['latitude'] = $value ? (float)$value : null;
                    break;
                case 'الهاتف':
                    $data['phone'] = $value;
                    break;
                case 'البريد الإلكتروني':
                    $data['email'] = $value;
                    break;
                case 'الموقع الإلكتروني':
                    $data['website'] = $value;
                    break;
                case 'نوع المركز':
                    $data['type'] = $value;
                    break;
                case 'أنواع الخدمات الطبية':
                    $data['medical_service_types'] = $value ? explode(', ', $value) : [];
                    break;
                case 'الخصومات الطبية':
                    $data['medical_discounts'] = $value ? json_decode($value, true) : [];
                    break;
                case 'الحالة':
                    $data['status'] = $value ?: 'active';
                    break;
                case 'حالة العقد':
                    $data['contract_status'] = $value;
                    break;
                case 'تاريخ بداية العقد':
                    $data['contract_start_date'] = $value ? date('Y-m-d', strtotime($value)) : null;
                    break;
                case 'تاريخ نهاية العقد':
                    $data['contract_end_date'] = $value ? date('Y-m-d', strtotime($value)) : null;
                    break;
                case 'رابط الموقع':
                    $data['location'] = $value;
                    break;
            }
        }

        return $data;
    }


}
