<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use NotificationChannels\WebPush\HasPushSubscriptions;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, HasPushSubscriptions;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'whatsapp',
        'address',
        'commune',
        'quartier',
        'role',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ============================================
    // Helpers rôles
    // ============================================

    public function isParent(): bool
    {
        return $this->role === 'parent';
    }

    public function isRepetiteur(): bool
    {
        return $this->role === 'repetiteur';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // ============================================
    // Helpers login
    // ============================================

    public static function isEmail(string $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function normalizePhone(string $value): string
    {
        $digits = preg_replace('/\D+/', '', $value);

        if (str_starts_with($digits, '225')) {
            $digits = substr($digits, 3);
        }

        return $digits;
    }

    // ============================================
    // Relations
    // ============================================

    public function eleves()
    {
        return $this->hasMany(Eleve::class, 'parent_id');
    }

    public function demandes()
    {
        return $this->hasMany(Demande::class, 'parent_id');
    }

    public function derniereDemande()
    {
        return $this->hasOne(Demande::class, 'parent_id')->latestOfMany();
    }

    public function repetiteur()
    {
        return $this->hasOne(Repetiteur::class);
    }

    public function placements()
    {
        return $this->hasMany(Placement::class, 'parent_id');
    }

    public function placementsRepetiteur()
    {
        return $this->hasManyThrough(
            Placement::class,
            Repetiteur::class,
            'user_id',
            'repetiteur_id',
            'id',
            'id'
        );
    }
}