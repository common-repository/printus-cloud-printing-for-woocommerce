<?php
/**
 * File responsible for setting structure for settings classes.
 *
 * Author:          Uriahs Victor
 * Created on:      13/03/2023 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.0.0
 * @package Views
 */

namespace Printus\Views\Admin\PluginSettings;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Abstract Settings class.
 *
 * @package Printus\Views\Admin\PluginSettings
 * @since 1.0.0
 */
abstract class AbstractSettings {

	/**
	 * Settings tab.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	abstract public function createTab(): array;

	/**
	 * Settings sections.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	abstract public function createSections(): array;
}
