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
        'sitemap/author_sitemap.xml',
        'sitemap/deals_sitemap.xml'
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

    public static function paginationLinks($url)
    {
        if( $url == null ) {
            return $url;
        }

        // Find query string start character
        if( preg_match("~\?([A-Za-z]+)=~", $url) ) {
            // Remove old pagination param (with slashes)
            $url = preg_replace("~\/[A-Za-z]+\/\d+~", "", $url);
            // Fix laravel default pagination with questionmark
            $url = preg_replace("~\?([A-Za-z]+)=~", "/$1/", $url);
        }

        // For page 1 do not use page paramether
        if( preg_match("~\/([A-Za-z]+)\/1~", $url) ) {
            $url = preg_replace("~\/([A-Za-z]+)\/1~", "/", $url);
        }

        return trim($url, "/") ."/";
    }

    public static function paginationCheck($pageNumber, $pageType = "page")
    {
        if( $pageNumber !== null ) {
            $pageNumber = (int) $pageNumber;
            if( $pageNumber === 0 ) {
                abort(404);
            }
            return request()->request->add([$pageType => $pageNumber]);
        }
    }

}
