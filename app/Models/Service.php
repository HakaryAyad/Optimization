<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Service extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'server_id',
        'name',
        'cpu',
        'memory',
        'storage',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'server_id' => 'integer',
            'cpu' => 'integer',
            'memory' => 'integer',
            'storage' => 'integer',
        ];
    }

    /**
     * Server hosting this service.
     */
    public function server(): BelongsTo
    {
        return $this->belongsTo(Server::class);
    }
}
