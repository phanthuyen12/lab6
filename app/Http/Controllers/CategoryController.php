<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function select_data(Request $request)
    {
        // Lấy danh sách các danh mục và số lượng sản phẩm tương ứng
        $categories = Category::select('id', 'name_category', 'images', 'created_at', 'trangthai')
            ->withCount('products') // Lấy số lượng sản phẩm của mỗi danh mục
            ->get();
    
        return response()->json($categories);
    }
    
    public function get_category_id($id){
        $category = Category::find($id);
        return response()->json($category);
    }

    public function creates(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:255',
            'category_img' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $image = $request->file('category_img');
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('images'), $imageName);

        $category = new Category();
        $category->name_category = $request->category_name;
        $category->images = $imageName;
        $category->save();

        return response()->json(['message' => 'Thành công'], 200);
    }

    public function delete_item(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|exists:categorys,id',
            ]);
    
            $categoryId = $request->id;
            $category = Category::with('products')->find($categoryId);
    
            if (!$category) {
                return response()->json(['message' => 'Danh mục không tồn tại'], 404);
            }
    
            // Kiểm tra xem danh mục có sản phẩm không
            if ($category->products()->exists()) {
                return response()->json(['message' => 'Không thể xóa danh mục vì có sản phẩm trong danh mục này'], 422);
            }
    
            $imagePath = public_path('images/' . $category->images);
            if (file_exists($imagePath)) {
                unlink($imagePath); // Xóa ảnh từ thư mục public
            }
    
            $category->delete();
    
            return response()->json(['message' => 'Xóa danh mục và ảnh thành công'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    
    

    public function update_trangthai(Request $request){
        // Tìm danh mục theo id
        $request->validate([
            'id' => 'required|exists:categorys,id',
        ]);
    
        $categoryId = $request->id;
        $category = Category::find($categoryId);
    
        if (!$category) {
            return response()->json(['message' => 'Danh mục không tồn tại'], 404);
        }
    
        // Cập nhật trạng thái
        $category->trangthai = !$category->trangthai; // Sử dụng toán tử NOT để đảo ngược trạng thái
        $category->save();
    
        return response()->json(['message' => 'Cập nhật trạng thái thành công'], 200);
    }
    
    public function update_category(Request $request){
        // dd($request->all());
        // Xác thực dữ liệu được gửi từ request nếu cần
        $request->validate([
            'id' => 'required|exists:categorys,id',
            'name_category' => 'required|string|max:255',
            'category_img' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ]);
    
        $categoryId = $request->id;
    
        $category = Category::find($categoryId);
        $imagePath = public_path('images/' . $category->images);
        if (file_exists($imagePath)) {
            unlink($imagePath); // Xóa ảnh từ thư mục public
        }

        if (!$category) {
            return response()->json(['message' => 'Danh mục không tồn tại'], 404);
        }
        $image = $request->file('category_img');
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('images'), $imageName);
        $category->name_category = $request->name_category;
        $category->images = $imageName;

        $category->save();
    
        // Trả về phản hồi cho client
        return response()->json(['message' => 'Cập nhật danh mục thành công'], 200);
    }
    
    
}
