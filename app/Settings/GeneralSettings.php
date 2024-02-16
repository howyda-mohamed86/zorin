<?php

namespace Tasawk\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings {
    public string|null $app_logo;
    public string $app_name;
    public string $app_email;
    public string $app_phone;
    public string $app_address;
    public float $taxes;
    public array $app_pages = [];

    public array $social_links = [];
    public static function group(): string {
        return 'general';
    }
}
