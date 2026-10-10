<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Server extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'cpu',
        'memory',
        'storage',
        'latency',
        'max_services',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'cpu' => 'integer',
            'memory' => 'integer',
            'storage' => 'integer',
            'latency' => 'integer',
            'max_services' => 'integer',
        ];
    }

    /**
     * Services currently allocated to this server.
     */
    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }
}
