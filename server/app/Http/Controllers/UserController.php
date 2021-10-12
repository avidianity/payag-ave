<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetUsersRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\File;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \App\Http\Requests\GetUsersRequest $request
     * @return \Illuminate\Http\Response
     */
    public function index(GetUsersRequest $request)
    {
        $builder = User::query()->with('picture');

        if ($request->has('role')) {
            $builder->where('role', $request->role);
        }

        if ($request->has('roles')) {
            $builder->whereIn('role', $request->roles)
                ->orderBy('role');
        }

        return UserResource::collection($builder->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreUserRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        $user = User::create($request->validated());

        if ($request->hasFile('picture')) {
            $file = File::process($request->file('picture'));

            $user->picture()->save($file);
        }

        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $user->load('picture');
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateUserRequest $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        if ($request->has('email')) {
            $user->changeEmailRequests()->create(['email' => $request->email]);
        }

        $user->update(Arr::except($request->validated(), 'email'));

        if ($request->hasFile('picture')) {
            $file = File::process($request->file('picture'));

            optional($user->picture)->delete();

            $user->picture()->save($file);
        }

        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, User $user)
    {
        if ($request->user()->id === $user->id) {
            throw (new ModelNotFoundException)
                ->setModel($user, $user->id);
        }

        $user->delete();

        return response('', 204);
    }
}
