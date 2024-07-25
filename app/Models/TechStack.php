<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TechStack extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'major',
        'label',
        'category',
        'type',
        'image_path',
        'url',
        'progress',
        'tip'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [];
    }

    /**
     * Mutators
     *
     * @param string $value
     * @return void
     */
    public function setTypeAttribute($value)
    {
        $this->attributes['type'] = $value === 'supportive' ? 'secondary' : $value;
    }

    public function getTypeAttribute($value)
    {
        return $value === 'secondary' ? 'supportive' : $value;
    }

    /**
     * Get the user that owns the profile
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
