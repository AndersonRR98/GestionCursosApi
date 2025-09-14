<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;


class User extends Model
{
        use HasFactory;

     protected $table='users';

    
    protected $fillable = [
        'nombre',
        'email',
        'password',
        'activo',
        'rol_id'
    ];

    protected $allowIncluded=['role','comments','payments','courses'];
    protected $allowFilter=['id','nombre','email','password','activo','created_at'];
    protected $allowSort=['id','nombre','email','password','activo','created_at'];


    
    public function role()
    {
        return $this->belongsTo(Role::class ,'rol_id');
    }
    
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
    
    public function courses()
    {
        return $this->belongsToMany(Course::class ,'course_users','user_id','course_id')
        ->withPivot('progreso', 'completado');  
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
