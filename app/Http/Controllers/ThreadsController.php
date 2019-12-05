<?php

namespace App\Http\Controllers;

use App\Thread;
use App\Channel;
use App\Utilities\Spam;
use Illuminate\Http\Request;
use App\Filters\ThreadFilters;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class ThreadsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  ThreadFilters $filters
     * @param  Channel       $channel
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(ThreadFilters $filters, Channel $channel = null)
    {
        $threads = $this->getThreads($filters, $channel);

        return view('threads.index', ['threads' => $threads]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('threads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Utilities\Spam      $spam
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Throwable
     */
    public function store(Request $request, Spam $spam)
    {
        $attributes = $request->validate([
            'title'      => ['required', 'min:2', 'not_regex:/(.)\\1{4,}/'],
            'body'       => ['required', 'min:2', 'not_regex:/(.)\\1{4,}/'],
            'channel_id' => ['required', 'exists:channels,id'],
        ]);

        $spam->detect(request('body'));

        $thread = Auth::user()->publishThread($attributes);

        return redirect($thread->path())->with('flash', 'Thread created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Channel $channel
     * @param  \App\Thread  $thread
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function show(Channel $channel, Thread $thread)
    {
        if (auth()->check()) {
            auth()->user()->read($thread);
        }

        return view('threads.show', [
            'thread' => $thread->append(['hasBeenLiked', 'isSubscribedTo']),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Thread $thread
     * @return \Illuminate\Http\Response
     */
    public function edit(Thread $thread)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Thread              $thread
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Thread $thread)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Thread $thread
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function destroy(Thread $thread)
    {
        $this->authorize('update', $thread);

        $thread->delete();

        return redirect('home')->with('flash', 'Thread deleted!');
    }

    /**
     * @param  ThreadFilters $filters
     * @param  Channel       $channel
     * @return Collection
     */
    protected function getThreads(ThreadFilters $filters, Channel $channel = null): Collection
    {
        $threads = Thread::latest();

        if ($channel) {
            $threads = $channel->threads()->latest();
        }

        return $threads->filter($filters)->get();
    }
}
