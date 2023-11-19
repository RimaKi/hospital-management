<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\DoctorService;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function addUser(Request $request)
    {
        try {
            $data = $request->only(['name', 'phone', 'nationalId', 'email', 'birthday',
                'photo']);
            $request->validate([
                'userType' => ["required",'string', 'max:255'],
                'name' => ['required', 'string', 'max:255'],
                'phone' => ['string', 'min:8', 'max:16'],
                'nationalId' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'birthday' => ['required', 'date'],
                'photo' => ['file', 'mimes:png,jpg,jpeg'],
                'graduation' => ['date'],
                'specializationId' => ['string', 'max:255'],
                'education' => ['string', 'max:255']
            ], $request->all());
            $data['password'] = Hash::make(Carbon::make($data['birthday'])->format('dmY') . $data['phone']);
            if ($request->hasFile('photo')) {
                $data['photo'] = (new UserService())->saveFile('photo', '/users', $request->allFiles());
            }
            $user = new User();
            $user->setRawAttributes($data);
            $user->save();
            $user->assignRole($request->get('userType'));

            if ($request->get('userType') == "doctor" && $request->has('graduation') && $request->has('specializationId')) {
                $doctorData = $request->only(['specializationId','graduation', "education"]);
                $doctorData['userId'] = $user->id;
                if (!(new DoctorService())->save($doctorData)) {
                    throw new \Exception('failed');
                }
            }

            return \response()->json([
                'error' => 0,
                'msg' => 'successfully',
            ]);
        } catch (\Exception $e) {
            return \response()->json([
                'error' => 1,
                'msg' => $e->getMessage()
            ]);

        }
    }

    public function login(Request $request)
    {

        try {
            $data = $request->all();
            $request->validate([
                'email' => ['required', 'string', 'email', 'max:255'],
                'password' => ['required', 'string', 'min:8'],
            ], $data);
            if (!Auth::attempt([
                'email' => $data['email'],
                'password' => $data['password']
            ])) {
                return \response()->json([
                    'error' => 1,
                    'msg' => 'wrong email or password'
                ]);
            }
            $user = \auth()->user();
            return \response()->json([
                'error' => 0,
                'token' => $user->createToken($request->ip())->plainTextToken,
                'msg' => 'successfully'
            ]);

        } catch (\Exception $e) {
            return \response()->json([
                'error' => 1,
                'msg' => $e->getMessage()
            ]);
        }
    }

    public function edit(Request $request, $userId = null)
    {
        try {
            $data1 = $request->only(['education', 'experience', 'availableDays','specializationId'
                , 'availableHours', 'sessionTime']);
            $data2=$request->only(['nationalId','name','phone','photo', "password"]);
            $request->validate([
                'name' => ['string', 'max:255'],
                'nationalId' => ['string', 'max:255'],
                'phone' => ['string', 'min:8', 'max:16'],
                'photo' => ['file', 'mimes:png,jpg,jpeg'],
                'specializationId' => ['string'],
                'education' => ['string'],
                'experience' => ['string'],
                'availableDays' => ["string"],
                'availableHours' => ["string"],
                'sessionTime' => ['integer'],
            ],[...$data1,...$data2]);

            $user = $userId == null ? \auth()->user() : (new UserService())->getOne($userId);
            if ($request->hasFile('photo')) {
                $photo = $user->photo;
                if($photo !=null || $photo!=''){
                    Storage::disk('public')->delete($photo);
                }
                $data2['photo'] = (new UserService())->saveFile('photo', '/photo', $request->allFiles());
            }
            if($data2!=null){
                if ($request->has("password")) {
                    $data2["password"] = Hash::make($data2["password"]);
                }
                if (!(new UserService())->update($data2, ['id' => $user->id])) {
                    throw  new \Exception('failed1');
                }
            }
            if ($user->hasRole('doctor') && $data1!=null) {
                if (!(new DoctorService())->update($data1, ['userId' => $user->id])) {
                    throw  new \Exception('failed2');
                }
            }
            return \response()->json([
                'error' => 0,
                'msg' => "update Successfully"
            ]);
        } catch (\Exception $e) {
            return \response()->json([
                'error' => 1,
                'msg' => $e->getMessage()
            ]);
        }
    }

    public function logout()
    {
        try {
            \auth()->user()->tokens()->delete();
            return \response()->json([
                'error' => 0,
                'msg' => 'successfully'
            ]);
        } catch (\Exception $e) {
            return \response()->json([
                'error' => 1,
                'msg' => $e->getMessage()
            ]);
        }
    }

    public function profile(Request $request)
    {
        $data = $request->only(['id']);
        $request->validate([
            'id' => ['string']
        ], $data);

        if ($request->has('id')) {
            $user = (new UserService())->getFirst(['nationalId' => $data['id']]);
            if ($user == null) {
                throw new \Exception('not exist');
            }
        } else {
            $user = \auth()->user();
        }

        if($user->hasRole('doctor')){
          $doctor=$user->doctor;
            $user=array_merge($user->toArray(),$doctor->toArray());
        }

        return \response()->json([
            'error' => 0,
            'profile' => $user,

        ]);
    }

    public function change_password(Request $request)
    {

            $data = $request->only(['password', 'oldPassword', 'password_confirmation']);
            $request->validate([
                'password' => ['required', 'string', 'min:8', 'max:255', "confirmed"],
                'oldPassword' => ['required', 'string', 'min:8', 'max:255',]
            ], $data);
            $user = auth()->user();
            if (!password_verify($data['oldPassword'], $user->password)) {
                throw new \Exception('wrong password');
            }
            $data['password'] = Hash::make($data['password']);
            if (!(new UserService())->update(['password' => $data['password']], ['uniqueId' => $user->nationlaId])) {
                throw new \Exception('your password not change');
            }
            return response()->json([
                'error' => 0,
                'msg' => 'change password successfully'
            ]);
    }

}
