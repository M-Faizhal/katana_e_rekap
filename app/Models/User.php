<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $primaryKey = 'id_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama',
        'username',
        'email',
        'no_telepon',
        'alamat',
        'password',
        'role',
        'foto',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Check if user has specific role
     */
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    /**
     * Check if user has any of the given roles
     */
    public function hasAnyRole(array $roles)
    {
        return in_array($this->role, $roles);
    }

    /**
     * Check if user is superadmin
     */
    public function isSuperAdmin()
    {
        return $this->role === 'superadmin';
    }

    /**
     * Check if user is marketing admin
     */
    public function isMarketingAdmin()
    {
        return $this->role === 'admin_marketing';
    }

    /**
     * Check if user is purchasing admin
     */
    public function isPurchasingAdmin()
    {
        return $this->role === 'admin_purchasing';
    }

    /**
     * Check if user is finance admin
     */
    public function isFinanceAdmin()
    {
        return $this->role === 'admin_keuangan';
    }

    /**
     * Get available roles
     */
    public static function getAvailableRoles()
    {
        return [
            'superadmin' => 'Super Admin',
            'admin_marketing' => 'Admin Marketing',
            'admin_purchasing' => 'Admin Purchasing',
            'admin_keuangan' => 'Admin Keuangan',
        ];
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'id_user';
    }

    /**
     * Get profile photo URL
     */
    public function getProfilePhotoUrlAttribute()
    {
        if ($this->foto && Storage::disk('public')->exists($this->foto)) {
            return asset('storage/' . $this->foto);
        }

        // Return null to indicate no profile photo
        return null;
    }

    /**
     * Get profile photo path for storage
     */
    public function getProfilePhotoPathAttribute()
    {
        return $this->foto;
    }

    /**
     * Check if user has profile photo
     */
    public function hasProfilePhoto()
    {
        return $this->foto && Storage::disk('public')->exists($this->foto);
    }

    // Relationships
    public function proyekMarketing()
    {
        return $this->hasMany(Proyek::class, 'id_admin_marketing');
    }

    public function proyekPurchasing()
    {
        return $this->hasMany(Proyek::class, 'id_admin_purchasing');
    }
}
