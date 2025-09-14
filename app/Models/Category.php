<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

      protected $fillable = [
        'nombre',
    ];
    protected $allowIncluded=['courses'];
    protected $allowFilter=['id','nombre','courses.titulo','created_at'];
    protected $allowSort=['id','nombre','created_at'];

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function scopeIncluded(Builder $query): void
{
    $included = request('included');

    if (!$included || empty($this->allowIncluded)) {
        return;
    }
    // separa y filtra solo las relaciones permitidas
    $relations = collect(explode(',', $included))
        ->intersect($this->allowIncluded)
        ->toArray();

    if (!empty($relations)) {
        $query->with($relations);
    }
}


public function scopeFilter(Builder $query): void
{
    if (!request()->filled('filter') || empty($this->allowFilter)) return;

    foreach (request('filter') as $column => $value) {
        if (!in_array($column, $this->allowFilter)) continue;

        if (str_contains($column, '.')) {
            [$relation, $field] = explode('.', $column, 2);
            $query->whereHas($relation, fn($q) => $q->where($field, 'LIKE', "%$value%"));
        } elseif ($column === 'created_at') {
            $query->whereDate($column, $value);
        } else {
            $query->where($column, 'LIKE', "%$value%");
        }
    }
}

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
