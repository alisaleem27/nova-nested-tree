<?php
namespace ZakariaTlilani\NovaNestedTree\Domain\Relation\Handlers;

interface RelationHandler
{
    public function relation(): string;

    public function attach( $model, $relationship, $values): void;

    public function retrieve($model, $relationship, $idKey);
}
