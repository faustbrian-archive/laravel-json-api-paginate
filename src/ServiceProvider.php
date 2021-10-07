<?php

declare(strict_types=1);

namespace Faust\JsonApiPaginate;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
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

    public function register(): void
    {
        $pagination = resolve(ForwardPagination::class);

        Builder::macro(config('json-api-paginate.method_name'), fn (int $maxResults = null, int $defaultSize = null) => $pagination($this, $maxResults, $defaultSize));
        BelongsToMany::macro(config('json-api-paginate.method_name'), fn (int $maxResults = null, int $defaultSize = null) => $pagination($this, $maxResults, $defaultSize));
        HasManyThrough::macro(config('json-api-paginate.method_name'), fn (int $maxResults = null, int $defaultSize = null) => $pagination($this, $maxResults, $defaultSize));
    }
}
