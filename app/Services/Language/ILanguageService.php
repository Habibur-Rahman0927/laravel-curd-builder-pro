<?php 


namespace App\Services\Language;
use App\Services\IBaseService;

interface ILanguageService extends IBaseService
{
    public function getLanguageData();
}
