<?php 


namespace App\Services\Language;
use App\Services\IBaseService;

interface ILanguageService extends IBaseService
{
    public function getLanguageData();

    /**
     * Create a new language and generate a translation file.
     *
     * @param array $data
     * @return Language
     */
    public function createLanguage(array $data);

    /**
     * update language and update generate a translation file.
     * 
     * @param int $int
     * @param array $data
     * @return Language
     */
    public function updateLanguage(int $int, array $data);

    /**
     * Retrieve all language keys and values from files in the specified directory.
     *
     * @param string $languageCode
     * @return array
     */
    public function getLanguageFiles(string $languageCode = 'en'): array;
}
