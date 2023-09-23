<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class PostController extends Controller
{
    public function store(PostRequest $request)
    {
        try {

            $user_id = auth('api')->user()->id;

            $values = array();
            if ($request->hasFile('image')) {
                $post_image = 'post_image-' . time() . '-' . rand(0, 99) . '.' . $request->image->extension();
                $request->image->move(public_path('upload/post_image/'), $post_image);
            }
            $values['user_id'] = $user_id;
            $values['title'] = $request->title;
            $values['description'] = $request->description;
            $values['image'] = $post_image;
            Post::create($values);
            $data['status'] = true;
            $data['message'] = 'Data inserted successfully.';
            return response()->json($data, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong. Please try again.'], 500);
        }
    }

    public function postList()
    {
        $post = Post::orderBy('created_at', 'DESC')->where('deleted_at', null)->get();
        if (count($post) > 0) {
            $postData = PostResource::collection($post);
            return response()->json(['status' => true, 'message' => "Data Fetched Successfully.", 'data' => $postData]);
        } else {
            return response()->json(['status' => false, 'message' => "No Record Found."]);

        }
    }

    public function destroy(Request $request)
    {
        try {
            $user_id = auth('api')->user()->id;
            $validation = Validator::make($request->all(), [
                'post_id' => 'required',
            ]);
            if ($validation->fails()) {
                $errors = $validation->errors()->first();
                $data['status'] = false;
                $data['code'] = 422;
                $data['message'] = $errors;
                return response()->json($data, 422);
            } else {
                Post::where(['user_id' => $user_id, 'id' => $request->post_id])->delete();
                return response()->json(['status' => true, 'message' => "Data Deleted Successfully."]);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong. Please try again.'], 500);
        }
    }

    public function update(UpdatePostRequest $request)
    {
        try {
            $user_id = auth('api')->user()->id;
            if ($request->hasFile('image')) {
                $post_image = 'image-' . time() . '-' . rand(0, 99) . '.' . $request->image->extension();
                $request->image->move(public_path('upload/post_image/'), $post_image);
                $post_images = Post::where('id', $request->post_id)->pluck('image')[0];
                File::delete(public_path($post_images));
                Post::where('id', $request->post_id)->update(['image' => $post_image]);
            }
            $post = [
                'title' => $request->title,
                'description' => $request->description,
            ];
            $postData = Post::where(['id' => $request->post_id, 'user_id' => $user_id])->update($post);
            $updatedPost = Post::where(['id' => $request->post_id, 'user_id' => $user_id])->first();
            if ($updatedPost->image) {$updatedPost['image'] = url('public/upload/team_image/') . '/' . $updatedPost->image;} else { $updated['image'] = null;}
            $data['data'] = $updatedPost;
            $data['status'] = true;
            $data['message'] = 'Post updated successfully.';
            return response()->json($data, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong. Please try again.'], 500);
        }
    }
}
