<?php

use Illuminate\Http\Request;
use Dingo\Api\Transformer\Adapter\Fractal;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$api = app(\Dingo\Api\Routing\Router::class);

$api->version('v1', function ($api) {

    $api->get('/tag/{id}', function ($id) {
        return \App\Models\Tag::findOrFail($id);
    })->name('tag.detail');

    $api->get('/tag/{id}/url', function ($id) {
        $url = app(\Dingo\Api\Routing\UrlGenerator::class)
            ->version('v1')
            ->route('tag.detail', ['id' => $id]);
        return $url;
    });

    $api->get('/fractal/resource/item', function () {
        $task = \App\Models\Tag::findOrFail(1);
        $resource = new \League\Fractal\Resource\Item($task, new \App\Transformers\TagTransformer());
        $fractal = new \League\Fractal\Manager();
        return $fractal->parseIncludes('user')->createData($resource)->toJson();
    });

    $api->get('/fractal/resource/collection', function () {
        $tasks = \App\Models\Tag::all();
        $resource = new \League\Fractal\Resource\Collection($tasks, new \App\Transformers\TagTransformer());
        $fractal = new \League\Fractal\Manager();
        return $fractal->parseIncludes('user')->createData($resource)->toJson();
    });

    $api->get('/fractal/serializers', function () {
        $task = \App\Models\Tag::findOrFail(1);
        $resource = new \League\Fractal\Resource\Item($task, function (\App\Models\Tag $task) {
            return [
                'id' => $task->id,
                'title' => $task->title,
                'is_draft' => $task->is_completed ? 'yes' : 'no'
            ];
        });
        $fractal = new \League\Fractal\Manager();
        $fractal->setSerializer(new \League\Fractal\Serializer\JsonApiSerializer());
        return $fractal->createData($resource)->toJson();
    });

    Route::get('fractal/paginator', function () {
        $paginator = \App\Models\Tag::paginate();
        $tasks = $paginator->getCollection();

        $resource = new \League\Fractal\Resource\Collection($tasks, new \App\Transformers\TaskTransformer());
        $resource->setPaginator(new \League\Fractal\Pagination\IlluminatePaginatorAdapter($paginator));

        $fractal = new \League\Fractal\Manager();
        return $fractal->createData($resource)->toJson();
    });
});

$api->version('v2',function ($api){
    $api->get('/tag/{id}',function ($id){
        return \App\Models\Tag::findOrFail($id);
    })->name('tag.detail');
});

$api->version('v3',function ($api){
    $api->resource('tags',\App\Http\Controllers\Api\TagController::class);
});



