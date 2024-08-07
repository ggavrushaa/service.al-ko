<?php

namespace App\Traits;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait BelongsToManyCustomConnectionTrait
{
    public function belongsToManyCustomConnection(
        $related,
        $table = null,
        $foreignPivotKey = null,
        $relatedPivotKey = null,
        $parentKey = null,
        $relatedKey = null,
        $relation = null,
        $connection = null
    ): BelongsToMany {
        $instance = $this->newRelatedInstance($related);

        $table = $table ?: $this->joiningTable($related);

        $foreignPivotKey = $foreignPivotKey ?: $this->getForeignKey();
        $relatedPivotKey = $relatedPivotKey ?: $instance->getForeignKey();

        $parentKey = $parentKey ?: $this->getKeyName();
        $relatedKey = $relatedKey ?: $instance->getKeyName();

        if (is_null($relation)) {
            $relation = $this->guessBelongsToManyRelation();
        }

        $query = $instance->newQuery();

        if ($connection) {
            $query->setConnection($connection);
        }

        return $this->newBelongsToMany(
            $query,
            $this,
            $table,
            $foreignPivotKey,
            $relatedPivotKey,
            $parentKey,
            $relatedKey,
            $relation
        );
    }
}
