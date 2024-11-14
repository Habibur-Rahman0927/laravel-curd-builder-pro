<?php 


namespace App\Services\Language;

use App\Repositories\Language\ILanguageRepository;
use App\Services\BaseService;
use Exception;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;

class LanguageService extends BaseService implements ILanguageService
{
    public function __construct(private ILanguageRepository $languageRepository)
    {
        parent::__construct($languageRepository);
    }

    /**
     * Retrieve user data for DataTables.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLanguageData(): JsonResponse
    {
        try {
            $data = $this->languageRepository->findAll([]);
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
