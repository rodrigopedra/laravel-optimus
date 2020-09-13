<?php

namespace RodrigoPedra\LaravelOptimus\Traits;

use Illuminate\Database\Eloquent\Builder;

/**
 * Trait HasOptimusKey
 *
 * @package RodrigoPedra\LaravelOptimus\Traits
 */
trait UsesOptimusKey
{
    /**
     * Get the value of the model's route key.
     *
     * @return mixed
     */
    public function getRouteKey()
    {
        if (! $this->exists) {
            return null;
        }

        $key = parent::getRouteKey();

        return \resolve('optimus')->encode($key);
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed  $value
     * @param  string|null  $field
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value, $field = null)
    {
        return $this->newQuery()->withOptimusKey($value)->first();
    }

    /**
     * Add a query scope using the encoded key
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithOptimusKey(Builder $builder, $value)
    {
        $key = \resolve('optimus')->decode($value);

        $builder->where($this->getRouteKeyName(), '=', $key);

        return $builder;
    }
}
