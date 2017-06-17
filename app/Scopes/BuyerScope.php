<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;



/* Writing your own global scopes can provide a convenient, 
 * easy way to make sure every query for a given model receives certain constraints. 
 */


class BuyerScope implements Scope
{

	public function apply(Builder $builder, Model $model)
	{
		$builder->has('transactions');
	}
}