

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\{{ ucfirst($name) }}\I{{ ucfirst($name) }}Service;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Create{{ ucfirst($name) }}Request;
use App\Http\Requests\Update{{ ucfirst($name) }}Request;
use Illuminate\Http\JsonResponse;
use Exception;

class {{ ucfirst($name) }}Controller extends Controller
{

    public function __construct(private I{{ ucfirst($name) }}Service ${{ lcfirst($name) }}Service)
    {

    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
     public function index(): View
    {
        return view('admin.{{ lcfirst($name) }}.index')->with([]);
    }

    /**
     * Get {{ lcfirst($name) }} data for DataTables.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDatatables(Request $request): JsonResponse
    {
        if ($request->ajax()) {
            return $this->{{ lcfirst($name) }}Service->get{{ ucfirst($name) }}Data();
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
        return view('admin.{{ lcfirst($name) }}.create')->with([]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param {{ ucfirst($name) }}Request $request
     * @return RedirectResponse
     */
    public function store(Create{{ ucfirst($name) }}Request $request): RedirectResponse
    {
        try {
            $response = $this->{{ lcfirst($name) }}Service->create($request->all());

            if ($response) {
                return redirect()->back()->with('success', '{{ ucfirst($name) }} added successfully.');
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
            $response = $this->{{ lcfirst($name) }}Service->findById($id);

            return view('admin.{{ lcfirst($name) }}.edit')->with([
                'data' => $response,
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error retrieving the resource.');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Update{{ ucfirst($name) }}Request $request
     * @param string $id
     * @return RedirectResponse
     */
    public function update(Update{{ ucfirst($name) }}Request $request, string $id): RedirectResponse
    {
        try {
            $data = $request->except(['_token', '_method']);
            $this->{{ lcfirst($name) }}Service->update(['id' => $id], $data);

            return redirect()->back()->with('success', '{{ ucfirst($name) }} updated successfully.');
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
            $data = $this->{{ lcfirst($name) }}Service->deleteById($id);

            if ($data) {
                return response()->json([
                    'message' => '{{ ucfirst($name) }} deleted successfully',
                    'status_code' => ResponseAlias::HTTP_OK,
                    'data' => []
                ], ResponseAlias::HTTP_OK);
            }

            return response()->json([
                'message' => '{{ ucfirst($name) }} is not deleted successfully',
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
