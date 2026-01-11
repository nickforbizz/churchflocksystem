<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ViewsController extends Controller
{
    public function index(Request $request)
    {


        return view('frontend.index');
    }



    public function getPost($id)
    {
        $post_categories = PostCategory::where('active', 1)->with('posts')->get();
        $latest_posts = Post::where('active', 1)->orderBy('created_at', 'desc')->take(4)->get();
        $post = Post::with('comments.user')->findOrFail($id);
        return view('frontend.post', compact('post_categories', 'latest_posts', 'post'));
    }


    public function posts()
    {
        $post_categories = PostCategory::where('active', 1)->with('posts')->get();
        $posts = Post::where('active', 1)->orderBy('created_at', 'desc')->paginate(10);
        $featured_posts = Post::where('active', 1)->where('status', 3)->orderBy('created_at', 'desc')->get();

        return view('frontend.posts', compact('post_categories', 'posts', 'featured_posts'));
    }


    // subscribeNewsletter
    public function subscribeNewsletter(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'email' => 'required|email|unique:newsletters,email',
        ],
        [
            'email.required' => 'Email is required',
            'email.email' => 'Please provide a valid email address',
            'email.unique' => 'This email is already subscribed to the newsletter',
        ]);

        // if validation fails
        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Create a new newsletter subscription
        \App\Models\Newsletter::create([
            'email' => $request->email,
        ]);

        // return json response
        if ($request->ajax()) {
            return response()->json(['message' => 'Thank you for subscribing to our newsletter!'], 200);
        }

        return redirect()->back()->with('success', 'Thank you for subscribing to our newsletter!');
    }
}
