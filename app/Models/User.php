<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\PasswordResetNotification;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Cerbero\QueryFilters\FiltersRecords;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class User extends Authenticatable
{
    use Notifiable, FiltersRecords;

    protected $table = 'wave.users';
    protected $connection = 'mysql_wave';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'userable_type',
        'email',
        'password',
        'note',
        'active',
        'is_verified',
        'userable_id',
        'user_occupation_id',
        'token'
    ];



    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $guard_name = 'api';



    /**
     *  Create user and user type
     *
     * @var array
     */
    public static function create(array $attributes = [])
    {

        DB::beginTransaction();

        $newUserType = UserPerson::class::create($attributes);

        //$attributes['user_type'] = $newUserType->getMorphClass();
        $attributes['userable_id'] = $newUserType->id;
        $attributes['token'] = bin2hex(random_bytes(6)); //each byte is 2 hex, so 12 chars

        $model = static::query()->create($attributes);

        DB::commit();

        return $model;
    }

    public function surveyPrograms()
    {
        return $this->belongsToMany(SurveyProgram::class, 'survey_program_users', 'user_id', 'survey_program_id')
            ->using(SurveyProgramUser::class);
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_users', 'user_id', 'project_id')
            ->using(ProjectUser::class);
    }

    public function workspaces()
    {
        return $this->belongsToMany(Workspace::class, 'workspace_users', 'user_id', 'workspace_id')
            ->using(WorkspaceUser::class);
    }

    //user has many UserHasDivingSpot with user_id as key

    /**
     * Gets the rest of the user information depending on the type
     */
    // public function userType()
    // {
    //     return $this->morphTo('user_type', 'user_type_id');
    // }

    /**
     * Send a new  Password Reset Notification
     *
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PasswordResetNotification($token));
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function userPerson()
    {
        return $this->belongsTo(UserPerson::class, 'userable_id');
    }
}
