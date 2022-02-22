<?php


namespace Multimonos\Cli\Command;


class SyncAcfJsonToBlockFoldersCommand
{
    public function run($baseclass) {
        $json_dir = get_template_directory() . '/acf-json';

        $this->assert_baseclass_declared( $baseclass );
        $this->assert_is_dir( $json_dir );

        $block_manifest = $this->create_block_manifest( $baseclass );
//        print_r( $block_manifest );

        $acf_manifest = $this->create_acf_json_manifest( $json_dir );
//        print_r( $acf_manifest );


        $manifest = $this->merge( $acf_manifest, $block_manifest );
//        print_r( $manifest );

        $this->sync( $manifest );
    }

    protected function create_block_manifest( $baseclass ): array {

        $classpaths = $this->subclasses_of( $baseclass );

        return array_map( function( $classpath ) {
            $block = new $classpath;
            $acf = $block->get_acf_block_config();

            return [
                'acfid'   => $this->to_acfid( $acf['name'] ),
                'dirpath' => $this->class_dirpath( $block ),
                'class'   => $classpath,
            ];

        }, $classpaths );

    }

    protected function create_acf_json_manifest( $json_dir ): array {

        $paths = glob( $json_dir . '/*.json' );

        return array_map( function( $path ) {

            $a = [
                'path' => $path,
                'ids'  => $this->get_ids_from_acf_json( $path ),
            ];

            return $a;

        }, $paths );

    }

    protected function assert_baseclass_declared( $baseclass ) {
        if ( ! in_array( $baseclass, get_declared_classes() ) ) {
            \WP_CLI::error( "Class not found {$baseclass}" );
        }
    }

    protected function assert_is_dir( $dir ) {
        if ( ! is_dir( $dir ) ) {
            \WP_CLI::error( "Directory not found {$dir}" );
        }
    }

    protected function subclasses_of( $baseclass ): array {
        $l = get_declared_classes();
        $l = array_filter( $l, fn( $x ) => is_subclass_of( $x, $baseclass ) !== false );
        return $l;
    }

    protected function to_acfid( $name ): string {
        return empty( $name ) ? '' : 'acf/' . preg_replace( '/[_]/', '-', $name );
    }

    protected function class_dirpath( $obj ) {
        $r = new \ReflectionClass( $obj );
        return dirname( realpath( $r->getFileName() ) );
    }

    protected function get_ids_from_acf_json( $path ): array {

        $json = json_decode( file_get_contents( $path ), true );

        $location = isset( $json['location'] ) && is_array( $json['location'] )
            ? $json['location']
            : false;

        if ( $location === false ) {
            return [];
        }

        $ids = [];

        foreach ( $location as $condtions ) {
            foreach ( $condtions as $cond ) {
                if ( 'block' === $cond['param'] ) {
                    $ids[] = $cond['value'];
                }
            }
        }

        return $ids;

    }

    protected function merge( array $acf_manifest, array $block_manifest ): array {

        return array_map( function( $block ) use ( $acf_manifest ) {

            $found = array_filter( $acf_manifest, fn( $acf ) => in_array( $block['acfid'], $acf['ids'] ) );

            $block['json_paths'] = array_column( $found, 'path' );

            return $block;

        }, $block_manifest );

    }

    protected function manifest_filecount( array $manifest ): int {
        $items = array_column( $manifest, 'json_paths' );
        $cnt = array_reduce( $items, function( $acc, $cur ) {
            $acc = (int)$acc + count( $cur );
            return $acc;
        }, 1 );
        return $cnt;
    }

    protected function sync( array $manifest ): void {

        $total = $this->manifest_filecount( $manifest );
        $prefix = 'acf-json_';
        $progress = \WP_CLI\Utils\make_progress_bar( 'Copying acf json files ...', $total );
//        $classname = \WP_CLI::colorize( "%y{$block['class']}" );

//        echo "\n";
//        $cnt = $this->manifest_filecount( $manifest );
//        echo "\ncount:" . $cnt;
//        exit;
        array_map( function( $block ) use ( $progress, $prefix ) {

//            \WP_CLI::log( $classname );


            $dst_dir = join( DIRECTORY_SEPARATOR, [$block['dirpath'], 'acf-json'] );
            \WP_CLI::debug( "destination folder : {$dst_dir}" );

            // dst dir exists
            if ( ! is_dir( $dst_dir ) ) {
                if ( ! mkdir( $dst_dir, 0755 ) ) {
                    \WP_CLI::error( "failed to create folder {$dst_dir}" );
                } else {
                    \WP_CLI::debug( "created folder {$dst_dir}" );
                }
            }

            // delete existing acf json files
            $existing = glob( $dst_dir . "/{$prefix}*.json" );
            $cnt = count( $existing );

            if ( $cnt > 0 ) {
                \WP_CLI::debug( "found {$cnt} existing acf json files" );

                array_map( function( $file ) {
                    if ( ! unlink( $file ) ) {
                        \WP_CLI::warning( "failed to delete existing json {$file}" );
                    } else {
                        \WP_CLI::debug( "deleted {$file}" );
                    }
                }, $existing );
            }

            // copy
            $i = 0;
            foreach ( $block['json_paths'] as $src ) {
                $dst = join( DIRECTORY_SEPARATOR, [$dst_dir, "{$prefix}{$i}.json"] );
                if ( ! copy( $src, $dst ) ) {
                    \WP_CLI::warning( "failed to copy {$src} -> {$dst}" );
                } else {
                    \WP_CLI::debug( "copied  ${src} -> {$dst}" );
                }
                $i ++;
            }

            $progress->tick();

        }, $manifest );

        $progress->finish();

        \WP_CLI::success( "Complete" );

    }
}