<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Catogery;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Complaint;
use App\Models\OrderProduct;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
class ProductController extends Controller
{
  
public function getAllproducts(){
  $product =Product::all();
  return response()->json($product);

}
//.....................................................................
public function getProductById($id){
 $product = Product::find($id);
 if($product){
    return response()->json($product);
 }
}
//.....................................................................
public function addProduct(Request $request) {
    $product = Auth::user();
    
    // Validate the request
    $validatedData = $request->validate([
        'title' => 'required',
        'price' => 'required|numeric',
        'description' => 'required',
        'catogery_id' => 'required|exists:catogeries,id', // تأكد من استخدام الاسم الصحيح
        'quantity' => 'required|integer|min:1',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // تأكد من أن الصورة مطلوبة وأنها صورة
    ]);

    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imageName = time() . '_' . $image->getClientOriginalName(); // إضافة توقيت لتجنب تكرار الأسماء
        
        try {
            $path = $image->storeAs('public/uploads', $imageName); // حفظ الصورة في المجلد المحدد
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to save image: ' . $e->getMessage()], 500);
        }

        // Create the product
        $pro = Product::create([
            'title' => $validatedData['title'],
            'price' => $validatedData['price'],
            'catogery_id' => $validatedData['catogery_id'], // استخدام الاسم الصحيح
            'quantity' => $validatedData['quantity'],
            'description' => $validatedData['description'],
            'image' => 'uploads/' . $imageName,
            'user_id' => $product->id, // ربط المنتج بالمستخدم
        ]);

        return response()->json(['message' => 'Product added successfully', 'data' => $pro], 201);
    } else {
        return response()->json(['error' => 'Image is required.'], 422);
    }
}
//.....................................................................
public function deleteProduct($id)
{
    try {
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json(['message' => 'Product deleted successfully.'], 200);
    } catch (ModelNotFoundException $e) {
        return response()->json(['error' => 'Product not found.'], 404);
    } catch (\Exception $e) {
        return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
    }
}
//.....................................................................
public function updateProduct(Request $request ,$id){
    $validatedData = $request->validate([
        'title' => 'nullable|string|max:255',
        'price' => 'nullable|numeric',
        'description' => 'nullable|string',
        'category_id' => 'nullable|exists:categories,id', // تأكد من وجود الفئة
        'quantity' => 'nullable|integer',
        'image' => 'nullable|image', // الصورة اختيارية
    ]);

    // العثور على المنتج
    $product = Product::findOrFail($id);

    // تحديث القيم فقط إذا كانت موجودة في الطلب
    $product->fill($validatedData);

    // إذا كانت هناك صورة، يجب عليك معالجتها
    if ($request->hasFile('image')) {
        // حفظ الصورة ومعالجة المسار (يمكنك تعديل هذا حسب احتياجاتك)
        $path = $request->file('image')->store('images', 'public');
        $product->image = $path;
    }

    $product->save();

    return response()->json(['message' => 'Product updated successfully', 'product' => $product]);
}
//.....................................................................
public function searchProduct(Request $request){
$title=$request->input('title');
$products=Product::where('title','LIKE',"%{$title}%")->get();
return response()->json($products);
}
//.....................................................................
public function get_All_Catogery(){
    $cat= Catogery::all();
    return response()->json(['message'=>'this is all cats','data'=>$cat,200]);
}
//.....................................................................

public function get_Catogery_By_Id($id) {
    // العثور على الفئة باستخدام المعرف
    $category = Catogery::find($id);

    // التحقق مما إذا كانت الفئة موجودة
    if (!$category) {
        return response()->json(['message' => 'Category not found'], 404);
    }

    // استرجاع المنتجات المرتبطة بالفئة
    $products = Product::where('catogery_id', $category->id)->get();

    return response()->json(['message' => 'Products', 'products' => $products], 200);
}
//.....................................................................
public function add_Catogery(Request $request){
    $product=Auth::user();
    $pro=$product->id;
$cat =$request->validate([
    'name'=>'required'
]);
$c=Catogery::create([
    'name'=>$request->name,
]);
return response()->json(['message'=>'add cat success','data'=>$c,200]);
}
//.....................................................................
public function delete_Catogery($id){
    $product=Auth::user();
    $pro=$product->id;
$cat =Catogery::find($id);
if(empty($cat)){
    return response()->json(['error'=>'Can not Find',404]);}
    $cat->delete();
    return response()->json(['message'=>'delete cat success','data'=>$cat,200]) ;  
}

//.....................................................................
public function delete_order($id){
    $product=Auth::user();
    $pro=$product->id;
$order=Order::find($id);
$order->delete();
return response()->json(['message'=>'Oerder is Deleted success','data'=>$order],200);}
//...........................................................................
public function update_order(Request $request, $id) {
    // Find the order by ID
    $order = OrderProduct::find($id);
    if (empty($order)) {
        return response()->json(['error' => 'Cannot find order'], 404);
    }

    // Validate the request
    $request->validate([
        'quantity' => 'required|integer|min:1', // Ensure quantity is present and valid
    ]);

    // Get the quantity from the request
    $quantity = $request->input('quantity');

    // Update the order's quantity
    $order->quantity = $quantity;
    $order->save();

    return response()->json(['message' => 'Order updated successfully'], 200);
}
//................................................................................
public function get_all_order(){
    $order=Order::all();
    return response()->json(['message'=>'there are all orders','data'=>$order],200);}
//...................................................................................
public function add_order(Request $request){
    $user = Auth::user();
    if (!$user) {
        return response()->json(['error' => 'User not authenticated'], 401);
    }
    $orderDate = Carbon::now();
    $order = Order::create([
        'user_id' => $user->id, // Use the current user's ID
        'order_date' => $orderDate, // Use the converted date here
    ]);
    return response()->json(['message' => '  successfuly added  order','order'=>$order], 200);}
    
//.....................................................................................
public function add_order_product(Request $request) {
    $user = Auth::user();
    if (!$user) {
        return response()->json(['error' => 'User not authenticated'], 401);
    }

    $orderId = $request->input('order_id');
    $order = Order::find($orderId);

    if (!$order) {
        return response()->json(['error' => 'Order not found'], 404);
    }

    $data = $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:1', // Ensure quantity is a positive integer
    ]);

    // Find the product
    $product = Product::find($data['product_id']);
    if (empty($product)) {
        return response()->json(['error' => 'Cannot find product'], 404);
    }

    // Check product quantity
    if ($product->quantity < $data['quantity']) {
        return response()->json(['error' => 'Insufficient product quantity', 'available_quantity' => $product->quantity], 404);
    }

    // Create the order-product entry
    $orderProduct = OrderProduct::create([
        'product_id' => $data['product_id'],
        'order_id' => $order->id, // Use the ID of the existing order
        'quantity' => $data['quantity'],
    ]);

    return response()->json([
        'message' => 'Product added to order',
        'order' => $order,
        'order_product' => $orderProduct,
        'product' => $product
    ], 200);
}
//.............................................................................................
public function get_orderById($id){
    $user=Auth::user();
    $u=$user->id;
    $order=Order::find($id);
    if($order){
        return response()->json(['message'=>'this is your  order','data'=>$order,200]);
    }

}

//.........................................................................................
public function get_order_product_byId(Request $request, $id)
{
    // Find the OrderProduct by ID
    $orderProduct = OrderProduct::find($id);

    // Check if the OrderProduct exists
    if (!$orderProduct) {
        return response()->json(['error' => 'Order product not found'], 404);
    }

    // Find the associated order using the order_id from OrderProduct
    $order = Order::with('products')->find($orderProduct->order_id);

    // Check if the order exists
    if (!$order) {
        return response()->json(['error' => 'Order not found'], 404);
    }

    // Retrieve the products associated with the order
    $products = $order->products;

    // Check if products are found
    if ($products->isEmpty()) {
        return response()->json(['order_id' => $order->id, 'products' => $products, 'details' => [], 'error' => 'No products found for this order'], 200);
    }

    // Collect product IDs to fetch details
    $productIds = $products->pluck('product_id'); // Use product_id instead of id

    // Fetch detailed information for the associated products
    $details = Product::whereIn('id', $productIds)->get();

    return response()->json([
        'order_id' => $order->id,
        'products' => $products,
        'details' => $details
    ], 200);
}

//............................................................................
public function get_all_order_product(){
    $order=OrderProduct::all();
    return response()->json(['message' => 'there are  all orders', 'data' => $order], 200);
}
//.....................................................................................
public function add_payment(Request $request) {
    $data = $request->validate([
        'order_product_id' => 'required|exists:orders_prdoucts,id',
        
    ]);

    // جلب OrderProduct باستخدام المعرف
    $orderProduct = OrderProduct::find($data['order_product_id']);
    
    if (empty($orderProduct)) {
        return response()->json(['error' => 'Cannot find order product'], 404);
    }

    // الحصول على الكمية والمنتج المرتبط
    $quantity = $orderProduct->quantity; // تأكد من أن لديك حقل quantity في جدول orders_products
    $product = Product::find($orderProduct->product_id);
    
    if (empty($product)) {
        return response()->json(['error' => 'Cannot find product'], 404);
    }

    // الحصول على السعر
    $price = $product->price; // تأكد من أن لديك حقل price في جدول products

    // حساب المجموع الكلي
    $total_price = $quantity * $price;
    $currentDateTime = Carbon::now();
    $payment = Payment::create([
        'total_price' => $total_price ,
        'order_product_id'=>$data['order_product_id'] ,
        'date_payment' => $currentDateTime,
    ]);

    return response()->json(['message' => 'Payment added successfully', 'data' => $payment], 200);
}
//................................................................................
public function get_all_payments(){
    $payment=Payment::all();
    return response()->json(['message'=>'there are all payments','payments'=>$payment]);
}
//................................................................................

public function get_payment_ById($id) {
    $user = Auth::user();

    // Find the payment by ID
    $pay = Payment::find($id);

    // Check if payment exists
    if (!$pay) {
        return response()->json(['message' => 'Payment not found'], 404);
    }

    // Find the order product associated with the payment
    $order_product = OrderProduct::find($pay->order_product_id);

    // Check if order product exists
    if (!$order_product) {
        return response()->json(['message' => 'Order product not found'], 404);
    }

    // Find the order associated with the order product
    $order = Order::with('products')->find($order_product->order_id);

    // Check if order exists
    if (!$order) {
        return response()->json(['message' => 'Order not found'], 404);
    }

    // Get product IDs from the order's products relationship
    $productIds = $order->products->pluck('product_id');

    // Get details of the products
    $details = Product::whereIn('id', $productIds)->get();

    return response()->json([
        'data' => $pay,
        'order_id' => $order->id,
        'products' => $order->products,
        'details' => $details
    ], 200);
}
//..............................................................................

public function add_address(Request $request){
    $user =Auth::user();
    $add=$user->id;
    $data=$request->validate([
        'area'=>'required',
        'city'=>'required',
        'country'=>'required'
    ]);
    $address=Address::create([
        'user_id'=>$add,
        'area'=>$request->area,
        'city'=>$request->city,
        'country'=>$request->country
    ]);
    return response()->json(['message' => 'Address added successfully', 'data' => $address], 200);
}
//...............................................................................................
public function update_address(Request $request ,$id){
    $address=Address::find($id);
    $address=Address::where('id',$id)->update($request->all());
    return response()->json(['message' => 'Address update successfully'], 200);
}
//.............................................................................................
public function delete_address($id){

    $address=Address::find($id);
    $address->delete();
    return response()->json(['message' => 'Address delete successfully'], 200);

}
//....................................................................
public function updateStateOrder($id) {
    // Find the OrderProduct by its ID
    $orderProduct = OrderProduct::find($id);

    // Check if the order product exists
    if (!$orderProduct) {
        return response()->json(['error' => 'Order product not found'], 404);
    }

    // Retrieve the order_id from the found order product
    $orderId = $orderProduct->order_id;

    // Retrieve all OrderProducts associated with the order_id
    $orderProducts = OrderProduct::where('order_id', $orderId)->get();

    // Check if any order products are found
    if ($orderProducts->isEmpty()) {
        return response()->json(['error' => 'No order products found for this order'], 404);
    }

    // Update the status of each order product to "Active"
    foreach ($orderProducts as $product) {
        if ($product->status !== 'Active') {
            $product->status = 'Active';
            $product->save();
        }
    }

    return response()->json([
        'message' => 'Order product statuses updated successfully',
        'updated_count' => $orderProducts->count(),
        'order_products' => $orderProducts
    ], 200);
}}
//......................................................................
