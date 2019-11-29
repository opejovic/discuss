<?php

namespace App\Http\Controllers;

use App\Thread;
use Illuminate\Http\Request;

class ThreadSubscriptionsController extends Controller
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
     * @param  Thread                   $thread
     * @param  \Illuminate\Http\Request $request
     * @return void
     */
    public function store(Thread $thread, Request $request)
    {
        abort_if(auth()->user()->isSubscribedTo($thread), 403);

        $thread->subscribe();

        return response('Subscribed!', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function show(Thread $thread)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function edit(Thread $thread)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Thread  $thread
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
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Thread $thread)
    {
        abort_unless(auth()->user()->isSubscribedTo($thread), 403);

        $thread->unsubscribe();

        return response('Unsubscribed!', 200);
    }
}
