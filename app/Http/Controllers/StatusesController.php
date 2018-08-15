<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\AuthorizationException;

class StatusesController extends Controller
{
    public function __construct()
    {
        // 所有动作都需要授权
        $this->middleware('auth');
    }

    /**
     * 存储微博
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'content' => 'required|max:140'
        ]);

        Auth::user()->statuses()->create(['content' => $request['content']]);

        return redirect()->back();
    }

    /**
     * 删除微博
     *
     * @param Status $status
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Status $status)
    {
        try {
            $this->authorize('destroy', $status);
            $status->delete();
            session()->flash('success', '微博已被成功删除！');
            return redirect()->back();
        } catch (AuthorizationException $ae) {
            abort(403);
        } catch (\Exception $e) {
            session()->flash('danger', '微博删除失败');
            return back();
        }
    }
}
