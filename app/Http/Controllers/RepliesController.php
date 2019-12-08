<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Thread;
use App\Rules\SpamRule;
use Illuminate\Http\Request;

class RepliesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  Thread $thread
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index(Thread $thread)
    {
        return $thread->replies()->paginate(15);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Thread $thread
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store(Thread $thread)
    {
        $validated = request()->validate([
           'body' => ['required', 'min:3', new SpamRule]
        ]);

        $thread->addReply([
            'user_id' => auth()->id(),
            'body' => $validated['body']
        ]);

        return response('Reply created!', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  Reply $reply
     * @return \Illuminate\Http\Response
     */
    public function show(Reply $reply)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Reply $reply
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Reply $reply)
    {
        return view('threads.show', [
            'thread' => $reply->thread->append('hasBeenLiked'),
            'replies' => $reply->thread->replies()->paginate(25),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  Reply   $reply
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, Reply $reply)
    {
        $this->authorize('update', $reply);

        request()->validate([
            'body' => ['required', 'min:2', new SpamRule]
        ]);

        $reply->update([
           'body' => request('body')
        ]);

        return response('Reply updated!', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Thread $thread
     * @param  Reply  $reply
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Thread $thread, Reply $reply)
    {
        $this->authorize('delete', $reply);

        $reply->delete();

        if (request()->expectsJson()) {
            return response(['Reply deleted'], 200);
        }

        return back();
    }
}
