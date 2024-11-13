
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\{{ ucfirst($name) }}\I{{ ucfirst($name) }}Service;
use App\Http\Requests\Create{{ ucfirst($name) }}Request;
use App\Http\Requests\Update{{ ucfirst($name) }}Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Illuminate\Http\JsonResponse;
use Exception;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Tag(
 *     name="{{ ucfirst($name) }}",
 *     description="{{ ucfirst($name) }} management operations"
 * )
 */
class {{ ucfirst($name) }}Controller extends Controller
{
    public function __construct(private I{{ ucfirst($name) }}Service ${{ lcfirst($name) }}Service)
    {
    }

    /**
     * @OA\Get(
     *     path="/api/admin/api-{{ strtolower($name) }}",
     *     tags={"{{ ucfirst($name) }}"},
     *     security={!! $bearerAuth !!},
     *     summary="Get all {{ strtolower($name) }}",
     *     @OA\Response(
     *         response=200,
     *         description="A list of {{ strtolower($name) }}",
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        try {
            $data = $this->{{ lcfirst($name) }}Service->findAllWithPagination([], ['*'], 10);
            return $this->success($data, 'Data retrieved successfully');
        } catch (Exception $e) {
            return $this->error('Could not retrieve {{ strtolower($name) }}s.', [], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/admin/api-{{ strtolower($name) }}",
     *     tags={"{{ ucfirst($name) }}"},
     *     security={!! $bearerAuth !!},
     *     summary="Create a new {{ strtolower($name) }}",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="name",
     *                 type="string",
     *                 example="Sample name",
     *                 description="The name of the {{ strtolower($name) }}"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="{{ ucfirst($name) }} created successfully",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     )
     * )
     */
    public function store(Create{{ ucfirst($name) }}Request $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $data = $this->{{ lcfirst($name) }}Service->create($request->all());
            DB::commit();
            return $this->success($data, '{{ ucfirst($name) }} created successfully', ResponseAlias::HTTP_CREATED);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error('Could not create {{ strtolower($name) }}.', [], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/admin/api-{{ strtolower($name) }}/{id}",
     *     tags={"{{ ucfirst($name) }}"},
     *     security={!! $bearerAuth !!},
     *     summary="Retrieve a single {{ strtolower($name) }} by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the {{ strtolower($name) }} to retrieve",
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="{{ ucfirst($name) }} retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="id",
     *                 type="integer",
     *                 example=1,
     *                 description="The unique identifier of the {{ strtolower($name) }}"
     *             ),
     *             @OA\Property(
     *                 property="name",
     *                 type="string",
     *                 example="Sample Name",
     *                 description="The name of the {{ strtolower($name) }}"
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="{{ ucfirst($name) }} not found"
     *     )
     * )
     */
    public function show(int $id): JsonResponse
    {
        try {
            $data = $this->{{ lcfirst($name) }}Service->findById($id);
            
            if (!$data) {
                return $this->error('{{ ucfirst($name) }} not found.', [], ResponseAlias::HTTP_NOT_FOUND);
            }

            return $this->success($data, '{{ ucfirst($name) }} retrieved successfully', ResponseAlias::HTTP_OK);
        } catch (Exception $e) {
            return $this->error('Could not retrieve {{ strtolower($name) }}.', [], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/admin/api-{{ strtolower($name) }}/{id}",
     *     tags={"{{ ucfirst($name) }}"},
     *     security={!! $bearerAuth !!},
     *     summary="Update an existing {{ strtolower($name) }}",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the {{ strtolower($name) }} to update",
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="name",
     *                 type="string",
     *                 example="Updated name",
     *                 description="The updated name of the {{ strtolower($name) }}"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="{{ ucfirst($name) }} updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="id",
     *                 type="integer",
     *                 example=1,
     *                 description="The unique identifier of the updated {{ strtolower($name) }}"
     *             ),
     *             @OA\Property(
     *                 property="name",
     *                 type="string",
     *                 example="Updated name",
     *                 description="The updated name of the {{ strtolower($name) }}"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="{{ ucfirst($name) }} not found"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     )
     * )
     */
    public function update(int $id, Update{{ ucfirst($name) }}Request $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            ${{ lcfirst($name) }} = $this->{{ lcfirst($name) }}Service->findById($id);
            
            if (!${{ lcfirst($name) }}) {
                return $this->error('{{ ucfirst($name) }} not found.', [], ResponseAlias::HTTP_NOT_FOUND);
            }
            
            $data = $this->{{ lcfirst($name) }}Service->update(['id' => $id], $request->all());
            
            DB::commit();
            return $this->success($data, '{{ ucfirst($name) }} updated successfully', ResponseAlias::HTTP_OK);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error('Could not update {{ strtolower($name) }}.', [], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/admin/api-{{ strtolower($name) }}/{id}",
     *     tags={"{{ ucfirst($name) }}"},
     *     security={!! $bearerAuth !!},
     *     summary="Delete an existing {{ strtolower($name) }}",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the {{ strtolower($name) }} to delete",
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="{{ ucfirst($name) }} Deleted successfully",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="{{ ucfirst($name) }} not found"
     *     )
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        DB::beginTransaction();
        try {
            $data = $this->{{ lcfirst($name) }}Service->findById($id);
            
            if (!$data) {
                return $this->error('{{ ucfirst($name) }} not found.', [], ResponseAlias::HTTP_NOT_FOUND);
            }

            $this->{{ lcfirst($name) }}Service->deleteById($id);
            
            DB::commit();
            return $this->success([], '{{ ucfirst($name) }} deleted successfully!', ResponseAlias::HTTP_NO_CONTENT);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error('Could not delete {{ strtolower($name) }}.', [], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
