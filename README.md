# Laravel Optimus

Bridge to `jenssegers/optimus` for Laravel.

It handles Route Model Binding to use a hashed id.

## Installation

```
composer require rodrigopedra/laravel-optimus
```

It will auto-register the ServiceProvider.

## Configuration 

Run this command to generate your app's secret numbers and add them to you `.env`:

````bash
php artisan optimus:seed
````

## Usage

Add the `UsesOptimusKey` trait to the models you want to have an Optimus route key.

This trait will handle the custom model binding.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
