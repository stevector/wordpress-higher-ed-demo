<?php
/**
 * Plugin Name:     Customize Hero
 * Plugin URI:      https://github.com/ataylorme/customize-hero
 * Description:     Allows WordPress sites to add a hero image or carousel via the customizer.
 * Author:          Andrew Taylor, Weston Ruter, Daniel Bachhuber
 * Text Domain:     customize-hero
 * Domain Path:     /languages
 * Version:         0.1.0
 * License: GPL3+
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package         Customize_Hero
 *
 * Copyright (c) 2016 Andrew Taylor (https://www.ataylor.me/)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 3 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 */

/**
 * Define plugin paths
 */
define( 'CUSTOMIZE_HERO_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'CUSTOMIZE_HERO_URL', untrailingslashit( plugin_dir_url( __FILE__ ) ) );

/**
 * Fetch autoloader
 */
require_once CUSTOMIZE_HERO_PATH . '/vendor/autoload.php';

/**
 * Initialize classes
 */
CustomizeHero::init();
CustomizeHero_Options::init();

/**
 * Displays the Customize Hero banner for arbitrary use, such as in templates.
 *
 * @return string
 */
function get_customize_hero() {
	$customize_hero = CustomizeHero::init();

	return $customize_hero->hero_html();
}
