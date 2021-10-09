<?php

declare(strict_types=1);

namespace Faust\JsonApiPaginate;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Arr;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('json-api-paginate')
            ->hasConfigFile();
    }

    public function packageRegistered(): void
    {
        $pagination = function (int $maxResults = null, int $defaultSize = null) {
            $maxResults          = $maxResults ?? config('json-api-paginate.max_results');
            $defaultSize         = $defaultSize ?? config('json-api-paginate.default_size');
            $numberParameter     = config('json-api-paginate.number_parameter');
            $sizeParameter       = config('json-api-paginate.size_parameter');
            $paginationParameter = config('json-api-paginate.pagination_parameter');
            $paginationMethod    = config('json-api-paginate.use_simple_pagination') ? 'simplePaginate' : 'paginate';

            $size = (int) request()->input($paginationParameter.'.'.$sizeParameter, $defaultSize);

            $size = $size > $maxResults ? $maxResults : $size;

            $paginator = $this
                ->{$paginationMethod}($size, ['*'], $paginationParameter.'.'.$numberParameter)
                ->setPageName($paginationParameter.'['.$numberParameter.']')
                ->appends(Arr::except(request()->input(), $paginationParameter.'.'.$numberParameter));

            if (! is_null(config('json-api-paginate.base_url'))) {
                $paginator->setPath(config('json-api-paginate.base_url'));
            }

            return $paginator;
        };

        Builder::macro(config('json-api-paginate.method_name'), $pagination);
        BelongsToMany::macro(config('json-api-paginate.method_name'), $pagination);
        HasManyThrough::macro(config('json-api-paginate.method_name'), $pagination);
    }
}
