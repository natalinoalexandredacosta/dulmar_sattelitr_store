<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestimonialProof extends Model
{
    use HasFactory;

    public const TYPE_CHAT = 'chat';
    public const TYPE_VIDEO = 'video';

    protected $fillable = [
        'testimonial_id',
        'proof_type',
        'file_path',
        'file_name',
        'mime_type',
        'file_size',
    ];

    protected $casts = [
        'file_size' => 'integer',
    ];

    public function testimonial()
    {
        return $this->belongsTo(Testimonial::class);
    }

    public function getProofTypeLabelAttribute(): string
    {
        return match ($this->proof_type) {
            self::TYPE_CHAT => 'Bukti Chat',
            self::TYPE_VIDEO => 'Bukti Video',
            default => ucfirst((string) $this->proof_type),
        };
    }
}