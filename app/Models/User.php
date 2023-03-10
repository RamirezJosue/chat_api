<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Notifications\MessageSent;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';
    protected $guarded = ['id'];

    protected $hidden = [
        'password'
    ];

    const USER_TOKEN = "userToken";

    public function chats(): HasMany
    {
        return $this->hasMany(Chat::class, 'created_by');
    }

    public function routeNotificationForOneSignal(): array
    {
        return ['tags' => ['key' => 'userId', 'relation' => '=', 'value' => (string)($this->id)]];
    }

    public function sendNewMessageNotification(array $data): void
    {
        $this->notify(new MessageSent($data));
    }
}
