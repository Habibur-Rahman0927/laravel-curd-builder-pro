<?php 


namespace App\Repositories\Language;

use App\Models\Language;
use App\Repositories\BaseRepository;

class LanguageRepository extends BaseRepository implements ILanguageRepository
{
    public function __construct(Language $model)
    {
        parent::__construct($model);
    }
}
