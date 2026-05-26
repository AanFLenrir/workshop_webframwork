<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Customer extends Model
{
    protected $fillable = ['name', 'phone', 'photo_blob', 'photo_path'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public static function generateGuestName(): string
    {
        $last   = static::orderBy('id', 'desc')->first();
        $nextId = $last ? $last->id + 1 : 1;
        return 'Guest_' . str_pad($nextId, 7, '0', STR_PAD_LEFT);
    }
}