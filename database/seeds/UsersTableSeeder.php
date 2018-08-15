<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 创建假用户
        $users = factory(User::class)->times(50)->make();
        // 临时显示 User 模型里指定的隐藏属性 $hidden,将生成假用户列表数据批量插入到数据库中
        User::insert($users->makeVisible(['password', 'remember_token'])->toArray());

        // 对第一个用户更新，方便我们的测试
        $user = User::find(1);
        $user->name = 'jack';
        $user->email = 'security.2009@live.cn';
        $user->is_admin = true;
        $user->save();
    }
}
