<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;


class Course extends Model
{
    use HasFactory;

     protected $fillable = [
        'titulo',
        'descripcion',
        'precio',
        'instructor_id',
        'category_id',
    ];
    protected $allowIncluded=['category','users','lessons','payments','comments'];
    protected $allowFilter=['id','titulo','descripcion','precio','created_at'];
    protected $allowSort=['id','titulo','descripcion','precio','created_at'];
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
     public function users()
    {
        return $this->belongsToMany(User::class ,'course_users','user_id','course_id')
        ->withPivot('progreso', 'completado');  
    }
    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }
     public function payments()
    {
        return $this->hasMany(Payment::class);
    }
     
      public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
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


