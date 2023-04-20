<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Intervention\Image\ImageManagerStatic as Image;
class UserController extends Controller
{
    public function __construct()
    {
        //create read update delete
        $this->middleware(['permission:users_read'])->only('index');
        $this->middleware(['permission:users_create'])->only('create');
        $this->middleware(['permission:users_update'])->only('edit');
        $this->middleware(['permission:users_delete'])->only('destroy');

    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->search){

            $users = User::where('first_name', 'like', '%' . $request->search . '%')
                         ->orWhere('last_name', 'like', '%' . $request->search . '%')
                         ->whereHasRole(['admin'])
                         ->paginate(5);
        }else{

            $users = User::whereHasRole(['admin'])->paginate(5);

        }



        return view('dashboard.users.index',compact('users'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:users',
            'image' => 'image',
            'password' => 'required|confirmed',
            'permissions' => 'required|min:1',

        ]);

        $request_data = $request->except('password','password_confirmation','permissions','image');
        $request_data['password'] = bcrypt($request->password);

        if($request->image){
            Image::make($request->image)->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path('uploads/user_images/' . $request->image->hashName()));

            $request_data['image'] = $request->image->hashName();
        }

        $user= User::create($request_data);
        $user->addRole('admin');
        $user->syncPermissions($request->permissions);

        session()->flash('success',__('site.added_successfully'));
        return redirect()->route('dashboard.users.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( User $user)
    {
        return view('dashboard.users.edit',compact('user'));


    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => ['required',Rule::unique('users')->ignore($user->id),],
            'image' => 'image',
            'permissions' => 'required|min:1',

        ]);

        $request_data = $request->except('permissions','image');

        if($request->image){

            if ($user->image != 'default.png') {

                Storage::disk('public_uploads')->delete('/user_images/'. $user->image);
            }


            Image::make($request->image)->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
            })->save(public_path('uploads/user_images/' . $request->image->hashName()));

            $request_data['image'] = $request->image->hashName();
        }

        $user->update($request_data);



        $user->syncPermissions($request->permissions);

        session()->flash('success',__('site.updated_successfully'));

        return redirect()->route('dashboard.users.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if ($user->image != 'default.png') {

            Storage::disk('public_uploads')->delete('/user_images/'. $user->image);
        }

        $user->delete();

        session()->flash('success',__('site.deleted_successfully'));
        return redirect()->route('dashboard.users.index');

    }
}
