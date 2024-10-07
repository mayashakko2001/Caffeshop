<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Complaint;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function register_admin(Request $request)
    {
        $register = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|max:10',
            'phone' => 'required|string',
        ]);
        if (!$register) {
            return response()->json(['message' => 'error'], 401);
        }
        $input = User::where('email', $request->email)->first();
        if ($input) {
            return response()->json(['message' => 'There is a similar email, please use a new email'], 500);
        }
        $role = 1;
        $reg = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password), 
            'role'=>$role
        ]);
        $token = $reg->createToken('admin_project')->plainTextToken; 
        $response = [
            'user' => $reg,
            'token' => $token,
        ];

        return response()->json(['message' => 'register user success', 'data' => $response], 200); // تصحيح هنا
    }

//.............................................................................................
public function register_customer(Request $request)
{
    // التحقق من صحة المدخلات
    $register = $request->validate([
        'name' => 'required|string',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|max:10',
        'phone' => 'required|string|regex:/^09[0-9]{8}$/',
    ]);

    // لا حاجة للتحقق من $register هنا، لأن validate ستقوم بإرجاع استجابة تلقائيًا إذا فشلت
    // التحقق من وجود البريد الإلكتروني بالفعل
    $input = User::where('email', $request->email)->first();
    if ($input) {
        return response()->json(['message' => 'There is a similar email, please use a new email'], 409); // استخدام 409 للتعارض
    }

    // تحديد الدور (تأكد من أنه صحيح)
    $role = 2; // يجب تحديد القيمة بشكل صحيح

    // إنشاء المستخدم
    $reg = User::create([
        'name' => $request->name,
        'phone' => $request->phone,
        'email' => $request->email,
        'password' => Hash::make($request->password), 
        'role' => $role
    ]);

    // توليد توكن للمستخدم الجديد
    $token = $reg->createToken('customer_project')->plainTextToken; 

    // إعداد الاستجابة
    $response = [
        'user' => $reg,
        'token' => $token,
    ];

    return response()->json(['message' => 'register user success', 'data' => $response], 201); // استخدام 201 لإنشاء جديد
}
//........................................................................................
public function login(Request $request) {
    $valid = $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);
    
    $user = User::where('email', $valid['email'])->first();
    
    if (!$user || !Hash::check($valid['password'], $user->password)) {
        return response()->json([
            'message' => 'The provided credentials are incorrect.'
        ], 401);
    }

    $token = $user->createToken('myapptoken')->plainTextToken;

    $response = [
        'user' => $user,
        'token' => $token,
        'role' => $user->role,
    ];
    
    return response()->json([
        'message' => 'Login successful',
        'data' => $response
    ], 200);
}
//......................................................................................
public function add_complaint(Request $request)
{
    // التحقق من وجود المستخدم الموثق
    $user = Auth::user();

    // التأكد من أن المستخدم موثق
    if (!$user) {
        return response()->json([
            'message' => 'Unauthorized: User not authenticated.'
        ], 401); // 401 Unauthorized
    }

    // التحقق من صحة الطلب
    $request->validate([
        'description' => 'required|string'
    ]);

    // إنشاء شكوى جديدة باستخدام user_id من التوكن
    $data = Complaint::create([
        'description' => $request->description,
        'user_id' => $user->id,  // استخدام معرف المستخدم الموثق
    ]);

    // جلب اسم المستخدم
    $username = $user->name;

    return response()->json([
        'message' => 'Complaint added successfully',
        'data' => $data,
        'username' => $username  // إرجاع اسم المستخدم
    ], 200);
}
//.........................................................................................
public function get_all_complaints()
{
    $complaints = Complaint::with('user')->get();

    // تعديل هيكل البيانات ليشمل اسم المستخدم
    $data = $complaints->map(function ($complaint) {
        return [
            'id' => $complaint->id,
            'description' => $complaint->description,
            'user_id' => $complaint->user_id,
            'username' => $complaint->user->name,  // جلب اسم المستخدم
        ];
    });

    return response()->json(['message' => 'There are all complaints', 'data' => $data], 200);
}
//..........................................................................................
public function get_complaint_byId($id){
    $comp=Complaint::find($id);
    return response()->json(['message' => 'this is a complaint', 'data' => $comp], 200);
}
//...........................................................................................
public function get_complaint_byUserId(Request $request){
$user_id =$request->input('user_id');
$comp=Complaint::find($user_id);
 return response()->json(['message' => 'there are all user is complaints', 'data' => $comp], 200);
}
//...........................................................................................
public function get_all_user(){
    $user=User::all();
    return response()->json(['message'=>'there are all users' , 'data'=>$user],200);
}
//............................................................................................
public function get_user_byId($id){
    $user=User::find($id);
    if(empty($user)){
        return response()->json(['message'=>'Not Found'],404);
    }
    return response()->json(['message'=>'this is user', 'data'=>$user],200);
}
//............................................................................................

public function delete_user($id) {
        $product =Auth::user();
        $pro=$product->id;
        $user=User::find($id);
        if(empty($user)){
            return response()->json(['error'=>'Can not Find',404]);}
        $user->delete();
        return response()->json(['message'=>'delete user success','data'=>$user,200]) ;   
    }//...........................................................................................
public function update_user(Request $request, $id) {
    // Find the user or return a 404 error if not found
    $user = User::find($id);
    if (empty($user)) {
        return response()->json(['message' => 'Not Found'], 404);
    }

    // Validate the incoming request data
    $validatedData = $request->validate([
        'name' => 'sometimes|string|max:255',
        'email' => 'sometimes|email|max:255|unique:users,email,' . $id,
        'phone' => 'sometimes|string|max:15',
        // Add other fields and validation rules as needed
    ]);

    // Update the user with validated data
    $user->update($validatedData);

    return response()->json(['message' => 'User updated successfully', 'data' => $user], 200);
}
//............................................................................................
public function searchUser(Request $request){
    $name=$request->input('name');
    $products=user::where('name','LIKE',"%{$name}%")->get();
    return response()->json($products);
    }
}