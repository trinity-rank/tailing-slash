# Very short description of the package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/trinity-rank/tailing-slash.svg?style=flat-square)](https://packagist.org/packages/trinityrank/tailing-slash)

Adds URL formatting and redirection with trailing slash to Laravel framework.

## Installation

### Step 1: Install package

To get started with Laravel Trailing Slash, use Composer command to add the package to your composer.json project's dependencies:

```bash
composer require trinityrank/tailing-slash
```

### Step 2: Service Provider

After installing the package, register `Trinityrank\TailingSlash\RoutingServiceProvider` in your `config/app.php`.

```php
    'providers' => [
        /*
        * Package Service Providers...
        */
        // ...
        Trinityrank\TailingSlash\RoutingServiceProvider::class
        // ...
    ],
    'aliases' => [
        // ...
        'UrlGenerator' => Trinityrank\TailingSlash\UrlGenerator::class,
        // ...
    ].
```

### Step 3: Routes
```php
    Route::get('/', function () {
        return view('welcome');
    });

    Route::get('about-us/', function () {
        return view('about');
    });
```

### Usage

Whenever you use some Laravel redirect function, tailing slash ("/") will be applied to the end of url.

```php
    return redirect('about/');

    return back()->withInput();

    return redirect()->route('post', ['id' => 1]);

    return redirect()->action('IndexController@about');
```
### Notice

There is a problem with overriding Laravel `Paginator` and `LengthAwarePaginator` classes. So, every time you use `paginate()` method on your models, query builders etc., you must set current path for pagination links. Example:

```php
    $posts = Text::where('is_active', 1)->paginate();
    $posts->setPath(URL::current());

    $posts->links();
```

## Pagiantion
- Use this method to format pagination links on your blade component (tailwind.blade.php) and also use this for canonical links (check your `cleanCanonicalURL` in `BaseController`)

```php
    UrlGenerator::paginationLinks($url)
```

- On your 'web.php' add page route for pagination. Use same `controller` and same `method` as you will use for normal archive page (just `path` is changed)
- ADD ABOVE THE REGILAR METHOD (not below)

```php
    // Paginate emethod
    Route::get('{slug}/page/{page}', [FrontendController::class, 'resolve'])->name('resolve');
    // Your regulat method
    Route::get('{slug}', [FrontendController::class, 'resolve'])->name('resolve');
```

- In controller just add couple of thinks things
    - use `UrlGenerator` on top of the document
    - In method add new optional param `$pageNumber`
    - Add `UrlGenerator::paginationCheck` method at the beginning of the method

```php
        use Trinityrank\TailingSlash\UrlGenerator;
        // ...
        public function method($slug, $pageNumber = null){
            // This add at the beginning of the method
            UrlGenerator::paginationCheck($pageNumber);
            // ...
        }
```

- Method `paginationCheck` has second optional paramether if default paginate keyword is not `page`