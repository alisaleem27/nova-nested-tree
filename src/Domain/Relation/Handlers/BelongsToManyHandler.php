<?php
namespace ZakariaTlilani\NovaNestedTree\Domain\Relation\Handlers;

class BelongsToManyHandler implements RelationHandler
{
    public function relation(): string
    {
        return 'Illuminate\Database\Eloquent\Relations\BelongsToMany';
    }

    public function attach( $model, $relationship, $values): void
    {
        $model->{$relationship}()->sync($values);
    }

    public function retrieve($model, $relationship, $idKey)
    {
        return $model->{$relationship}()->get()->pluck($idKey)->all();
    }
}
