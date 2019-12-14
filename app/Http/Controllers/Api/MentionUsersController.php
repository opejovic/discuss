<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MentionUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Support\Collection
     */
    public function index()
    {
        // @TODO Refactor
        // Cache the users, and renew the Cache, daily
        // or hourly, or whenever there is a new user created.
        return User::all();
    }
}
