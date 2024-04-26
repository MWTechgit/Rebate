<?php

namespace App;

use App\Cacheable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;

/**
 * An Admin user who approves/denies rebates.
 *
 * This user also exports spreadsheets and sends them to
 * a separate office. There is really only one main person
 * who manages rebates at the time of this writing (2018).
 *
 * Not all admins have the same privileges, same of them have
 * only read privs. These are people that work at the call center
 * who need to give rebate applicants information about the
 * status of their rebate.
 */
class Admin extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    use Cacheable;

    const WRITE = 'admin';
    const READ = 'call_center';

    protected $guarded = [];

    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * Approvals and Denials of claims
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(ClaimTransaction::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function canWrite(): bool
    {
        return $this->role == self::WRITE;
    }

    public function isSuperAdmin(): bool
    {
        $adminEmails = Arr::wrap(config('broward.super_admins', []));
        return in_array($this->email, $adminEmails);
    }

    public function scopeWhereReceivesAlerts($query) {
        return $query->where('receive_alerts', true);
    }
}
