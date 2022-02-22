<?php


namespace Multimonos\Cli;


use Multimonos\Cli\Command\CreateCommand;
use Multimonos\Cli\Command\ListCommand;
use Multimonos\Cli\Command\SyncAcfJsonToBlockFoldersCommand;


class BlockCommands
{
    /**
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
     * @param $_
     * @param $opts
     *
     * @when before_wp_load
     * @subcommand create
     */
    public function create( $_, $opts ) {
        $slug = $_[0];
        $cmd = new CreateCommand( );
        $cmd->run($slug);
    }
}