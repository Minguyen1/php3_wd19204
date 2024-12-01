<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Exception;
use Illuminate\Http\Client\ResponseSequence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    // Danh sách posts
    public function index(){
        $posts = Post::query()->latest('id')->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $posts,
            'status' => 200
        ], 200);
    }

    public function show($id){
        try{
            $post = Post::query()->findOrFail($id);
            return Response()->json([
                'success' => true,
                'message' => 'Chi tiết bài viết',
                'data' => $post,
            ], 200);
        } catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Lỗi không tồn tại {$id}',
            ]);
        }
    }

    public function destroy($id){
        try{
            $post = Post::query()->findOrFail($id);
            if(file_exists($post->image)){
                Storage::delete($post->image);
            }
            $post->delete();
            return response()->json([
                'success' => true,
                'message' => 'Xóa dữ liệu thành công',
            ], 200);
        } catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Xóa dữ liệu thất bại',
                'error' => $e->getCode()
            ]);
        }
    }

    public function store(Request $request){
        $data = $request->all();

        $validate = Validator($data, [
            'title' => ['required', 'min:5'],
            'image' => ['nullable', 'image'],
            'view' => ['integer', 'min:1'],
        ], [
            'title.required' => 'Bạn phải nhập title',
            'title.min' => 'Title phải ít nhất 5 ký tự',
            'image.image' => 'Image phải là hình ảnh',
            'view.integer' => 'View là số nguyên',
            'view.min' => 'View không được là số âm',
        ]);

        if($validate->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validate->errors(),
                'message' => 'Lỗi validate',
            ]);
        }

        try{
            $image = "";
            // Nếu nhập ảnh
            if($request->hasFile('image')){
                $image = $request->file('image')->store('images');
            }
            // Cập nhật ảnh
            $data['image'] = $image;

            Post::query()->create($data);

            return response()->json([
                'success' => true,
                'message' => 'Thêm dữ liệu thành công',
            ], 201);
        } catch(\Throwable $th){
            return response()->json([
                'success' => false,
                'message' => 'Thêm dữ liệu thất bại',
                'error' => $th->getCode(),
            ]);
        }
    }

    public function update(Request $request, $id){
        try{
            $post = Post::query()->findOrFail($id);

            $data = $request->all();

            $validate = Validator($data, [
                'title' => ['required', 'min:5', "unique:posts, title, $id"],
                'image' => ['nullable', 'image'],
                'view' => ['integer', 'min:1'],
            ], [
                'title.required' => 'Bạn phải nhập title',
                'title.min' => 'Title phải ít nhất 5 ký tự',
                'image.image' => 'Image phải là hình ảnh',
                'view.integer' => 'View là số nguyên',
                'view.min' => 'View không được là số âm',
            ]);

            if($validate->fails()){
                return response()->json([
                    'success' => false,
                    'errors' => $validate->errors(),
                    'message' => 'Lỗi validate',
                ]);
            }

            $image = "";
            // Nếu nhập ảnh
            if($request->hasFile('image')){
                $image = $request->file('image')->store('images');
            }
            // Cập nhật ảnh
            $data['image'] = $image;

            $post->update($data);

            return response()->json([
                'success' => true,
                'data' => $post,
                'message' => 'Cập nhập dữ liệu thành công',
            ], 200);
        } catch(\Throwable $th){
            return response()->json([
                'success' => false,
                'message' => 'Cập nhập dữ liệu thất bại',
                'error' => $th->getCode(),
            ]);
        }
    }
}
