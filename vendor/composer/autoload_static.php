<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit3947fbe0df98a15c2b30a6ee5520a6f8
{
    public static $prefixLengthsPsr4 = array (
        's' => 
        array (
            'sxqibo\\cloudprinter\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'sxqibo\\cloudprinter\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit3947fbe0df98a15c2b30a6ee5520a6f8::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit3947fbe0df98a15c2b30a6ee5520a6f8::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit3947fbe0df98a15c2b30a6ee5520a6f8::$classMap;

        }, null, ClassLoader::class);
    }
}
