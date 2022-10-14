<?php

namespace App\Models;

use Ramsey\Uuid\Uuid as Generator;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\Factories\HasFactory;


/**
 * App\Models\moduleMenu
 *
 * @property string $id
 * @property string $module_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|Permission[] $permissions
 * @property-read int|null $permissions_count
 * @method static \Illuminate\Database\Eloquent\Builder|moduleMenu newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|moduleMenu newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|moduleMenu query()
 * @method static \Illuminate\Database\Eloquent\Builder|moduleMenu whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|moduleMenu whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|moduleMenu whereModuleName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|moduleMenu whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class moduleMenu extends Model
{
    use HasFactory;

    protected $fillable = [
        'id','module_name'
    ];

    public function getIncrementing()
    {
        return false;
    }

    public function getKeyType()
    {
        return 'string';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            try {
                $model->id = Generator::uuid4()->toString();
            } catch (\Exception $e) {
                abort(500);
            }
        });
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class,'module_id','id');
    }

}
