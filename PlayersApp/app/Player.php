<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Level;

class Player extends Model
{
    protected $table = 'players';

     protected $fillable = [
        'name', 'score', 'level_id', 'suspected'
    ];

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function scopeFilters($query, $filters = [])
    { 
        if(is_array($filters) && !empty($filters) ){
           
            if(isset($filters['level']) && !empty($filters['level'])){
                $query->whereHas('level', function($subQuery) use ($filters){
                      $subQuery->where('name', 'like', '%'.trim($filters['level']).'%');
                });
            }

            if(isset($filters['search']) && !empty($filters['search'])){
                $query->where('name', 'like', '%'.trim($filters['search']).'%');
                $query->orWhere('level_id', $filters['search']);
                $query->orWhere('id', $filters['search']);
                $query->orWhere('score', $filters['search']);
            }                                 
        }
        
        return $query;
    } 
}
