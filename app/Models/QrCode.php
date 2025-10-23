<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_type',
        'reference_id',
        'qr_content',
        'file_path',
        'generated_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}
