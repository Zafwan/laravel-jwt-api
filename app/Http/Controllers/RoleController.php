<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;

class RoleController extends BaseController
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
     *     name="Role",
     *     description="Endpoints related to roles"
     * )
     */

    /**
     * @OA\Get(
     *     path="/api/role/roles",
     *     summary="Get all roles",
     *     tags={"Role"},
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="405", description="Method not allowed (Due to token invalid or expired)"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function index()
    {
        $this->authorize('view-role');

        $roles = Role::with('role_permission')->latest()->paginate(10);

        return $this->sendResponse($roles, 'Roles Retrieved Successfully');
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
     *     name="Role",
     *     description="Endpoints related to roles"
     * )
     */

    /**
     * @OA\Post(
     *     path="/api/role/roles",
     *     summary="Create a new role",
     *     tags={"Role"},
     *     security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              required={"name"},
     *              @OA\Property(property="name", type="string", example="Admin"),
     *              @OA\Property(property="permission_id", type="array", @OA\Items(type="string",example="1")),
     *          ),
     *      ),
     *     @OA\Response(response="201", description="Role created successfully"),
     *     @OA\Response(response="422", description="Validation error"),
     *     @OA\Response(response="405", description="Method not allowed (Due to token invalid or expired)"),
     * )
     */
    public function store(Request $request)
    {
        $this->authorize('create-role');

        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:roles,name',
            'permission_id' => 'nullable|array',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return $this->sendValidationError('Validation Error', $validator->errors());
        }

        $role = Role::create([
            'name' => $request->name
        ]);

        //Create role_permission
        RolePermission::create([
            'role_id' => $role->id,
            'permission_id' => $request->permission_id
        ]);

        return $this->sendResponseCreate($role, 'New Role Created Successfully');
    }

    /**
     * @OA\Tag(
     *     name="Role",
     *     description="Endpoints related to roles"
     * )
     */

    /**
     * @OA\Get(
     *     path="/api/role/roles/{id}",
     *     summary="Get specific role by id",
     *     tags={"Role"},
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
        $this->authorize('view-role');

        $role = Role::with('role_permission')->find($id);

        if (is_null($role)) {
            return $this->sendError('Role not found');
        }

        return $this->sendResponse($role, 'Role Retrieved Successfully');
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
     *     name="Role",
     *     description="Endpoints related to roles"
     * )
     */

    /**
     * @OA\Put(
     *      path="/api/role/roles/{id}",
     *      summary="Update role by id",
     *      tags={"Role"},
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
     *              @OA\Property(property="name", type="string", example="Admin"),
     *              @OA\Property(property="permission_id", type="array", @OA\Items(type="string",example="1")),
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
        $this->authorize('update-role');

        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required|string|unique:roles,name,' . $id,
            'permission_id' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError('Validation Error', $validator->errors());
        }

        $role = Role::find($id);

        if (is_null($role)) {
            return $this->sendError('Role not found');
        }

        $role->name = $input['name'];
        $role->save();

        //Update role_permissions
        $role_permission = RolePermission::find($id);
        $role_permission->permission_id = $request->permission_id;
        $role_permission->save();


        return $this->sendResponse($role, 'Role Updated Successfully');
    }

    /**
     * @OA\Tag(
     *     name="Role",
     *     description="Endpoints related to roles"
     * )
     */

    /**
     * @OA\Delete(
     *      path="/api/role/roles/{id}",
     *      summary="Delete role by id",
     *      tags={"Role"},
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
        $this->authorize('delete-role');

        $role = Role::find($id);

        if (is_null($role)) {
            return $this->sendError('Role not found');
        }

        $role->delete();

        return $this->sendResponse($role, 'Role Deleted Successfully');
    }
}