<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitd32535a5d6635021063f9d6f72c4e4a2
{
    public static $files = array (
        '7b11c4dc42b3b3023073cb14e519683c' => __DIR__ . '/..' . '/ralouphie/getallheaders/src/getallheaders.php',
        'c964ee0ededf28c96ebd9db5099ef910' => __DIR__ . '/..' . '/guzzlehttp/promises/src/functions_include.php',
        '6e3fae29631ef280660b3cdad06f25a8' => __DIR__ . '/..' . '/symfony/deprecation-contracts/function.php',
        '37a3dc5111fe8f707ab4c132ef1dbc62' => __DIR__ . '/..' . '/guzzlehttp/guzzle/src/functions_include.php',
        'a4a119a56e50fbb293281d9a48007e0e' => __DIR__ . '/..' . '/symfony/polyfill-php80/bootstrap.php',
        'cd5441689b14144e5573bf989ee47b34' => __DIR__ . '/..' . '/qcloud/cos-sdk-v5/src/Common.php',
        '841780ea2e1d6545ea3a253239d59c05' => __DIR__ . '/..' . '/qiniu/php-sdk/src/Qiniu/functions.php',
    );

    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Symfony\\Polyfill\\Php80\\' => 23,
        ),
        'Q' => 
        array (
            'Qiniu\\' => 6,
            'Qcloud\\Cos\\' => 11,
            'QCloud\\COSSTS\\' => 14,
        ),
        'P' => 
        array (
            'Psr\\Http\\Message\\' => 17,
            'Psr\\Http\\Client\\' => 16,
        ),
        'O' => 
        array (
            'OSS\\' => 4,
        ),
        'M' => 
        array (
            'MyCLabs\\Enum\\' => 13,
        ),
        'G' => 
        array (
            'GuzzleHttp\\UriTemplate\\' => 23,
            'GuzzleHttp\\Psr7\\' => 16,
            'GuzzleHttp\\Promise\\' => 19,
            'GuzzleHttp\\Command\\Guzzle\\' => 26,
            'GuzzleHttp\\Command\\' => 19,
            'GuzzleHttp\\' => 11,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Symfony\\Polyfill\\Php80\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-php80',
        ),
        'Qiniu\\' => 
        array (
            0 => __DIR__ . '/..' . '/qiniu/php-sdk/src/Qiniu',
        ),
        'Qcloud\\Cos\\' => 
        array (
            0 => __DIR__ . '/..' . '/qcloud/cos-sdk-v5/src',
        ),
        'QCloud\\COSSTS\\' => 
        array (
            0 => __DIR__ . '/..' . '/qcloud_sts/qcloud-sts-sdk/src',
        ),
        'Psr\\Http\\Message\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/http-factory/src',
            1 => __DIR__ . '/..' . '/psr/http-message/src',
        ),
        'Psr\\Http\\Client\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/http-client/src',
        ),
        'OSS\\' => 
        array (
            0 => __DIR__ . '/..' . '/aliyuncs/oss-sdk-php/src/OSS',
        ),
        'MyCLabs\\Enum\\' => 
        array (
            0 => __DIR__ . '/..' . '/myclabs/php-enum/src',
        ),
        'GuzzleHttp\\UriTemplate\\' => 
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/uri-template/src',
        ),
        'GuzzleHttp\\Psr7\\' => 
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/psr7/src',
        ),
        'GuzzleHttp\\Promise\\' => 
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/promises/src',
        ),
        'GuzzleHttp\\Command\\Guzzle\\' => 
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/guzzle-services/src',
        ),
        'GuzzleHttp\\Command\\' => 
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/command/src',
        ),
        'GuzzleHttp\\' => 
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/guzzle/src',
        ),
    );

    public static $classMap = array (
        'Attribute' => __DIR__ . '/..' . '/symfony/polyfill-php80/Resources/stubs/Attribute.php',
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'PhpToken' => __DIR__ . '/..' . '/symfony/polyfill-php80/Resources/stubs/PhpToken.php',
        'Stringable' => __DIR__ . '/..' . '/symfony/polyfill-php80/Resources/stubs/Stringable.php',
        'UnhandledMatchError' => __DIR__ . '/..' . '/symfony/polyfill-php80/Resources/stubs/UnhandledMatchError.php',
        'ValueError' => __DIR__ . '/..' . '/symfony/polyfill-php80/Resources/stubs/ValueError.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitd32535a5d6635021063f9d6f72c4e4a2::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitd32535a5d6635021063f9d6f72c4e4a2::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitd32535a5d6635021063f9d6f72c4e4a2::$classMap;

        }, null, ClassLoader::class);
    }
}
