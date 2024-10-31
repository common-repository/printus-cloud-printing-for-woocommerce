<?php
/**
 * File responsible for commonly used util methods.
 *
 * Author:          Uriahs Victor
 * Created on:      26/03/2023 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.0.0
 * @package Helpers
 */

namespace Printus\Helpers;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use DateTimeZone;
use DateTime;
use Printus\Models\PluginSettings\LocalizationSettingsModel;

/**
 * Class responsible for defining utility methods.
 *
 * @package Printus\Helpers
 * @since 1.0.0
 */
class Utilities {

	/**
	 * Convert and width and a height into appropriate point value.
	 *
	 * @param float $width The width to convert.
	 * @param float $height The height to convert.
	 * @return array
	 * @since 1.0.0
	 */
	public static function convertMmToPt( float $width, float $height ): array {
		return array(
			'width'  => round( ( $width / 25.4 ) * 72, 2 ),
			'height' => round( ( $height / 25.4 ) * 72, 2 ),
		);
	}

	/**
	 * Whether the user is making use of custom templates.
	 *
	 * We can tell by checking if they have the printus templates folder in the theme.
	 *
	 * @return bool
	 * @since 1.0.0
	 */
	public static function usingCustomTemplates(): bool {
		return defined( 'PRINTUS_CUSTOM_TEMPLATES_PATH' );
	}

	/**
	 * Days of the week.
	 *
	 * @return array
	 * @since 1.1.0
	 */
	public static function getDaysOfWeek(): array {
		return array(
			'monday'    => __( 'Monday' ),
			'tuesday'   => __( 'Tuesday' ),
			'wednesday' => __( 'Wednesday' ),
			'thursday'  => __( 'Thursday' ),
			'friday'    => __( 'Friday' ),
			'saturday'  => __( 'Saturday' ),
			'sunday'    => __( 'Sunday' ),
		);
	}

	/**
	 * Get the current time zone for the site. Filterable if user wants to set a custom timezone.
	 *
	 * @return DateTimeZone
	 * @since 1.1.0
	 */
	public static function getTimezone(): DateTimeZone {
		return apply_filters( 'printus_timezone', wp_timezone() );
	}

	/**
	 * Get the current day.
	 *
	 * @return string
	 * @since 1.1.0
	 */
	public static function getCurrentDay(): string {
		$timezone = apply_filters( 'printus_timezone', wp_timezone() );
		return date_create( 'now', $timezone )->format( 'l' );
	}

	/**
	 * Get the current time based on the time format selected by the user.
	 *
	 * @return string
	 * @since 1.1.0
	 */
	public static function getCurrentTime(): string {
		$timezone    = self::getTimezone();
		$time_format = LocalizationSettingsModel::getPreferredTimeFormat();
		$time_format = ( 'H' === $time_format[0] ) ? 'H:i' : 'h:i';
		return date_create( 'now', $timezone )->format( $time_format );
	}

	/**
	 * Get the current time as 24 hour time format.
	 *
	 * @param string $time_12hr
	 * @param mixed  $timezone
	 * @return string
	 * @since 1.1.0
	 */
	public static function getCurrentTime24hr(): string {
		return date_create( 'now', self::getTimezone() )->format( 'H:i' );
	}

	/**
	 * Convert a time to 24 hour time format.
	 *
	 * @param string       $time
	 * @param DateTimeZone $timezone
	 * @return DateTime|string
	 * @since 1.1.0
	 */
	public static function convertTimeTo24hr( string $time ): string {
		$converted = date_create( $time, self::getTimezone() )->format( 'H:i' );
		if ( empty( $converted ) ) {
			return '';
		}
		return $converted;
	}

	/**
	 * Check if a time is between two times.
	 *
	 * @param string $from
	 * @param string $to
	 * @param string $input
	 * @return bool
	 * @since 1.1.0
	 * @link https://stackoverflow.com/a/27134087/4484799
	 */
	public static function timeIsBetween( string $from, string $to, string $input ): bool {
		$from  = DateTime::createFromFormat( '!H:i', $from );
		$to    = DateTime::createFromFormat( '!H:i', $to );
		$input = DateTime::createFromFormat( '!H:i', $input );

		if ( $from > $to ) {
			$to->modify( '+1 day' );
		}

		return ( $from <= $input && $input <= $to ) || ( $from <= $input->modify( '+1 day' ) && $input <= $to );
	}
}
