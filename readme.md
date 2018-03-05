# Test Browser By Dusk Lib
    composer require --dev laravel/dusk

    - Register for provider service:
        config/app.php -> set 'Laravel\Dusk\DuskServiceProvider::class' in 'providers'   

    - run 'php artisan dusk:install' => folder 'browser' created in folder 'tests'
        -> set APP_URL environment variable in .env file (application url in browser)

    - To run your tests:
        run 'php artisan dusk'