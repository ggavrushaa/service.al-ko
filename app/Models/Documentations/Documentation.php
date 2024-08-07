<?php

namespace App\Models\Documentations;

use App\Models\ProductGroup;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Documentation extends Model
{

    protected $connection = 'second_db';
    protected $table = 'documentations';
    
    use HasFactory;
    protected $fillable = [
        'name', 'doc_type_id', 'category_id', 'added'
    ];

    public function documentType()
    {
        return $this->belongsTo(DocumentType::class, 'doc_type_id');
    }

    public function productGroup()
    {
        return $this->belongsTo(ProductGroup::class, 'category_id');
    }
}
