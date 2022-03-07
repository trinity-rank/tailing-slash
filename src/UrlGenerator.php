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

        $endingSlash = "/";

        // For search pagination
        if( preg_match("~\?q=~", $url) ) {
            $endingSlash = "";
            // Remove old pagination param (with slashes)
            $url = preg_replace("~\/page\/\d+\?~", "?", $url);
            // Fix laravel default pagination with questionmark
            $url = preg_replace("~\?q=(.*)&page=(.*)~", "/page/$2/?q=$1", $url);
        }
        
        // Find query string start character
        if( preg_match("~\?page=~", $url) ) {
            // Remove old pagination param (with slashes)
            $url = preg_replace("~\/page\/\d+\?~", "?", $url);
            // Fix laravel default pagination with questionmark
            $url = preg_replace("~\?(page)=~", "/$1/", $url);
        }

        // For page 1 do not use page paramether
        if( preg_match("~/page/1$~", $url) ) {
            $url = preg_replace("~/page/1$~", "/", $url);
        }

        return trim($url, "/") . $endingSlash;
    }

    public static function paginationCheck($pageNumber, $pageType = "page")
    {
        // For MultiLanguage websites
        $locales = config('app.locales');
        if( $locales ) {
            if( in_array($pageNumber, $locales) ) {
                return true;
            }
        }
        
        // Custom pagination
        if( $pageNumber !== null ) {
            $pageNumber = (int) $pageNumber;
            if( $pageNumber === 0 ) {
                abort(404);
            }
            return request()->request->add([$pageType => $pageNumber]);
        }
    }


    public static function language($pageNumber = null, $lang = null)
    {
        $locales = config('app.locales');
        if( $locales && $lang == null) {
            if( in_array($pageNumber, $locales) ) {
                return $pageNumber;
            }
        }
        return $lang;
    }

}
