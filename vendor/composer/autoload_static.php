<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitfac4006c13c7ee2efa170112587017ed
{
    public static $prefixLengthsPsr4 = array (
        'M' => 
        array (
            'Multimonos\\' => 11,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Multimonos\\' => 
        array (
            0 => __DIR__ . '/../..' . '/lib/Multimonos',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitfac4006c13c7ee2efa170112587017ed::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitfac4006c13c7ee2efa170112587017ed::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitfac4006c13c7ee2efa170112587017ed::$classMap;

        }, null, ClassLoader::class);
    }
}