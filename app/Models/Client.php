<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Orchid\Filters\Filterable;
use Orchid\Filters\Types\Where;
use Orchid\Metrics\Chartable;
use Orchid\Screen\AsSource;
use Orchid\Screen\Fields\Input;
use Orchid\Support\Facades\Layout;
use Propaganistas\LaravelPhone\PhoneNumber;

class Client extends Model
{
    use HasFactory;
    use AsSource;
    use Filterable;
    use Chartable;

    protected $fillable = ['phone', 'name', 'last_name', 'status', 'email', 'birthday', 'service_id', 'assessment', 'invoice_id'];

    protected $allowedSorts = [
        'status'
    ];

    protected $allowedFilters = [
        'phone' => Where::class,
    ];

    public const STATUS = [
        'interviewed' => 'Опрошен',
        'not_interviewed' => 'Не опрошен'
    ];

    public function setPhoneAttribute($phone)
    {
        $this->attributes['phone'] = make_phone_normalized($phone);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
