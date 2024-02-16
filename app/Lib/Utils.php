<?php

namespace Tasawk\Lib;

use App\Settings\DeveloperSetting;
use Tasawk\Filament\Pages\Settings\ManageDeveloper;
use Tasawk\Models\Branch;
use Tasawk\Models\Manager;
use Tasawk\Models\User;
use Tasawk\Settings\GeneralSettings;

class Utils
{
    static public function formatDistance($distance): string
    {
        $unit = trans('meter');
        if ($distance > 1000) {
            $distance = ($distance / 1000);
            $unit = trans("km");
        }
        $distance = round($distance, 2);
        return trans(":NUMBER :UNIT", ['NUMBER' => $distance, 'UNIT' => $unit]);
    }

    static public function getBranchFromRequestHeader(): string|null
    {
        return request()->header('X-Branch-ID');
    }

    static public function getBranchReceiptMethods()
    {
        return Branch::find(self::getBranchFromRequestHeader())->receipt_methods;
    }

    static public function getBranchLocation()
    {
        return Branch::where('id', self::getBranchFromRequestHeader())->first('location')->location;
    }

    static public function branchInHeavyLoadMode(): bool
    {
        return Branch::find(self::getBranchFromRequestHeader())->heavy_load_mode;
    }

    static public function getAdministrationUsers($branchId = null)
    {
        return User::whereHas('roles', fn ($q) => $q->whereIn('name', ['admin', 'super_admin']))
            ->get();
    }


    public static function convertStringToArrayLanguage($text, $params = []): array
    {
        $arr = [];
        foreach (['ar', 'en'] as $lang) {
            $arr[$lang] = __($text, $params, $lang);
        }

        return $arr;
    }

    public static function randomOtpCode(): int
    {
        $settings = new DeveloperSetting();
        return $settings->otp_code_is_random ? rand(1111, 9999) : 1234;
    }
}
