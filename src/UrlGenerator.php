<?php

namespace Trinityrank\TailingSlash;

use Illuminate\Routing\UrlGenerator as BaseUrlGenerator;
use Illuminate\Support\Str;

class UrlGenerator extends BaseUrlGenerator
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
        'sitemap/page_sitemap.xml',
        'sitemap/author_sitemap.xml'
    ];

    public function format($root, $path, $route = null)
    {
        return collect($this->except)->contains(function($element) use ($path) {
            return Str::contains($path, $element);
        })
        ? parent::format($root, $path, $route)
        : parent::format($root, $path, $route) . (Str::contains($path, '#') ? '' : '/');
    }

    public static function tailingSlash($url)
    {
        // Find query string start character
        if( !Str::contains($url, "/?") ) {
            $url = Str::replace("?", "/?", $url);
        }
        // Find anchor tag
        if( !Str::contains($url, "/#") ) {
            $url = Str::replace("#", "/#", $url);
        }
        return $url;
    }


}
