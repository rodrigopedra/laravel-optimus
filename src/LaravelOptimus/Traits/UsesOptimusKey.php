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
        if (!$this->exists) {
            return null;
        }

        $key = parent::getRouteKey();

        return app( 'optimus' )->encode( $key );
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed $key
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding( $key )
    {
        return $this->newQuery()->withOptimusKey( $key )->first();
    }

    /**
     * Add a query scope using the encoded key
     *
     * @param  \Illuminate\Database\Eloquent\Builder $builder
     * @param  mixed                                 $value
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithOptimusKey( Builder $builder, $value )
    {
        $key = app( 'optimus' )->decode( $value );

        $builder->where( $this->getRouteKeyName(), '=', $key );

        return $builder;
    }
}
