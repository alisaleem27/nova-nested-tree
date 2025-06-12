<?php

namespace ZakariaTlilani\NovaNestedTree\Http\Controllers;

use Illuminate\Routing\Controller;
use Laravel\Nova\Http\Requests\NovaRequest;
use ZakariaTlilani\NovaNestedTree\Domain\Relation\RelationHandlerFactory;

class NestedTreeController extends Controller
{
    private $factory;

    public function __construct(RelationHandlerFactory $factory)
    {
        $this->factory = $factory;
    }

    public function attached(NovaRequest $request, $resource, $resourceId, $relationship, $idKey)
    {
        $model = $request->findResourceOrFail()->model();

        $handler = $this->factory->make($model->{$relationship}());

        return $handler->retrieve($model, $relationship, $idKey);
    }
}
