<?php

namespace App\Http\Controllers\Api2;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api2\RegisterRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\Api2\UserResource;
use App\Models\Group;
use App\Models\Setting;
use App\Models\User;
use App\Support\HelperSupport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use function PHPUnit\Framework\throwException;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['update']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {

        $user = User::orWhere(['username' => $request->username, 'email' => $request->username])->first();
        if (!$user) {
            HelperSupport::SendError(['incorrect' => 'تأكد من بيانات الدخول']);
        }
        if (!\Hash::check($request->password, $user->password)) {
            HelperSupport::SendError(['incorrect' => 'تأكد من بيانات الدخول']);
        }
        $token = $user->createToken('user')->plainTextToken;
        return HelperSupport::sendData(['user' => new UserResource($user), 'token' => $token]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(RegisterRequest $request)
    {
        $parent=null;

        if (!empty($request->affiliate)) {
            $parent = User::whereNotNull('affiliate')->where('affiliate', $request->affiliate)->first();
        }
        $data = [
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'name' => $request->name,
            'address' => $request->address,
            'active' => true,
            'password' => bcrypt($request->password),
            'group_id' => Group::orderBy('sort')->first()->id,
        ];

        if ($parent != null && !$parent->hasRole('partner')) {
            $data['affiliate_id'] = $parent->id;
        }
        if ($parent != null && $parent->hasRole('partner')) {
            $data['user_id'] = $parent->id;
        }
        $user = User::create($data);


        $token = $user->createToken('user')->plainTextToken;
        $user->update([
            'token' => $token,
        ]);
        return HelperSupport::sendData(['user' => new UserResource($user), 'token' => $token]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return HelperSupport::sendData(['user' => new UserResource(auth()->user())]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProfileRequest $request)
    {
        $data = [
            'name' => $request->name,
//            'username' => $request->username,
            'phone' => $request->phone,
//            'email' => $request->email,
            'address' => $request->address,
        ];

        auth()->user()->update($data);
        if ($request->hasFile('img')) {
            auth()->user()->clearMediaCollection('image');
            try {
                auth()->user()->addMedia($request->img)->toMediaCollection('image');
            } catch (FileDoesNotExist | FileIsTooBig $e) {
            }

        }
        return HelperSupport::sendData(['user' => new UserResource(auth()->user())]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function setPassword(Request $request)
    {
        try{
            if(!Hash::check($request->oldpassword,auth()->user()->password)){
                throw (new \Exception('يرجى إدخال كلمة المرور القديمة بشكل صحيح'));
            }
            if(empty($request->newpassword)){
                throw (new \Exception('يرجى إدخال كلمة المرور'));
            }
            if($request->newpassword != $request->confirmpassword){
                throw new \Exception('كلمة المرور الجديدة غير متطابقة');
            }

            auth()->user()->update([
                'password'=>bcrypt($request->newpassword),

            ]);

            return HelperSupport::sendData(['user' => new UserResource(auth()->user())]);
        }catch (\Exception | \Error $e){
            HelperSupport::SendError('خطأ في البيانات',$e->getMessage());
        }
    }




    public function setHash(Request $request){

        $old=auth()->user()->hash;
        $data=[
            'is_hash'=> (bool)$request->is_hash,
        ];
        if($old!=null && $old!=$request->old){
            HelperSupport::SendError('خطأ في الطلب','يرجى إدخال الكلمة القديمة بشكل صحيح');
        }
        if($old!=null && $request->old !=$old && $request->password!=null){
            HelperSupport::SendError('خطأ في الطلب','يرجى إدخال الكلمة القديمة بشكل صحيح');
        }

        if($old!=null && $request->old==$old &&  $request->password!=null){
            $data['hash']=$request->password;
        }elseif ($old==null){
            $data['hash']=$request->password;
        }
       if((bool)$request->is_hash ==false){
           $data['hash']='';
           $data['is_hash']=false;
       }
        auth()->user()->update($data);
        return HelperSupport::sendData(['user' => new UserResource(auth()->user())]);
    }
}
