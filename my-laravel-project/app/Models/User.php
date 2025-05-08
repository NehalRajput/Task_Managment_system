<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'user_type_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    // Relationships
    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'intern_task');
    }

    public function userType()
    {
        return $this->belongsTo(UserType::class);
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function unreadMessages()
    {
        return $this->receivedMessages()->where('is_read', false);
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function adminChats()
    {
        return $this->hasMany(Chat::class, 'admin_id');
    }

    public function internChats()
    {
        return $this->hasMany(Chat::class, 'intern_id');
    }

    public function chats()
    {
        if ($this->isAdmin() || $this->isSuperAdmin()) {
            return $this->adminChats();
        }
        return $this->internChats();
    }

    // Role checkers
    public function isSuperAdmin()
    {
        return $this->userType->name === 'super_admin';
    }

    public function isAdmin()
    {
        return $this->userType->name === 'admin';
    }

    public function isIntern()
    {
        return $this->userType->name === 'intern';
    }
}
