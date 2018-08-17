<?php

namespace App\Models;

use Auth;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use phpDocumentor\Reflection\Types\Parent_;
use App\Notifications\ResetPassword;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function boot()
    {
        parent::boot();
        // 用户创建之前，生成激活token
        static::creating(function ($user) {
            $user->activation_token = str_random(30);
        });
    }

    public function statuses()
    {
        return $this->hasMany(Status::class);
    }

    /**
     * 获取当前用户关注的人发布过的所有微博动态
     */
    public function feed()
    {
        // return $this->statuses()->orderBy('created_at', 'desc');

        $user_ids = Auth::user()->followings->pluck('id')->toArray(); // 取出所有关注用户的信息
        array_push($user_ids, Auth::user()->id); // 将当前用户的 id 加入到 user_ids 数组中

        // 取出所有用户的微博动态并进行倒序排序
        return Status::whereIn('user_id', $user_ids)
            ->with('user')
            ->orderBy('created_at', 'desc');
    }

    public function gravatar($size = '100')
    {
        $hash = md5(strtolower(trim($this->attributes['email'])));
        return "http://www.gravatar.com/avatar/$hash?s=$size";
    }

    /**
     * 发送密码重置通知
     *
     * @param string $token
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    /**
     * 获取粉丝关系列表
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function followers()
    {
        // 在 Laravel 中会默认将两个关联模型的名称进行合并，并按照字母排序，因此我们生成的关联关系表名称会是 user_user。
        // 我们也可以自定义生成的名称，把关联表名改为 followers
        // 传递额外参数至 belongsToMany 方法来自定义数据表里的字段名称
        return $this->belongsToMany(User::class, 'followers', 'user_id', 'follower_id');
    }

    /**
     * 获取用户关注人列表
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function followings()
    {
        // 第三个参数是定义在关联中的模型外键名，而第四个参数则是要合并的模型外键名
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'user_id');
    }

    /*
        $user = App\Models\User::find(1);
        id 为 1 的用户去关注 id 为 2 和 id 为 3 的用户时，可使用 attach 方法来进行关注
        $user->followings()->attach([2, 3]);

        对用户进行关注之后，我们可以通过下面方法来输出关联的 id 数组查看创建结果
        $user->followings()->allRelatedIds()->toArray();

        attach 方法有个问题，在我们对同一个 id 进行添加时，则会出现 id 重复的情况
        $user->followings()->attach([2]);
        $user->followings()->allRelatedIds()->toArray(); 出现两条 user_id 为 2 的重复数据

        为了解决这种问题，我们可以使用 sync 方法。
        sync 方法会接收两个参数，第一个参数为要进行添加的 id，第二个参数则指明是否要移除其它不包含在关联的 id 数组中的 id，
        true 表示移除，false 表示不移除，默认值为 true

        $user->followings()->sync([3], false); 关注一个新用户的时候，仍然要保持之前已关注用户的关注关系，因此不能对其进行移除，所以在这里我们选用 false
        $user->followings()->allRelatedIds()->toArray();

        detach 来对用户进行取消关注的操作
        $user->followings()->detach([2,3]);
        $user->followings()->allRelatedIds()->toArray();
     */

    /**
     * 关注
     *
     * @param $user_ids
     */
    public function follow($user_ids)
    {
        if (!is_array($user_ids)) {
            $user_ids = compact('user_ids');
        }

        $this->followings()->sync($user_ids, false);
    }

    /**
     * 取消关注
     *
     * @param $user_ids
     */
    public function unFollow($user_ids)
    {
        if (!is_array($user_ids)) {
            $user_ids = compact('user_ids');
        }

        $this->followings()->detach($user_ids);
    }

    /**
     * 判断当前登录的用户 A 是否关注了用户 B
     *
     * @param $user_id
     * @return mixed
     */
    public function isFollowing($user_id)
    {
        return $this->followings->contains($user_id);
    }
}
