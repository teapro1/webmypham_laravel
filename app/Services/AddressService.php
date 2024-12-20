<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Models\Address;

class AddressService
{
    public static function getProvinces()
    {
        return Cache::remember('provinces', 1440, function () {
            $response = Http::timeout(30)->get('https://provinces.open-api.vn/api/p');
            return $response->successful() ? $response->json() : []; 
        });
    }

    public static function getDistricts($provinceId)
    {
        $response = Http::timeout(30)->get("https://provinces.open-api.vn/api/p/{$provinceId}?depth=2");
        return $response->successful() ? $response->json('districts') : []; 
    }

    public static function getWards($districtId)
    {
        $response = Http::timeout(30)->get("https://provinces.open-api.vn/api/d/{$districtId}?depth=2");
        return $response->successful() ? $response->json('wards') : []; 
    }

    public function createAddress()
    {
        $provinces = AddressService::getProvinces(); 
        return view('customer.profile.address.add', compact('provinces'));
    }

    public static function getProvinceName($provinceId)
    {
        return Cache::remember("province_name_{$provinceId}", 1440, function () use ($provinceId) {
            $response = Http::timeout(30)->get("https://provinces.open-api.vn/api/p/{$provinceId}");
            return $response->successful() ? $response->json('name') : 'N/A'; 
        });
    }

    public static function getDistrictName($districtId)
    {
        return Cache::remember("district_name_{$districtId}", 1440, function () use ($districtId) {
            $response = Http::timeout(30)->get("https://provinces.open-api.vn/api/d/{$districtId}");
            return $response->successful() ? $response->json('name') : 'N/A'; 
        });
    }

    public static function getWardName($wardId)
    {
        return Cache::remember("ward_name_{$wardId}", 1440, function () use ($wardId) {
            $response = Http::timeout(30)->get("https://provinces.open-api.vn/api/w/{$wardId}");
            return $response->successful() ? $response->json('name') : 'N/A';
        });
    }
//lay tt tu csdl tra ra ten
    public static function getFullAddresses($userId)
{
    return Address::where('user_id', $userId)->get()->map(function ($address) {
        return [
            'id' => $address->id,
            'detail_address' => $address->detail_address,
            'ward_name' => self::getWardName($address->ward), 
            'district_name' => self::getDistrictName($address->district), 
            'province_name' => self::getProvinceName($address->province), 
            'is_default' => $address->is_default,
        ];
    });
}


    public static function getDefaultAddress($userId)
{
    $defaultAddress = Address::where('user_id', $userId)
        ->where('is_default', true)
        ->first();

    if ($defaultAddress) {
        return [
            'id' => $defaultAddress->id,
            'detail_address' => $defaultAddress->detail_address,
            'ward' => $defaultAddress->ward,
            'district' => $defaultAddress->district, 
            'province' => $defaultAddress->province, 
            'is_default' => $defaultAddress->is_default,
            'ward_name' => self::getWardName($defaultAddress->ward), 
            'district_name' => self::getDistrictName($defaultAddress->district), 
            'province_name' => self::getProvinceName($defaultAddress->province), 
        ];
    }

    return null; 
}

    public static function getAddressById($id)
    {
        return Address::find($id);
    }

    public static function updateAddress($id, $data)
    {
        $address = Address::find($id);
        
        if (!$address) {
            return false; 
        }

      
        $address->update($data);
        return true;
    }
}
