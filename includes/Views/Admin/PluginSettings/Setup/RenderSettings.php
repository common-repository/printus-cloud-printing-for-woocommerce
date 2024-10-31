<?php

/**
 * File responsible for bringing together all our plugin's settings for render.
 *
 * Author:          Uriahs Victor
 * Created on:      07/02/2023 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.0.0
 * @package Views
 */
namespace Printus\Views\Admin\PluginSettings\Setup;

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
use Printus\Views\Admin\PluginSettings\APISettings;
use Printus\Views\Admin\PluginSettings\GeneralSettings;
use Printus\Views\Admin\PluginSettings\LocalizationSettings;
use Printus\Views\Admin\PluginSettings\ToolsSettings;
use Printus\Views\Admin\PluginSettings\TemplateSettings;
/**
 * Class responsible for bringing all settings together for render.
 *
 * @package Printus\Views\Admin\PluginSettings\Setup
 * @since 1.0.0
 */
class RenderSettings {
    /**
     * Property to hold API Settings instance.
     *
     * @var APISettings
     */
    private $api_settings;

    /**
     * Property to hold General Settings instance.
     *
     * @var GeneralSettings
     */
    private $general_settings;

    /**
     * Property to hold General Settings instance.
     *
     * @var TemplateSettings
     */
    private $template_settings;

    /**
     * Property to hold General Settings instance.
     *
     * @var LocalizationSettings
     */
    private $localization_settings;

    /**
     * Property to hold General Settings instance.
     *
     * @var ToolsSettings
     */
    private $tools_settings;

    /**
     * Class constructor.
     *
     * @return void
     */
    public function __construct() {
        $this->api_settings = new APISettings();
        $this->general_settings = new GeneralSettings();
        $this->template_settings = new TemplateSettings();
        $this->localization_settings = new LocalizationSettings();
        $this->tools_settings = new ToolsSettings();
    }

    /**
     * Render our plugin's settings.
     *
     * @return array
     * @since 1.0.0
     */
    public function render_settings() {
        $settings['tabs'] = $this->get_tabs();
        $settings['sections'] = $this->get_sections();
        return $settings;
    }

    /**
     * Get our different settings tabs.
     *
     * @return array
     * @since 1.0.0
     */
    private function get_tabs() {
        $tabs = array();
        $tabs[] = $this->api_settings->createTab();
        $tabs[] = $this->general_settings->createTab();
        $tabs[] = $this->template_settings->createTab();
        $tabs[] = $this->localization_settings->createTab();
        $tabs[] = $this->tools_settings->createTab();
        return $tabs;
    }

    /**
     * Get our different sections.
     *
     * @return array
     * @since 1.0.0
     */
    private function get_sections() {
        $sections = array_merge(
            $this->api_settings->createSections(),
            $this->general_settings->createSections(),
            $this->template_settings->createSections(),
            $this->localization_settings->createSections(),
            $this->tools_settings->createSections()
        );
        return $sections;
    }

}
