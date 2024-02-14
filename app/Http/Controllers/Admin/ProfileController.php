<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\UserDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProfilePasswordUpdateRequest;
use App\Http\Requests\Admin\ProfileUpdateRequest;
use App\Models\User;
use App\Traits\FileUploadTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    use FileUploadTrait;
    //
    function index(): View{
        return view('admin.profile.index');
    }
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('admin.user.edit', compact('user'));
    }
    public function createUser()
    {
        return view('admin.user.create');
    }
    public function save(Request $request)
    {
        $request->validate([
            'name' => ['required', 'max:255'],
            'email' => ['required','email','max: 50','unique:users,email'],
            'password' => ['required', 'min:5'],
        ]);
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->role = 'admin';
        $user->save();
        toastr()->success('User Created Successfully');
        return redirect()->route('user');
    }
    function updateProfile(ProfileUpdateRequest $request):RedirectResponse{
        $user=Auth::user();
        $imagePath=$this->uploadImage($request,'avatar','','/uploads');
        $user->name=$request->name;
        $user->email=$request->email;
        $user->avatar=isset($imagePath)?$imagePath:$user->avatar;
        $user->save();

        toastr('Profile updated successfully','success');
        return redirect()->back();
    }

    function updateUserProfile(Request $request){

       $request->validate([
            'name' => 'required',
            'email' => ['required','email','max: 50','unique:users,email,'.$request->id],
        ]);
        // dd($request->name);
        $user=User::findOrFail($request->id);
        $imagePath=$this->uploadImage($request,'avatar','','/uploads');
        $user->name=$request->name;
        $user->email=$request->email;
        
        $user->password=!empty($request->password)? bcrypt($request->password) : $user->password;
        $user->avatar=isset($imagePath)?$imagePath:$user->avatar;
        $user->save();

        toastr('Profile updated successfully','success');
        return redirect()->back();
    }
    function updatePassword(ProfilePasswordUpdateRequest $request): RedirectResponse{
        // dd($request->all());
        $user=Auth::user();
        $user->password=bcrypt($request->password);
        $user->save();
        toastr('Password updated successfully','success');
        return redirect()->back();
    }
    function users(UserDataTable $dataTable)
    {

        return $dataTable->render('admin.user.index');

    }
    public function delete(string $id): Response
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return response(['status' => 'success', 'message' => 'Deleted Successfully!']);
        } catch (\Exception $e) {
            return response(['status' => 'error', 'message' => 'something went wrong!']);
        }
    }
}
