<?php
/**
 * Plugin Name:     multimonos-wpcli
 * Plugin URI:      PLUGIN SITE HERE
 * Description:     Adds useful wp-cli commands for building wordpress sites.
 * Author:          Craig Hopgood <searaig@gmail.com>
 * Author URI:      YOUR SITE HERE
 * Text Domain:     multimonos-wpcli
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Multimonos_Wpcli
 * @see https://make.wordpress.org/cli/handbook/references/internal-api/
 */


if ( defined( 'WP_CLI' ) && WP_CLI ) {
    require_once __DIR__ . '/vendor/autoload.php';

    \WP_CLI::add_command( 'block', Multimonos\Cli\BlockCommands::class );
}