<?php 


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Language\ILanguageService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\CreateLanguageRequest;
use App\Http\Requests\UpdateLanguageRequest;
use Illuminate\Http\JsonResponse;
use Exception;

class LanguageController extends Controller
{

    public function __construct(private ILanguageService $languageService)
    {

    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
     public function index(): View
    {
        return view('admin.language.index')->with([]);
    }

    /**
     * Get language data for DataTables.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDatatables(Request $request): JsonResponse
    {
        if ($request->ajax()) {
            return $this->languageService->getLanguageData();
        }
        return response()->json([
            'success' => false,
            'message' => 'Invalid request.',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        $combinedLanguageKeys = $this->languageService->getLanguageFiles();
        return view('admin.language.create')->with([
            'combinedLanguageKeys' => $combinedLanguageKeys
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param LanguageRequest $request
     * @return RedirectResponse
     */
    public function store(CreateLanguageRequest $request): RedirectResponse
    {
        try {
            $response = $this->languageService->createLanguage($request->only('name', 'code', 'is_default', 'translations'));

            if ($response) {
                return redirect()->back()->with('success', 'Language added successfully.');
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }

        return redirect()->back()->with('error', 'Something went wrong. Please try again.');
    }

    /**
     * Display the specified resource.
     *
     * @param string $id
     * @return View
     */
    public function show(string $id) // : View
    {
        // You can add logic to fetch and return data for the specific resource here.
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param string $id
     * @return View
     */
    public function edit(string $id): View
    {
        try {
            $response = $this->languageService->findById($id);
            if ($response) {
                $combinedLanguageKeys = $this->languageService->getLanguageFiles($response->code);
            }
            return view('admin.language.edit')->with([
                'data' => $response,
                'combinedLanguageKeys' => $combinedLanguageKeys
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error retrieving the resource.');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateLanguageRequest $request
     * @param string $id
     * @return RedirectResponse
     */
    public function update(UpdateLanguageRequest $request, string $id): RedirectResponse
    {
        try {
            $data = $request->except(['_token', '_method']);
            $this->languageService->updateLanguage($id, $data);

            return redirect()->back()->with('success', 'Language updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong while updating.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $data = $this->languageService->deleteById($id);

            if ($data) {
                return response()->json([
                    'message' => 'Language deleted successfully',
                    'status_code' => ResponseAlias::HTTP_OK,
                    'data' => []
                ], ResponseAlias::HTTP_OK);
            }

            return response()->json([
                'message' => 'Language is not deleted successfully',
                'status_code' => ResponseAlias::HTTP_BAD_REQUEST,
                'data' => []
            ], ResponseAlias::HTTP_BAD_REQUEST);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while trying to delete.',
                'status_code' => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR,
                'data' => []
            ], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
