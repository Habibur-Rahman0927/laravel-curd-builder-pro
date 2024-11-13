

namespace App\Services\{{ ucfirst($name) }};
use App\Services\IBaseService;

interface I{{ ucfirst($name) }}Service extends IBaseService
{
    public function get{{ ucfirst($name) }}Data();
}
