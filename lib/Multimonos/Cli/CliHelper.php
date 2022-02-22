<?php

namespace Multimonos\Cli;

class CliHelper
{
    public static function get_composer_json() {
        $path = get_template_directory() . '/composer.json';
        if ( file_exists( $path ) ) {
            return json_decode( file_get_contents( $path ), true );
        }
        return false;
    }

    public static function get_blocks_dir() {
        $path = get_template_directory();

        $dir = new \RecursiveDirectoryIterator( $path );
        $iter = new \RecursiveIteratorIterator( $dir );
        $files = new \RegexIterator( $iter, '/(lib|inc)\/.+\/Blocks\/BlockRegistrar.php$/', \RegexIterator::GET_MATCH );

        foreach ( $files as $file ) {
            return dirname( $path . DIRECTORY_SEPARATOR . $file[0]);
        }

        return false;
    }

    public static function get_plugin_dir() {
        return dirname( dirname( dirname( plugin_dir_path( __FILE__ ) ) ) );
    }

    public static function get_templates_dir() {
        return self::get_plugin_dir().'/templates';
    }

}