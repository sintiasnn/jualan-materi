<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Agent\Agent; 

class ActiveSession extends Model
{
    protected $fillable = [
        'user_id',
        'token',
        'device_name',
        'last_url',
        'last_active_at'
    ];

    protected $casts = [
        'last_active_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getDeviceInfo()
    {
        $agent = new Agent();
        $agent->setUserAgent($this->device_name);

        $deviceType = $agent->isPhone() ? 'phone' : ($agent->isTablet() ? 'tablet' : 'desktop');
        $browser = $agent->browser();
        $platform = $agent->platform();

        return [
            'type' => $deviceType,
            'browser' => $browser,
            'platform' => $platform,
            'icon' => $this->getDeviceIcon($deviceType)
        ];
    }

    private function getDeviceIcon($type)
    {
        return match($type) {
            'phone' => 'solid fa-mobile-screen-button',
            'tablet' => 'solid fa-tablet-screen-button',
            'desktop' => 'solid fa-desktop',
            default => 'device'
        };
    }
}