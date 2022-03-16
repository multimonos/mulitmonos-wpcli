<?php


namespace Multimonos\Cli;


use Multimonos\Cli\Command\CreateCommand;
use Multimonos\Cli\Command\ListCommand;
use Multimonos\Cli\Command\SyncAcfJsonToBlockFoldersCommand;


class BlockCommands
{
    /**
     * Copies the existing acf-json files for each block from theme to block class folder
     *
     * @param $_
     * @param $opts
     *
     * @when after_wp_load
     * @subcommand acf
     */
    public function acf( $_, $opts ) {
        $cmd = new SyncAcfJsonToBlockFoldersCommand();
        $baseclass = $_[0];
        $cmd->run( $baseclass );
    }

    /**
     * Lists existing blocks
     *
     * @param $_
     * @param $opts
     *
     * @when before_wp_load
     * @subcommand list
     */
    public function list( $_, $opts ) {
        $cmd = new ListCommand();
        $cmd->run();
    }

    /**
     * Creates a block
     *
     * ## OPTIONS
     *
     * <slug>
     * : Id for the block - if slug is slug-example then classname will be SlugExampleBlock )
     *
     * [--post-types=<types>...]
     * : csv of types that are granted usage for block
     * ---
     * default: page
     * options:
     *   - page
     *   - post
     * ---
     *
     * [--category=<category>]
     * : category for the block
     * ---
     * default: formatting
     * ---
     * @param $_
     * @param $opts
     *
     * @when before_wp_load
     * @subcommand create
     */
    public function create( $_, $opts ) {
        $slug = $_[0];
        $cmd = new CreateCommand();
        $cmd->run( $slug, $opts );
    }
}