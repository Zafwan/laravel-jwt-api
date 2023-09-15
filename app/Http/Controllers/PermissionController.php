<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;

class PermissionController extends BaseController
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
     *     name="Permission",
     *     description="Endpoints related to permissions"
     * )
     */

    /**
     * @OA\Get(
     *     path="/api/permission/permissions",
     *     summary="Get all permissions",
     *     tags={"Permission"},
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="405", description="Method not allowed (Due to token invalid or expired)"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function index()
    {
        $this->authorize('view-permission');

        $permissions = Permission::latest()->paginate(10);

        return $this->sendResponse($permissions, 'Permissions Retrieved Successfully');
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
     *     name="Permission",
     *     description="Endpoints related to permissions"
     * )
     */

    /**
     * @OA\Post(
     *     path="/api/permission/permissions",
     *     summary="Create a new permission",
     *     tags={"Permission"},
     *     security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              required={"name"},
     *              @OA\Property(property="name", type="string", example="create-role"),
     *          ),
     *      ),
     *     @OA\Response(response="201", description="Permission created successfully"),
     *     @OA\Response(response="422", description="Validation error"),
     *     @OA\Response(response="405", description="Method not allowed (Due to token invalid or expired)"),
     * )
     */
    public function store(Request $request)
    {
        $this->authorize('create-permission');

        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:permissions,name'
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return $this->sendValidationError('Validation Error', $validator->errors());
        }

        $permission = Permission::create([
            'name' => $request->name
        ]);

        return $this->sendResponseCreate($permission, 'New Permission Created Successfully');
    }

    /**
     * @OA\Tag(
     *     name="Permission",
     *     description="Endpoints related to permissions"
     * )
     */

    /**
     * @OA\Get(
     *     path="/api/permission/permissions/{id}",
     *     summary="Get specific permission by id",
     *     tags={"Permission"},
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
        $this->authorize('view-permission');

        $permission = Permission::find($id);

        if (is_null($permission)) {
            return $this->sendError('Permission not found');
        }

        return $this->sendResponse($permission, 'Permission Retrieved Successfully');
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
     *     name="Permission",
     *     description="Endpoints related to permissions"
     * )
     */

    /**
     * @OA\Put(
     *      path="/api/permission/permissions/{id}",
     *      summary="Update permission by id",
     *      tags={"Permission"},
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
     *              required={"name"},
     *              @OA\Property(property="name", type="string", example="create-role"),
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
        $this->authorize('update-permission');

        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required|string|unique:permissions,name,' . $id,
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError('Validation Error', $validator->errors());
        }

        $permission = Permission::find($id);

        if (is_null($permission)) {
            return $this->sendError('Permission not found');
        }

        $permission->name = $input['name'];
        $permission->save();

        return $this->sendResponse($permission, 'Permission Updated Successfully');
    }

    /**
     * @OA\Tag(
     *     name="Permission",
     *     description="Endpoints related to permissions"
     * )
     */

    /**
     * @OA\Delete(
     *      path="/api/permission/permissions/{id}",
     *      summary="Delete permission by id",
     *      tags={"Permission"},
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
        $this->authorize('delete-permission');

        $permission = Permission::find($id);

        if (is_null($permission)) {
            return $this->sendError('Permission not found');
        }

        $permission->delete();

        return $this->sendResponse($permission, 'Permission Deleted Successfully');
    }
}