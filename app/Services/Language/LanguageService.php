<?php 


namespace App\Services\Language;

use App\Repositories\Language\ILanguageRepository;
use App\Services\BaseService;
use Exception;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

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

    /**
     * Create a new language and generate a translation file.
     *
     * @param array $data
     * @return Language
     */
    public function createLanguage(array $data)
    {
        return DB::transaction(function () use ($data) {
            $language = $this->create([
                'name' => $data['name'],
                'code' => $data['code'],
                'is_default' => $data['is_default'] ?? false,
            ]);

            $this->translateFile($data['translations'], $language->code);

            return $language;
        });
    }

    /**
     * Update existing language translations.
     *
     * @param int $id
     * @param array $data
     * @return Language
     */
    public function updateLanguage(int $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $language = $this->update(['id' => $id], [
                'name' => $data['name'],
                'code' => $data['code'],
                'is_default' => $data['is_default'] ?? false,
            ]);

            $this->translateFile($data['translations'], $data['code']);

            return $language;
        });
    }

    /**
     * Create or update language translation files.
     *
     * @param array $data
     * @param string $code
     */
    private function translateFile(array $data, string $code)
    {
        foreach ($data as $file => $translations) {
            $filePath = base_path("lang/{$code}/{$file}.php");

            File::ensureDirectoryExists(base_path("lang/{$code}"));

            $content = "<?php\n\nreturn " . var_export($translations, true) . ";\n";

            File::put($filePath, $content);
        }
    }

    /**
     * Retrieve all language keys and values from files in the specified directory.
     *
     * @param string $languageCode
     * @return array
     */
    public function getLanguageFiles(string $languageCode = 'en'): array
    {
        $combinedLanguageKeys = [];
        $langDirectory = base_path("lang/{$languageCode}");

        if (File::exists($langDirectory)) {
            foreach (File::files($langDirectory) as $file) {
                $fileName = pathinfo($file, PATHINFO_FILENAME);
                $fileContents = File::getRequire($file->getRealPath());

                if (is_array($fileContents)) {
                    $combinedLanguageKeys[$fileName] = $fileContents;
                } else {
                    $combinedLanguageKeys[$fileName] = []; // Handle non-array files
                }
            }
        }

        return $combinedLanguageKeys;
    }
}
