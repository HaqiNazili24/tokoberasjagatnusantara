<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
        'user_id', 'user_name', 'role', 'action_type', 'changes_detail'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Helper to log actions
     */
    public static function log(User $user, string $actionType, string $changesDetail): self
    {
        return self::create([
            'user_id' => $user->id,
            'user_name' => $user->full_name,
            'role' => $user->role,
            'action_type' => $actionType,
            'changes_detail' => $changesDetail,
        ]);
    }
}
