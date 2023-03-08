<?php

namespace Designbycode\Datatables;

use Designbycode\Datatables\Commands\DatatablesCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class DatatablesServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-datatables')
            ->hasConfigFile()
            ->hasViews()
//            ->hasMigration('create_laravel-datatables_table')
            ->hasCommand(DatatablesCommand::class);
    }
}
