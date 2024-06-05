<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Các thuộc tính có thể gán hàng loạt.
     *
     * @var array
     */
    protected $primaryKey = 'user_id'; // Đặt khóa chính là user_id nếu không phải là id
    public $incrementing = false; // Nếu user_id không phải là số tự tăng
    protected $keyType = 'string'; // Nếu user_id là chuỗi, còn nếu là số thì không cần dòng này
    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'provincestore',
        'districtstore',
        'communestore',
        'password',
        'role',
    ];

    /**
     * Các thuộc tính nên được ẩn khi trả về trong mảng.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Các thuộc tính sẽ được cast về các kiểu dữ liệu khác.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
