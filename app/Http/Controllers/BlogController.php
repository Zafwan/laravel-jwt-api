<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;

class BlogController extends BaseController
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * @OA\Tag(
     *     name="Blog",
     *     description="Endpoints related to blogs"
     * )
     */

    /**
     * @OA\Get(
     *     path="/api/blog/blogs",
     *     summary="Get all blogs for authenticated user",
     *     tags={"Blog"},
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="405", description="Method not allowed (Due to token invalid or expired)"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function index()
    {
        $user = auth()->user()->id;
        $blogs = Blog::with('user')->where('user_id', $user)->latest()->paginate(10);

        return $this->sendResponse($blogs, 'Blogs Retrieved Successfully.');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * @OA\Tag(
     *     name="Blog",
     *     description="Endpoints related to blogs"
     * )
     */

    /**
     * @OA\Post(
     *     path="/api/blog/blogs",
     *     summary="Create a new blog",
     *     tags={"Blog"},
     *     security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              required={"title"},
     *              @OA\Property(property="title", type="string", example="How to mengulor"),
     *              @OA\Property(property="body", type="text", example="First, go make coffee"),
     *          ),
     *      ),
     *     @OA\Response(response="201", description="Blog created successfully"),
     *     @OA\Response(response="422", description="Validation error"),
     *     @OA\Response(response="405", description="Method not allowed (Due to token invalid or expired)"),
     * )
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:50',
            'body' => 'nullable',
        ]);

        // Check if the validation fails
        if ($validator->fails()) {
            // If validation fails, return a JSON response with the validation errors
            return $this->sendValidationError('Validation Error', $validator->errors());
        }

        $blog = Blog::create([
            'title' => $request->title,
            'body' => $request->body,
            'user_id' => auth()->user()->id
        ]);
        return $this->sendResponseCreate($blog, 'New Blog Created Successfully.');
    }

    /**
     * @OA\Tag(
     *     name="Blog",
     *     description="Endpoints related to blogs"
     * )
     */

    /**
     * @OA\Get(
     *     path="/api/blog/blogs/{id}",
     *     summary="Get specific blog by id for authenticated user",
     *     tags={"Blog"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the record",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="404", description="Not found"),
     *     @OA\Response(response="405", description="Method not allowed (Due to token invalid or expired)"),
     * )
     */
    public function show($id)
    {
        $user = auth()->user()->id;
        $blog = Blog::where('user_id', $user)->find($id);

        if (is_null($blog)) {
            return $this->sendError('Blog not found');
        }

        return $this->sendResponse($blog, 'Blog Retrieved Successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * @OA\Tag(
     *     name="Blog",
     *     description="Endpoints related to blogs"
     * )
     */

    /**
     * @OA\Put(
     *      path="/api/blog/blogs/{id}",
     *      summary="Update blog by id",
     *      tags={"Blog"},
     *      security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the record",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              required={"title"},
     *              @OA\Property(property="title", type="string", example="How to pura-pura busy"),
     *              @OA\Property(property="body", type="text", example="Open as many terminals as possible"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error",
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not found",
     *      ),
     *      @OA\Response(response="405", description="Method not allowed (Due to token invalid or expired)"),
     * )
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user()->id;
        $input = $request->all();

        $validator = Validator::make($input, [
            'title' => 'required|string|max:50',
            'body' => 'nullable'
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError('Validation Error', $validator->errors());
        }

        $blog = Blog::where('user_id', $user)->find($id);

        if (is_null($blog)) {
            return $this->sendError('Blog not found');
        }

        $blog->title = $input['title'];
        $blog->body = $input['body'];
        $blog->save();

        return $this->sendResponse($blog, 'Blog Updated Successfully.');
    }

    /**
     * @OA\Tag(
     *     name="Blog",
     *     description="Endpoints related to blogs"
     * )
     */

    /**
     * @OA\Delete(
     *      path="/api/blog/blogs/{id}",
     *      summary="Delete blog by id",
     *      tags={"Blog"},
     *      security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the record",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not found",
     *      ),
     *      @OA\Response(response="405", description="Method not allowed (Due to token invalid or expired)"),
     * )
     */
    public function destroy($id)
    {
        $user = auth()->user()->id;
        $blog = Blog::where('user_id', $user)->find($id);

        if (is_null($blog)) {
            return $this->sendError('Blog not found');
        }

        $blog->delete();

        return $this->sendResponse($blog, 'Blog Deleted Successfully.');
    }
}