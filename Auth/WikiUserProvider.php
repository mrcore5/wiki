<?php namespace Mrcore\Wiki\Auth;

use Mrcore\Foundation\Support\Cache;
use Illuminate\Auth\EloquentUserProvider;

class WikiUserProvider extends EloquentUserProvider
{

    /**
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed  $identifier
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveById($identifier)
    {
        return Cache::remember('auth/userprovider/user:'.$identifier,
            function () use ($identifier) {
                return $this->createModel()->newQuery()->find($identifier);
            }
        );
    }

    /**
     * Retrieve a user by their unique identifier and "remember me" token.
     *
     * @param  mixed  $identifier
     * @param  string  $token
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByToken($identifier, $token)
    {
        $model = $this->createModel();

        return Cache::remember('auth/userprovider/user:'.$identifier.$token,
            function () use ($model, $identifier, $token) {
                return $model->newQuery()
                    ->where($model->getKeyName(), $identifier)
                    ->where($model->getRememberTokenName(), $token)
                    ->first();
            }
        );
    }
}
