<?php

namespace App\Observers;

use App\DocumentSet;
use Illuminate\Support\Facades\Storage;
use Spatie\LaravelImageOptimizer\Facades\ImageOptimizer;

class DocumentSetObserver
{
    
    /**
     * Optimize images before saving
     * @param  DocumentSet $documentSet [description]
     * @return [type]                   [description]
     */
    public function saving(DocumentSet $documentSet)
    {
        $files = DocumentSet::FILES;
        foreach( $files as $attribute) {
            if ( $documentSet->$attribute && $documentSet->isDirty($attribute)) {
                $this->optimize($documentSet->$attribute);
            }
        }
    }

    protected function optimize($file)
    {
        rescue(function() use ($file) {
            ImageOptimizer::optimize(Storage::disk('public')->path($file));
        });
    }
}
