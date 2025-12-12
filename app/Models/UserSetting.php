<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'setting_key',
        'setting_value',
    ];

    /**
     * Get the user that owns the setting.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get a setting value for a user.
     */
    public static function get($userId, $key, $default = null)
    {
        $setting = static::where('user_id', $userId)
            ->where('setting_key', $key)
            ->first();

        return $setting ? $setting->setting_value : $default;
    }

    /**
     * Set a setting value for a user.
     */
    public static function set($userId, $key, $value)
    {
        return static::updateOrCreate(
            ['user_id' => $userId, 'setting_key' => $key],
            ['setting_value' => $value]
        );
    }
}
