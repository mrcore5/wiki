<?php namespace Mrcore\Modules\Wiki\Auth;

// use mrcore support cache!!!!!!!!!!!! ????????
// though where does that reside? foundation probably?
// Mrcore\Foundation\Support\Cache
use Cache;
use Illuminate\Auth\EloquentUserProvider;

class WikiUserProvider extends EloquentUserProvider {

	/**
	 * Retrieve a user by their unique identifier.
	 *
	 * @param  mixed  $identifier
	 * @return \Illuminate\Contracts\Auth\Authenticatable|null
	 */
	public function retrieveById($identifier)
	{
		return Cache::remember('userById_'.$identifier, 60,
			function() use($model, $identifier) {
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

		return Cache::remember('userByIdAndToken_'.$identifier.$token, 60,
			function() use($model, $identifier, $token) {
				return $model->newQuery()
					->where($model->getKeyName(), $identifier)
					->where($model->getRememberTokenName(), $token)
					->first();
			}
		);
	}

}