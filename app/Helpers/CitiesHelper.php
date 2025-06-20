<?php

namespace App\Helpers;

class CitiesHelper
{
    /**
     * الحصول على جميع المناطق
     */
    public static function getAllRegions()
    {
        $data = config('cities.regions', []);
        return collect($data);
    }

    /**
     * الحصول على جميع المدن
     */
    public static function getAllCities()
    {
        $regions = self::getAllRegions();
        $cities = collect();
        
        foreach ($regions as $region) {
            foreach ($region['cities'] as $city) {
                $cities->push([
                    'name' => $city['name'],
                    'name_en' => $city['name_en'],
                    'slug' => $city['name_en'],
                    'region_name' => $region['name'],
                    'region_name_en' => $region['name_en'],
                    'region_slug' => $region['name_en']
                ]);
            }
        }
        
        return $cities;
    }

    /**
     * الحصول على منطقة بالاسم
     */
    public static function getRegionByName($name)
    {
        $regions = self::getAllRegions();
        return $regions->firstWhere('name', $name);
    }

    /**
     * الحصول على منطقة بالـ slug
     */
    public static function getRegionBySlug($slug)
    {
        $regions = self::getAllRegions();
        return $regions->firstWhere('name_en', $slug);
    }

    /**
     * الحصول على مدينة بالاسم
     */
    public static function getCityByName($name)
    {
        $cities = self::getAllCities();
        return $cities->firstWhere('name', $name);
    }

    /**
     * الحصول على مدينة بالـ slug
     */
    public static function getCityBySlug($slug)
    {
        $cities = self::getAllCities();
        return $cities->firstWhere('slug', $slug);
    }

    /**
     * الحصول على مدن منطقة معينة
     */
    public static function getCitiesByRegion($regionName)
    {
        $region = self::getRegionByName($regionName);
        if (!$region) {
            return collect();
        }

        return collect($region['cities'])->map(function($city) use ($region) {
            return [
                'name' => $city['name'],
                'name_en' => $city['name_en'],
                'slug' => $city['name_en'],
                'region_name' => $region['name'],
                'region_name_en' => $region['name_en'],
                'region_slug' => $region['name_en']
            ];
        });
    }

    /**
     * الحصول على مدن منطقة معينة بالـ slug
     */
    public static function getCitiesByRegionSlug($regionSlug)
    {
        $region = self::getRegionBySlug($regionSlug);
        if (!$region) {
            return collect();
        }

        return collect($region['cities'])->map(function($city) use ($region) {
            return [
                'name' => $city['name'],
                'name_en' => $city['name_en'],
                'slug' => $city['name_en'],
                'region_name' => $region['name'],
                'region_name_en' => $region['name_en'],
                'region_slug' => $region['name_en']
            ];
        });
    }

    /**
     * الحصول على منطقة مدينة معينة
     */
    public static function getRegionByCity($cityName)
    {
        $city = self::getCityByName($cityName);
        if (!$city) {
            return null;
        }

        return [
            'name' => $city['region_name'],
            'name_en' => $city['region_name_en'],
            'slug' => $city['region_slug']
        ];
    }

    /**
     * التحقق من وجود منطقة
     */
    public static function regionExists($name)
    {
        return self::getRegionByName($name) !== null;
    }

    /**
     * التحقق من وجود مدينة
     */
    public static function cityExists($name)
    {
        return self::getCityByName($name) !== null;
    }

    /**
     * الحصول على خيارات المناطق للـ select
     */
    public static function getRegionOptions()
    {
        $regions = self::getAllRegions();
        $options = [];
        
        foreach ($regions as $region) {
            $options[$region['name']] = $region['name'];
        }
        
        return $options;
    }

    /**
     * الحصول على خيارات المدن للـ select
     */
    public static function getCityOptions()
    {
        $cities = self::getAllCities();
        $options = [];
        
        foreach ($cities as $city) {
            $options[$city['name']] = $city['name'] . ' - ' . $city['region_name'];
        }
        
        return $options;
    }

    /**
     * الحصول على خيارات مدن منطقة معينة للـ select
     */
    public static function getCityOptionsByRegion($regionName)
    {
        $cities = self::getCitiesByRegion($regionName);
        $options = [];
        
        foreach ($cities as $city) {
            $options[$city['name']] = $city['name'];
        }
        
        return $options;
    }

    /**
     * تحويل اسم المدينة إلى slug
     */
    public static function cityNameToSlug($cityName)
    {
        $city = self::getCityByName($cityName);
        return $city ? $city['slug'] : null;
    }

    /**
     * تحويل اسم المنطقة إلى slug
     */
    public static function regionNameToSlug($regionName)
    {
        $region = self::getRegionByName($regionName);
        return $region ? $region['name_en'] : null;
    }

    /**
     * تحويل slug إلى اسم المدينة
     */
    public static function slugToCityName($slug)
    {
        $city = self::getCityBySlug($slug);
        return $city ? $city['name'] : null;
    }

    /**
     * تحويل slug إلى اسم المنطقة
     */
    public static function slugToRegionName($slug)
    {
        $region = self::getRegionBySlug($slug);
        return $region ? $region['name'] : null;
    }
}
