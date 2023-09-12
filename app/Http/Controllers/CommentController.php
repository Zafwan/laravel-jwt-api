<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;

class CommentController extends BaseController
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
     *     name="Comment",
     *     description="Endpoints related to comments"
     * )
     */

    /**
     * @OA\Get(
     *     path="/api/comment/comments",
     *     summary="Get all comments for authenticated user",
     *     tags={"Comment"},
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="405", description="Method not allowed (Due to token invalid or expired)"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function index()
    {
        $user = auth()->user()->id;
        $comments = Comment::with(['user', 'blog'])->where('user_id', $user)->latest()->paginate(10);

        return $this->sendResponse($comments, 'Comments Retrieved Successfully');
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
     *     name="Comment",
     *     description="Endpoints related to comments"
     * )
     */

    /**
     * @OA\Post(
     *     path="/api/comment/comments",
     *     summary="Create a new comment",
     *     tags={"Comment"},
     *     security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              required={"comment","blog_id"},
     *              @OA\Property(property="comment", type="text", example="This blog is useful"),
     *              @OA\Property(property="blog_id", type="integer", example="1"),
     *          ),
     *      ),
     *     @OA\Response(response="201", description="Comment created successfully"),
     *     @OA\Response(response="422", description="Validation error"),
     *     @OA\Response(response="405", description="Method not allowed (Due to token invalid or expired)"),
     * )
     */
    public function store(Request $request)
    {
        $user = auth()->user()->id;

        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'comment' => 'required|string',
            'blog_id' => 'required|integer'
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return $this->sendValidationError('Validation Error', $validator->errors());
        }

        $comment = Comment::create([
            'comment' => $request->comment,
            'user_id' => $user,
            'blog_id' => $request->blog_id
        ]);

        return $this->sendResponseCreate($comment, 'New Comment Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
     *     name="Comment",
     *     description="Endpoints related to comments"
     * )
     */

    /**
     * @OA\Put(
     *      path="/api/comment/comments/{id}",
     *      summary="Update comment by id",
     *      tags={"Comment"},
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
     *              required={"comment","blog_id"},
     *              @OA\Property(property="comment", type="text", example="Very good post"),
     *              @OA\Property(property="blog_id", type="integer", example="1"),
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
            'comment' => 'required|string',
            'blog_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError('Validation Error', $validator->errors());
        }

        $comment = Comment::where('user_id', $user)->find($id);

        if (is_null($comment)) {
            return $this->sendError('Comment not found');
        }

        $comment->comment = $input['comment'];
        $comment->blog_id = $input['blog_id'];
        $comment->save();

        return $this->sendResponse($comment, 'Comment Updated Successfully');
    }

    /**
     * @OA\Tag(
     *     name="Comment",
     *     description="Endpoints related to comments"
     * )
     */

    /**
     * @OA\Delete(
     *      path="/api/comment/comments/{id}",
     *      summary="Delete comment by id",
     *      tags={"Comment"},
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
        $comment = Comment::where('user_id', $user)->find($id);

        if (is_null($comment)) {
            return $this->sendError('Comment not found');
        }

        $comment->delete();

        return $this->sendResponse($comment, 'Comment Deleted Successfully');
    }
}