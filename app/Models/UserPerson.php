<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cerbero\QueryFilters\FiltersRecords;
use Carbon\Carbon;

class UserPerson extends Model
{
    use FiltersRecords;
    protected $connection = 'mysql_wave';

    protected $fillable = [
        'name',
        'gender',
        'country',
        'b_day',
        'number_dives'
    ];

    protected $table = 'user_persons';
    protected $appends = ['age'];

    public function getAgeAttribute()
    {
        $b_day = $this->attributes['b_day'];
        return Carbon::parse($b_day)->age;
    }

    public function user()
    {
        return $this->morphOne(User::class, 'userable');
    }

    public static function create(array $attributes = [])
    {
        //$files = $attributes['certificates'];
        $model = static::query()->create($attributes);


        /*foreach ($files as $file) {
            $name = md5($model->id . microtime());
            $link = 'user/certificates/' . $name . '.' . $file->getClientOriginalExtension();
            Storage::put('public/' . $link, file_get_contents($file->getRealPath()));
            UserCertificate::create([
                'user_person_id' => $model->id,
                'link' => $link
            ]);
            $value = $value * 2;
        }*/
        return $model;
    }

    public static function relation()
    {
        $post = UserPerson::find(1);

        return $post->user;
    }
}
