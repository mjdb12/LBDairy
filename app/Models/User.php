<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'phone',
        'address',
        'role',
        'profile_image',
        'password',
        'is_active',
        'admin_code',
        'position',
        'barangay',
        'farmer_code',
        'farm_name',
        'farm_address',
        'first_name',
        'last_name',
        'status',
        'terms_accepted',
        'last_login_at',
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
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
    ];

    /**
     * Get the farms owned by the user.
     */
    public function farms()
    {
        return $this->hasMany(Farm::class, 'owner_id');
    }

    /**
     * Get the livestock owned by the user.
     */
    public function livestock()
    {
        return $this->hasMany(Livestock::class, 'owner_id');
    }

    /**
     * Get the production records recorded by the user.
     */
    public function productionRecords()
    {
        return $this->hasMany(ProductionRecord::class, 'recorded_by');
    }

    /**
     * Get the sales recorded by the user.
     */
    public function sales()
    {
        return $this->hasMany(Sale::class, 'recorded_by');
    }

    /**
     * Get the expenses recorded by the user.
     */
    public function expenses()
    {
        return $this->hasMany(Expense::class, 'recorded_by');
    }

    /**
     * Get the issues reported by the user.
     */
    public function reportedIssues()
    {
        return $this->hasMany(Issue::class, 'reported_by');
    }

    /**
     * Get the issues assigned to the user.
     */
    public function assignedIssues()
    {
        return $this->hasMany(Issue::class, 'assigned_to');
    }

    /**
     * Get the audit logs for the user.
     */
    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }

    /**
     * Check if user is a farmer.
     */
    public function isFarmer()
    {
        return $this->role === 'farmer';
    }

    /**
     * Check if user is an admin.
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is a super admin.
     */
    public function isSuperAdmin()
    {
        return $this->role === 'superadmin';
    }
}
