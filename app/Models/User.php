<?php

namespace Tasawk\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
use ChristianKuri\LaravelFavorite\Traits\Favoriteability;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;
use Tasawk\Enum\ModelStatus;
use Tasawk\Enum\UserStatus;

class User extends Authenticatable implements HasMedia, FilamentUser, HasLocalePreference {
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles, InteractsWithMedia;
    use HasPanelShield, Favoriteability;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'last_name',
        'email',
        'phone',
        'gender',
        'birth_date',
        'balance',
        'active',
        'api_token',
        'password',
        'phone_verified_at',
        'settings',
        'active',
        'data',
        'iban',
        'national_id',
        'national_type',
        'package_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'settings' => 'array',
        'data' => 'array',
    ];


    public function toggleActive(): bool {
        if ($this->active) {
            return $this->update(['active' => 0]);
        }
        return $this->update(['active' => 1]);
    }

    // reservations relation
    public function reservations(): HasMany {
        return $this->hasMany(Reservation::class, 'customer_id');
    }

    public function deviceTokens(): HasMany {
        return $this->hasMany(DeviceToken::class, "user_id");
    }

    public function notifications() {
        return $this->morphMany(Notification::class, 'notifiable')->latest();

    }

    public function isFirstTimeToLogin(): bool {
        return !$this->remember_token;
    }

    public function verified(): int {
        return !is_null($this->phone_verified_at);
    }

    public function verificationCodes(): HasMany {
        return $this->hasMany(VerificationCode::class, "user_id");
    }

    public function preferredLocale() {
        return 'ar';
    }

    public function addresses() {
        return $this->hasMany(AddressBook::class);
    }

    public function toCustomer(): HasOne {
        return $this->hasOne(Customer::class, 'id');
    }

    public function actAs($model) {
        return $this->hasOne($model, 'id')->first();
    }

    public function isManager() {
        return $this->hasRole('manager');
    }

    public function isOperator() {
        return $this->hasRole('operator');
    }

    public function manager() {
        return $this->hasOne(Manager::class, 'id');
    }
    // public function operator() {
    //     return $this->hasOne(Operator::class, 'id');
    // }
    public function canAccessPanel(Panel $panel): bool {
        return true;
    }
    public function orders() {
        return $this->hasMany(Order::class, 'customer_id');
    }
    // public function setPasswordAttribute($value): void
    // {
    //     if ($value && !app()->runningInConsole()) {
    //         $this->attributes["password"] = bcrypt($value);
    //     }
    // }
}
