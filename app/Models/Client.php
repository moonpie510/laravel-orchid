<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Filters\Types\Where;
use Orchid\Screen\AsSource;
use Orchid\Screen\Fields\Input;
use Orchid\Support\Facades\Layout;
use Propaganistas\LaravelPhone\PhoneNumber;

class Client extends Model
{
    use HasFactory;
    use AsSource;
    use Filterable;

    protected $fillable = ['phone', 'name', 'last_name', 'status', 'email', 'birthday', 'service_id', 'assessment', 'invoice_id'];

    protected $allowedSorts = [
        'status'
    ];

    protected $allowedFilters = [
        'phone' => Where::class,
    ];

    public function setPhoneAttribute($phone)
    {
        $this->attributes['phone'] = str_replace('+', '', (string) new PhoneNumber($phone));
    }
}
