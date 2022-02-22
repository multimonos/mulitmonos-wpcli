<?php

namespace Multimonos\Cli\Command;

use Multimonos\Cli\CliHelper;

class ListCommand
{
    public function run() {
        $blocks_dir = CliHelper::get_blocks_dir();

        \WP_CLI::success( "Blocks directory {$blocks_dir}" );

        $classes = glob( $blocks_dir . '/**', GLOB_ONLYDIR );

        \WP_CLI::success( "Found " . count( $classes ) . " blocks" );

        if ( count( $classes ) ) {
            array_map( function( $dir ) {
                $dirname = basename( $dir );

                \WP_CLI::line( "- $dirname" );
            }, $classes );
        }
    }

}