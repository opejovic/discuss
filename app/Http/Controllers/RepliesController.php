<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Reply;
use App\Thread;
use Illuminate\Http\Request;

class RepliesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  Thread  $thread
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Thread $thread)
    {
        $validated = request()->validate([
           'body' => ['required', 'min:3']
        ]);

        $reply = $thread->addReply([
            'user_id' => auth()->id(),
            'body' => $validated['body']
        ]);

        return response('Reply created!', 200);

        // return back()->with('flash', 'You have replied to this thread.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function show(Reply $reply)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Reply  $reply
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
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Reply               $reply
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, Reply $reply)
    {
        $this->authorize('update', $reply);

        request()->validate([
            'body' => ['required', 'min:2']
        ]);

        $reply->update([
           'body' => request('body')
        ]);

        return back()->with('flash', 'Reply updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Thread     $thread
     * @param  \App\Reply $reply
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
