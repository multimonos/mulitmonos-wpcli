<?php

namespace Multimonos\Cli\Command;

use Multimonos\Cli\CliHelper;

class CreateCommand
{
    public function run( $slug ) {

        $blocks_dir = CliHelper::get_blocks_dir();

        if ( ! is_dir( $blocks_dir ) ) {
            \WP_CLI::error( "Core blocks directory missing {$blocks_dir}" );
            \WP_CLI::halt( 1 );
        }

        if ( empty( $slug ) ) {
            \WP_CLI::error( "Block slug cannot be empty" );
            \WP_CLI::halt( 1 );
        }

        $slug .= '-block';
        $classname = $this->slugToClassname( $slug );
        $classpath = $blocks_dir . DIRECTORY_SEPARATOR . $classname;

        if ( is_dir( $classpath ) ) {
            \WP_CLI::error( "Block directory already exists {$classpath}" );
            \WP_CLI::halt( 1 );
        }

        // create folder structure
        $dirs = [
            "{$classpath}",
            "{$classpath}/acf-json",
            //            "{$classpath}/js",
            //            "{$classpath}/scss",
            //            "{$classpath}/img"
        ];

        array_map( function( $path ) {
            if ( ! is_dir( $path ) ) {
                $rs = mkdir( $path );

                if ( $rs === false ) {
                    \WP_CLI::error( "Failed to create {$path}" );
                    \WP_CLI::halt( 1 );
                }
            }

            \WP_CLI::success( "Created directory " . str_replace( ABSPATH, '', $path ) );

        }, $dirs );

        // create empty files
        $files = [
            [
                'path'    => "{$classpath}/{$slug}.twig",
                'content' => "{# block : {$slug} #}"
            ],
            [
                'path'    => "{$classpath}/{$classname}.php",
                'content' => $this->replace( 'BlockClass.tpl', [
                    'classname'      => $classname,
                    'slug'           => $slug,
                    'block_name'     => $this->blockName( $slug ),
                    'block_title'    => $this->blockTitle( $slug ),
                    'block_keywords' => $this->blockKeywords( $slug ),
                ] ),
            ],
        ];

        array_map( function( $file ) {

            $rs = $this->createFile( $file['path'], $file['content'] );

            if ( ! $rs ) {
                \WP_CLI::error( "Failed to create {$file['path']}" );
                \WP_CLI::halt( 1 );
            }

            \WP_CLI::success( "Created directory " . str_replace( ABSPATH, '', $file['path'] ) );

        }, $files );

        // class preview
        if ( file_exists( "{$classpath}/{$classname}.php" ) ) {
            $preview = file_get_contents( "{$classpath}/{$classname}.php" );
            \WP_CLI::success( "Preview of {$classname}\n\n$preview ..." );
        }

        \WP_CLI::success( 'Done' );
    }

    protected function slugToClassname( $slug ): string {
        $name = trim( $slug );
        $name = preg_replace( '/-+/', ' ', $name );
        $name = ucwords( $name );
        $name = preg_replace( '/\s*/', '', $name );
        return $name;
    }

    protected function createFile( $path, $contents ) {
        if ( ! $fp = fopen( $path, 'w' ) ) {
            return false;
        }

        if ( fwrite( $fp, $contents ) === FALSE ) {
            return false;
        }

        fclose( $fp );

        return true;
    }

    protected function readTemplate( $name ): string {
        return file_get_contents( CliHelper::get_plugin_dir() . '/templates/' . $name );
    }

    protected function replace( $template_name, $params ): string {
        $str = $this->readTemplate( $template_name );

        foreach ( $params as $name => $value ) {
            $str = str_replace( '%%' . $name . '%%', $value, $str );
        }

        return $str;
    }

    protected function blockKeywords( $slug ) {
        $words = explode( '-', $slug );
        $words = array_filter( $words, fn( $word ) => $word !== 'block' );
        $str = "'" . implode( "', '", $words ) . "'";

        return $str;
    }

    protected function blockName( $slug ) {
        $name = $slug;
        $name = 'mod_' . str_replace( '-', '', $name);
        $name = str_replace( 'block', '', $name);
        return $name;
    }

    protected function blockTitle( $slug ) {
        $title=$slug;
        $title = str_replace( '-', ' ', $title );
        $title = str_replace( 'block', '', $title );
        $title = ucwords( $title );
        return $title;
    }

}