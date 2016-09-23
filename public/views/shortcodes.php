<?php
/**
 * Community Commons NYSCAA
 *
 * @package   Community_Commons_NYSCAA
 * @author    Yan Barnett
 * @license   GPL-2.0+
 * @link      http://www.communitycommons.org
 * @copyright 2016 Community Commons
 */

 /**
 * Output html for the Poverty Report.
 *
 * @since   1.0.0
 *
 * @return  html
 */
function nyscaa_poverty_report_shortcode() {
    ob_start();
    nyscaa_poverty_report();
    return ob_get_clean();
}
add_shortcode( 'nyscaa_poverty_report', 'nyscaa_poverty_report_shortcode' );