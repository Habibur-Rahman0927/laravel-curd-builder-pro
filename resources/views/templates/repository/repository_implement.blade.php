namespace App\Repositories\{{$name}};

use App\Models\{{ $name }};
use App\Repositories\BaseRepository;

class {{ $name }}Repository extends BaseRepository implements I{{ $name }}Repository
{
    public function __construct({{ $name }} $model)
    {
        parent::__construct($model);
    }
}
