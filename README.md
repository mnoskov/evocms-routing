# [WIP] evocms-routing

Controller.php:
```php
<?php

namespace EvolutionCMS\Application\Controllers;

use EvolutionCMS\Models\SiteContent;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Redirect;

class Controller extends BaseController
{
    public function index()
    {
        $content = SiteContent::where('parent', 0)->get();

        return Response::json([
            'status'    => 'success',
            'resources' => $content->toArray(),
        ]);
    }

    public function getAction()
    {
        return Response::make('Action!', 200);
    }

    public function redirectTo($docid)
    {
        return Redirect::to($docid);
    }
}
```

routes.php:
```php
<?php
use Illuminate\Support\Facades\Route;
use EvolutionCMS\Application\Controllers\Controller;

Route::prefix('/api')->group(function() {
    Route::get('/', Controller::class . '@index');
    Route::get('/action', Controller::class . '@getAction');
});

```
