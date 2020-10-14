<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use Laravel\Passport\HasApiTokens;
use App\Mail\VerificationCodeMail;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string $social_id
 * @property string $social_identifier
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSocialId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSocialIdentifier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $phone
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Client[] $clients
 * @property-read int|null $clients_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Token[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone($value)
 * @property string $username
 * @property \Illuminate\Support\Carbon|null $verified_at
 * @property int|null $verification_key
 * @property int|null $verification_expire
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereVerificationExpire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereVerificationKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereVerifiedAt($value)
 * @property string|null $verification_created
 * @method static \Illuminate\Database\Eloquent\Builder|User whereVerificationCreated($value)
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'phone',
        'username',
        'email',
        'password',
        'social_id',
        'social_identifier'
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

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'verified_at' => 'datetime',
        'verification_created' => 'datetime',
    ];

    /**
     * Create or get social user
     * @param string $social_identifier
     * @param \Laravel\Socialite\Two\User $social_data
     * @return User|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object
     */
    public static function createOrGetSocialUser(string $social_identifier, \Laravel\Socialite\Two\User $social_data) {
        $user_app = User::where(["social_id" => $social_data->id, 'social_identifier' => $social_identifier])->first();
        if (!$user_app) {
            $user = new User;
            $user->email = $social_data->email ?? "";
            $user->name = $social_data->name;
            $user->username = $social_data->name ?? "";
            $user->phone = $social_data->phone ?? "";
            $user->social_id = $social_data->id;
            $user->social_identifier = $social_identifier;
            $user->password = rand(1,10000);
            $user->save();
            return $user;
        }
        return $user_app;
    }

    /**
     * Make a new key for user
     * @return array
     */
    function makeKey() {
        if ($this->verification_created == null || (Carbon::now() > $this->verification_created->addMinutes(2))) {
            $this->verification_key = "999999";
            $this->verification_created = Carbon::now();
            $this->save();
            Mail::to($this->email)->queue(new VerificationCodeMail($this->email, $this->verification_key));
            return [
                'saved' => true,
                'message' => "A new key was generated"
            ];
        }
        else {
            return [
                'saved' => 'false',
                'message' => 'previous key has not expired, wait 2 minutes'
            ];
        }
    }
}
