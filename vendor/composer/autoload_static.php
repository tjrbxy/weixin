<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitcbb0190e9abb131d9293b6db05024dbc
{
    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'Weixin\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Weixin\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/Weixin',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitcbb0190e9abb131d9293b6db05024dbc::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitcbb0190e9abb131d9293b6db05024dbc::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
