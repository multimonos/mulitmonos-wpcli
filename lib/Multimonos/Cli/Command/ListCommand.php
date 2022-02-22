<?php

namespace Multimonos\Cli\Command;

use Multimonos\Cli\CliHelper;

class ListCommand
{
    public function run() {
        $blocks_dir = CliHelper::get_blocks_dir();

        \WP_CLI::success( "Blocks directory {$blocks_dir}" );

        $paths = glob( $blocks_dir . '/**', GLOB_ONLYDIR );

        \WP_CLI::success( "Found " . count( $paths ) . " blocks" );

        if ( count( $paths ) ) {
            $max = 0;

            $max = array_reduce( $paths, function( $max, $path ) {
                $len = strlen( basename( $path ) );

                return $max > $len ? $max : $len;
            }, 0 );

            array_map( function( $path ) use ( $max ) {
                $classname = str_pad( basename( $path ), $max, ' ' );
                $path = str_replace( ABSPATH, '', $path );

                \WP_CLI::line( "- {$classname} --> {$path}" );

            }, $paths );
        }
    }

}