<?php
namespace App\Traits;
use Illuminate\Database\Eloquent\Builder;

trait CommonQueryScopes {
    public function scopeFilterByStatus(Builder $q, $status) {
        return $status ? $q->where('status',$status) : $q;
    }

    public function scopeSearchByTitle(Builder $q, $term) {
        return $term ? $q->where('title','like','%'.$term.'%') : $q;
    }
}
