<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphTo;



class Comment extends Model
{
    use HasFactory;

     protected $fillable = [
        'texto',
        'user_id',
        'commentable_id',
        'commentable_type'
        
    ];
    protected $allowIncluded=['user','commentable'];
    protected $allowFilter=['id','texto','created_at'];
    protected $allowSort=['id','texto','created_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    
    public function commentable()
    {
        return $this->morphTo();
    }


   public function scopeIncluded(Builder $query): void
{
    if (!request()->filled('included')) return;

    $relations = explode(',', request('included'));

    foreach ($relations as $relation) {
        // Para morphTo solo cargamos la relación, no columnas
        if ($relation === 'commentable') {
            $query->with('commentable');
        } else {
            $query->with($relation);
        }
    }
}

    /**
     * Scope para filtros (?filter[contenido]=Laravel&filter[commentable.title]=eloquent)
     */
     public function scopeFilter(Builder $query)
    {

        if (empty($this->allowFilter) || empty(request('filter'))) {
            return;
        }

        $filters = request('filter');

        $allowFilter = collect($this->allowFilter);

        foreach ($filters as $filter => $value) {

            if ($allowFilter->contains($filter)) {

                $query->where($filter, 'LIKE', '%' . $value . '%');//nos retorna todos los registros que conincidad, asi sea en una porcion del texto
            }
        }

    }

    /**
     * Scope para ordenamiento (?sort=-created_at,commentable.title)
     */
  public function scopeSort(Builder $query): void
{
    if (!request()->filled('sort') || empty($this->allowSort)) return;

    foreach (explode(',', request('sort')) as $field) {
        $direction = str_starts_with($field, '-') ? 'desc' : 'asc';
        $field = ltrim($field, '-');

        if (!in_array($field, $this->allowSort)) continue;

        if (str_contains($field, '.')) {
            [$relation, $relationField] = explode('.', $field, 2);
            $query->with([$relation => fn($q) => $q->orderBy($relationField, $direction)]);
        } else {
            $query->orderBy($field, $direction);
        }
    }
}

    public function scopeGetOrPaginate(Builder $query)
    {
        if (request('perPage')) {
            $perPage = intval(request('perPage'));

            if ($perPage) {
                return $query->paginate($perPage);
            }
        }

        return $query->get();
    }
}
