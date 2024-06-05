<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class ProductController extends Controller
{
    public function get_create_product(){
        $categorys = Category::select('id', 'name_category', 'images', 'created_at','trangthai')->get();

        return view("admin/create_product",compact("categorys"));
    }
    public function create_product(Request $request){
        // Xác thực dữ liệu từ request
        $request->validate([
            'img_product' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
            'name_products' => 'required|string',
            'price_product' => 'required|integer',
            'id_category' => 'required|integer', // Đảm bảo rằng 'id_category' là bắt buộc và phải là số nguyên
            'stock_quantity' => 'required|integer',
            'describe' => 'required|string',
        ]);
    
        // Xử lý ảnh sản phẩm
        $image = $request->file('img_product');
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('images'), $imageName);
    
        // Tạo mới sản phẩm
        $product = new Product();
        $product->name_products = $request->name_products;
        $product->id_category = $request->id_category;
        $product->stock_quantity = $request->stock_quantity;
        $product->describe = $request->describe;    
        $product->price_product = $request->price_product;
        $product->img_product = $imageName;
        $product->save();
    
        // Trả về phản hồi thành công
        return response()->json(['message' => 'Thành công'], 200);
    }
    public function product_management(Request $request){
        $products = Product::select('product.id', 'product.name_products', 'product.price_product', 'product.img_product', 'product.describe', 'product.stock_quantity', 'product.created_at')
            ->join('categorys', 'product.id_category', '=', 'categorys.id')
            ->select('product.*', 'categorys.name_category')
            ->get();
        $categorys = Category::select('id', 'name_category', 'images', 'created_at','trangthai')->get();

        return view('admin/product_management', compact('products','categorys'));
    }
    public function get_product_id($id){
        // Tìm sản phẩm theo id
        $product = Product::find($id);
    
        // Kiểm tra xem sản phẩm có tồn tại không
        if(!$product) {
            // Nếu không tìm thấy sản phẩm, trả về phản hồi lỗi 404 Not Found
            return response()->json(['message' => 'Sản phẩm không tồn tại'], 404);
        }
    
        // Nếu sản phẩm tồn tại, trả về thông tin sản phẩm
        return response()->json(['message' => $product], 200);
    }
    
    public function delete_product(Request $request){
        $request->validate([
            'id' => 'required|exists:product,id',
        ]);
    
        $id = $request->id;
        $product = Product::find($id);
    
        if (!$product) {
            return response()->json(['message' => 'Sản phẩm không tồn tại'], 404);
        }
    
        $imagePath = public_path('images/' . $product->img_product);
        if (file_exists($imagePath)) {
            unlink($imagePath); // Xóa ảnh từ thư mục public
        }
    
        $product->delete();
    
        return response()->json(['message' => 'Xóa sản phẩm và ảnh thành công'], 200);
    }
    
    public function update_product(Request $request){
        $request->validate([

            'img_product' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
            'name_products' => 'required|string',
            'price_product' => 'required|integer',
            'id_category' => 'required|integer', // Đảm bảo rằng 'id_category' là bắt buộc và phải là số nguyên
            'stock_quantity' => 'required|integer',
            'describe' => 'required|string',
        ]);
        $id = $request->id;
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Không tìm thấy sản phẩm'], 404);
        }
        $imagePath = public_path('images/' . $product->img_product);
        if (file_exists($imagePath)) {
            unlink($imagePath); // Xóa ảnh từ thư mục public
        }
    

    
        // Xử lý ảnh sản phẩm
        $image = $request->file('img_product');
        
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('images'), $imageName);
    
        // Tạo mới sản phẩm
        
        $product->name_products = $request->name_products;
        $product->id_category = $request->id_category;
        $product->stock_quantity = $request->stock_quantity;
        $product->describe = $request->describe;    
        $product->price_product = $request->price_product;
        $product->img_product = $imageName;
        $product->save();
    
        // Trả về phản hồi thành công
        return response()->json(['message' => 'Thành công'], 200);
    }
    public function trangthai_product(Request $request){

    }
}
