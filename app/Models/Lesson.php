<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lesson extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'duration_in_minutes',
        'document',
        'module_id',
        'video',
        'description',
        'content'
    ];

    private $documentMap = [
        '.pdf' => 'PDF',
        '.docx' => 'DOCX',
        '.doc' => 'DOC',
        '.pptx' => 'PPTX',
        '.ppt' => 'PPT',
        '.mp3' => 'MP3',
        '.rar' => 'RAR',
        '.zip' => 'ZIP',
        '.xlsx' => 'XLSX',
        '.xls' => 'XLS'
    ];

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    public function getDocumentTypeAttribute()
    {
        if (!$this->document) {
            return null;
        }

        $documentExtension = strtolower(pathinfo($this->document, PATHINFO_EXTENSION));
        return $this->documentMap['.' . $documentExtension] ?? null;
    }
}
