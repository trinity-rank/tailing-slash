# Very short description of the package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/trinity-rank/tailing-slash.svg?style=flat-square)](https://packagist.org/packages/trinityrank/tailing-slash)

Adds URL formatting and redirection with trailing slash to Laravel framework.

## Installation

### Step 1: Install package

To get started with Laravel Trailing Slash, use Composer command to add the package to your composer.json project's dependencies:

```bash
composer require trinity-rank/tailing-slash
```

### Step 2: Service Provider

After installing the package, register [Trinityrank\TailingSlash\TailingSlashServiceProvider] in your [config/app.php].

```php
'providers' => [
    // Aplication Service Providers...
    // ...

    // Other Service Providers...
    Trinityrank\TailingSlash\TailingSlashServiceProvider::class
    // ...
],
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

    return redirect()->route('text', ['id' => 1]);

    return redirect()->action('IndexController@about');
```
### Notice

There is a problem with overriding Laravel [Paginator] and [LengthAwarePaginator] classes. So, every time you use [paginate()] method on your models, query builders etc., you must set current path for pagination links. Example:

```php
    $posts = Text::where('is_active', 1)->paginate();
    $posts->setPath(URL::current());

    $posts->links();
```