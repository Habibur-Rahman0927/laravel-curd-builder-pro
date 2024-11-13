
namespace App\Services\{{$name}};

use App\Repositories\{{ $name }}\I{{ $name  }}Repository;
use App\Services\BaseService;
use Exception;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;

class {{ $name  }}Service extends BaseService implements I{{ $name  }}Service
{
    public function __construct(private I{{ $name  }}Repository ${{  strtolower($name) }}Repository)
    {
        parent::__construct(${{  strtolower($name) }}Repository);
    }

    /**
     * Retrieve user data for DataTables.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get{{ ucfirst($name) }}Data(): JsonResponse
    {
        try {
            $data = $this->{{  strtolower($name) }}Repository->findAll([]);
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
