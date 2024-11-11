<?php 


namespace App\Services\User;

use App\Repositories\User\IUserRepository;
use App\Services\BaseService;
use Exception;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;

class UserService extends BaseService implements IUserService
{
    public function __construct(private IUserRepository $userRepository)
    {
        parent::__construct($userRepository);
    }

    /**
     * Retrieve user data for DataTables.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserData(): JsonResponse
    {
        try {
            $data = $this->userRepository->findAll([], ['id', 'name', 'email', 'created_at']);
            return DataTables::of($data)
                ->addColumn('action', function($data){
                    return $data->id;
                })->toJson();
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Could not retrieve data. Please try again later.',
            ]);
        }
    }
}
