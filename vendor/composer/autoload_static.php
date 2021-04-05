<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitd77a88adf22cd8f91b93559edd70ab44
{
    public static $prefixLengthsPsr4 = array (
        'D' => 
        array (
            'Derweili\\DynamicProductListing\\' => 31,
        ),
        'C' => 
        array (
            'Carbon_Fields\\' => 14,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Derweili\\DynamicProductListing\\' => 
        array (
            0 => __DIR__ . '/../..' . '/inc',
        ),
        'Carbon_Fields\\' => 
        array (
            0 => __DIR__ . '/..' . '/htmlburger/carbon-fields/core',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitd77a88adf22cd8f91b93559edd70ab44::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitd77a88adf22cd8f91b93559edd70ab44::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
