<?php
/**
 * Utility functions for the plugin.
 *
 * Community Commons NYSCAA
 *
 * @package   Community_Commons_NYSCAA
 * @author    Yan Barnett
 * @license   GPL-2.0+
 * @link      http://www.communitycommons.org
 * @copyright 2016 Community Commons
 */

/**
 * Fetch the group id of the NYSCAA group for the current environment.
 *
 * @since   1.0.0
 *
 * @return  int The group ID
 */
function nyscaa_get_group_id(){
	$location = get_site_url( null, '', 'http' );
	switch ( $location ) {
		case 'http://commonsdev.local':
			$group_id = 542;
			break;
		case 'http://dev.communitycommons.org':
			$group_id = 0;
			break;
		case 'http://staging.communitycommons.org':
			$group_id = 593;
			break;
		case 'http://www.communitycommons.org':
		case 'http://abydos.cares.missouri.edu':
			$group_id = 696;
			break;
		default:
			$group_id = 0;
			break;
	}
	return apply_filters( 'nyscaa_get_group_id', $group_id );
}

/**
 * Is this the group?.
 *
 * @since   1.0.0
 *
 * @param   int $group_id Optional. Group ID to check.
 *          Defaults to current group.
 * @return  bool
 */
function nyscaa_is_nyscaa_group( $group_id = 0 ) {
	if ( empty( $group_id ) ){
		$group_id = bp_get_current_group_id();
	}
	$setting = ( nyscaa_get_group_id() == $group_id ) ? true : false;

	return apply_filters( 'nyscaa_is_nyscaa_group', $setting );
}

/**
 * Get base url for the group.
 *
 * @since   1.0.0
 *
 * @return  string url
 */
function nyscaa_get_group_permalink() {
	$group_id = nyscaa_get_group_id();
	$permalink = bp_get_group_permalink( groups_get_group( array( 'group_id' => $group_id ) ) );

	return apply_filters( 'nyscaa_get_group_permalink', $permalink, $group_id);
}

/**
 * Is the current or specified user a member of the hub?
 *
 * @since   1.0.0
 * @param   int $user_id
 *
 * @return  bool
 */
function nyscaa_is_current_user_a_member( $user_id = 0 ) {
	$is_member = false;

	if ( empty( $user_id )  ) {
		$user_id = get_current_user_id();
	}

	if ( $user_id ) {
		$is_member = (bool) groups_is_user_member( $user_id, nyscaa_get_group_id() );
	}

	return $is_member;
}