<?php

namespace App\Http\Controllers;

use Auth;
use Mail;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', [
            'except' => ['create', 'show', 'store', 'confirmEmail']
        ]);

        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }

    /**
     * 用户列表页
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $users = User::paginate(10);
        return view('users.index', compact('users'));
    }


    /**
     * 渲染用户注册页面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * 渲染用户中心页面
     *
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(User $user)
    {
        $statuses = $user->statuses()->orderBy('created_at', 'desc')->paginate(10);
        return view('users.show', compact('user', 'statuses'));
    }

    /**
     * 执行用户注册操作
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:50',
            'email' => 'required|email|unique:users|max:50',
            'password'=> 'required|confirmed|min:6',
        ]);

        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => bcrypt($request['password']),
        ]);

        $this->sendEmailConfirmationTo($user);
        session()->flash('success', '验证邮件已发送到你的注册邮箱上，请注意查收。');
        return redirect('/');
        // 自动登录
        //Auth::login($user);
        // session()->flash('success', '欢迎，您将在这里开启一段新的征途~');
        //return redirect()->route('users.show', [$user]);
    }

    private function sendEmailConfirmationTo($user)
    {
        $view = 'emails.confirm';
        $data = compact('user');
        $to = $user->email;
        $subject = "感谢注册 csthink 应用！请确认你的邮箱。";
        Mail::send($view, $data, function ($message) use ($to, $subject) {
            $message->to($to)->subject($subject);
        });
    }

    /**
     * 渲染修改个人资料页面
     *
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(User $user)
    {
        try {
            $this->authorize ('update', $user);
            return view ('users.edit', compact ('user'));
        } catch (AuthorizationException $ae) {
            abort(403, '对不起，你无权访问此页面！');
        }
    }

    /**
     * 执行更新个人资料操作
     *
     * @param User $user
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(User $user, Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:50',
            'password' => 'nullable|confirmed|min:6'
        ]);

        try {
            /**
             * 这里 update 是指授权类里的 update 授权方法，$user 对应传参 update 授权方法的第二个参数
             * 调用时，默认情况下，我们不需要传递第一个参数，也就是当前登录用户，框架会自动加载当前登录用户
             */
            $this->authorize('update', $user);
            $data = [];
            $data['name'] = $request['name'];
            if ($request['password']) {
                $data['password'] = bcrypt($request['password']);
            }

            $user->update($data);
            session()->flash('success', '个人资料更新成功');
            return redirect()->route('users.show', [$user]);
        } catch (AuthorizationException $ae) {
            abort(403);
        }
    }

    /**
     * 删除用户
     *
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        try {
            $this->authorize('destroy', $user);
            $user->delete();
            session()->flash('success', '成功删除用户');
            return back();
        } catch (AuthorizationException $ae) {
            abort(403);
        } catch (\Exception $e) {
            session()->flash('danger', '删除失败');
            return back();
        }

    }

    /**
     * 激活用户账号
     *
     * @param $token
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirmEmail($token)
    {
        $user = User::where('activation_token', $token)->firstOrFail();

        // 更新账号激活状态
        $user->activation_token = null;
        $user->activated = true;
        $user->save();

        // 自动登录
        Auth::login($user);

        session()->flash('success', $user->name .  '欢迎您，账号激活成功！');
        return redirect()->route('users.show', [$user]);
    }

    /**
     * 显示用户的关注人列表
     *
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function followings(User $user)
    {
        $users = $user->followings()->paginate(10);
        $title = '关注的人';
        return view('users.show_follow', compact('users', 'title'));
    }

    /**
     * 显示用户的粉丝列表
     *
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function followers(User $user)
    {
        $users = $user->followers()->paginate(10);
        $title = '粉丝';
        return view('users.show_follow', compact('users', 'title'));
    }
}
