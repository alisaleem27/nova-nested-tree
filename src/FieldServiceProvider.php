<?php

namespace ZakariaTlilani\NovaNestedTree;

use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;
use ZakariaTlilani\NovaNestedTree\Domain\Cache\ArrayCache;
use ZakariaTlilani\NovaNestedTree\Domain\Cache\Cache;
use ZakariaTlilani\NovaNestedTree\Domain\Relation\Handlers\BelongsToHandler;
use ZakariaTlilani\NovaNestedTree\Domain\Relation\Handlers\BelongsToManyHandler;
use ZakariaTlilani\NovaNestedTree\Domain\Relation\Handlers\HasManyHandler;
use ZakariaTlilani\NovaNestedTree\Domain\Relation\RelationHandlerFactory;
use ZakariaTlilani\NovaNestedTree\Domain\Relation\RelationHandlerResolver;

class FieldServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Nova::serving(function (ServingNova $event) {
            Nova::script('nova-nested-tree-attach-many', __DIR__.'/../dist/js/field.js');
            Nova::style('nova-nested-tree-attach-many', __DIR__.'/../dist/css/field.css');
        });

        $this->app->booted(function () {
            \Route::middleware(['nova'])
                ->prefix('nova-vendor/nova-nested-tree-attach-many')
                ->group(__DIR__.'/../routes/api.php');
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(RelationHandlerFactory::class, RelationHandlerResolver::class);

        $factory = $this->app->make(RelationHandlerFactory::class);

        $factory->register($this->app->make(BelongsToManyHandler::class));
        $factory->register($this->app->make(BelongsToHandler::class));
        $factory->register($this->app->make(HasManyHandler::class));


        $this->app->singleton(Cache::class, ArrayCache::class);
    }
}
