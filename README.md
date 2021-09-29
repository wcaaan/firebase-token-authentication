# Firebase Token Authentication for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/wcaaan/firebase-token-authentication.svg?style=flat-square)](https://packagist.org/packages/wcaaan/firebase-token-authentication)
[![Total Downloads](https://img.shields.io/packagist/dt/wcaaan/firebase-token-authentication.svg?style=flat-square)](https://packagist.org/packages/wcaaan/firebase-token-authentication)
 
The driver contains a firebase guard that authenticates user by Firebase Authentication JWT token. To login use [Firebase Authentication](https://firebase.google.com/docs/auth/web/firebaseui).

## Compatibility & Requirement

The package is tested with 
    `php >= 7.4`
    `Laravel 7.3`
    `lcobucci/jwt 4.0`

## Installation

You can install the package via composer:

```bash
composer require wcaaan/firebase-token-authentication
```

### Update `config/auth.php`.
```
'guards' => [
    'web' => [
        'driver' => 'firebase',
        'provider' => 'users',
    ],

    'api' => [
        'driver' => 'firebase',
        'provider' => 'users',
    ],
],
```

## Configuration

```bash
php artisan vendor:publish --provider="Wcaaan\FirebaseTokenAuthentication\FirebaseTokenAuthenticationServiceProvider" --tag="config"
```

```php
return [
    /*
     * The firebase_project_id key is used when connecting to firebase authentication.
     */
    'firebase_project_id' => '',
    /*
     * The target_provider key is used for connecting with your desired model.
     * by default laravel provider is users
     * If target_provider is not set, by defalt users will be used.
     * Example: In below example your target_provider is users
     *
     * 'providers' => [
     *		'users' => [
     *			'driver' => 'eloquent',
     *			'model' => App\User::class,
     *		],
     *	],
     *
     */
    'target_provider' => 'users',
];
```

## Implementation

### Update `User` model

Update your authentication model. Generally it is `User` 

`use Wcaaan\FirebaseTokenAuthentication\FirebaseTokenAuthenticable`
 
`public $incrementing = false;`

`protected $fillable = [
    'name', 'email', 'phone', 'image'
];`


Eloquent example:
```
<?php

namespace App;

use Wcaaan\FirebaseTokenAuthentication\FirebaseTokenAuthenticable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable, FirebaseTokenAuthenticable;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'phone', 'image'
    ];
}

```
Firequent example:
```
<?php

namespace App;

use Wcaaan\FirebaseTokenAuthentication\FirebaseTokenAuthenticable;
use Firevel\Firequent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Model implements Authenticatable
{
    use Notifiable, FirebaseTokenAuthenticable;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'phone', 'image'
    ];

}

```

### If you are using Eloquent you need to create or update migration for users table manually.
```
$table->string('id')->primary();
$table->string('name')->nullable();
$table->string('email')->unique();
$table->string('phone')->unique();
$table->string('image')->nullable();
$table->timestamps();
```

## Usage

## API Guard

Your can apply `auth:api` middleware to group of routes or single route.

`Route::group(['middleware' => ['auth:api']], function ()
{
    // routes goes here
});`

`Route::get('/testing', function (Request $request) 
{
     return (array) $request->user();
})
->middleware('auth:api');`

For testing i have used postman, in postman header provide key `Authorization` and value `Bearer ...` with token.
The obeject you receive from firebase login must have access_token. Please replace the three dots in Bearer token with your actual access_token.

## Web Guard

In order to use firebase token authentication in web routes you must attach bearer token to each http request.

You can also store bearer token in `bearer_token` cookie variable and add to your `Kernel.php`:
```
    protected $middlewareGroups = [
        'web' => [
            ...
            \Wcaaan\FirebaseTokenAuthentication\Http\Middleware\AddAccessTokenFromCookie::class,
            ...
        ],

        ...
    ];
```

If you are using `EncryptCookies` middleware you must set:

```
    protected $except = [
        ...
        'bearer_token',
        ...
    ];
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

This project is forked from [firevel/firebase-authentication](https://github.com/firevel/firebase-authentication). I have modified it to make it compatible with `lcobucci/jwt 4.0` 
I have also modified some of the behaviour of actual repository and added a configuration file.

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email wcaaan@gmail.com instead of using the issue tracker.

## Credits

-   [WCan](https://github.com/wcaaan)
-   [firevel/firebase-authentication](https://github.com/firevel/firebase-authentication)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
