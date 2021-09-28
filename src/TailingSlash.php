<?php

namespace Trinityrank\TailingSlash;

use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Str;

class TailingSlash extends UrlGenerator
{
    protected $except = [
        'livewire/upload-file',
        'media-library-pro/uploads',
        'email/verify',
        'sitemap/category_sitemap.xml',
        'sitemap/news_sitemap.xml',
        'sitemap/blog_sitemap.xml',
        'sitemap/review_sitemap.xml',
        'sitemap/product_sitemap.xml',
        'sitemap/page_sitemap.xml'
    ];

    public function format($root, $path, $route = null)
    {
        return collect($this->except)->contains(function($element) use ($path) {
            return Str::contains($path, $element);
        })
        ? parent::format($root, $path, $route)
        : parent::format($root, $path, $route) . (Str::contains($path, '#') ? '' : '/');
    }
}
