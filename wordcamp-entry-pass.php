<?php

/**
 * Plugin Name: WordCamp Entry Pass
 * Plugin URI: https://angeladdons.com
 * Description: WordCamp Entry pass for Food and Others
 * Version: 1.0.0
 * Author: WordCamp
 * Author URI: https://sylhet.wordcamp.org/2023/
 * License:  GPL-3.0
 * License URI:  http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: wordcamp-entry-pass
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

require_once __DIR__.'/vendor/autoload.php';

use WordCamp\Bootstrap\Bootstrap;

/**
 * Run Application
 */
Bootstrap::run(__FILE__);