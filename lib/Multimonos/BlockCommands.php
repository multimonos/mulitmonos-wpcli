<?php


namespace Multimonos;


use Multimonos\Commands\SyncAcfJsonToBlockFolders;

class BlockCommands
{
    /**
     * @param $_
     * @param $opts
     *
     * @when after_wp_load
     * @subcommand sync-acf
     */
    public function sync_acf( $_, $opts) {
        new SyncAcfJsonToBlockFolders($_[0]);
    }
}