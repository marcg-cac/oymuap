<?php

namespace Oym\Uap\Includes;
class Capabilities {

	/**
	 * Init class.
	 *
	 * @since 1.5.8
	 */
	public function __construct() {
		$this->hooks();
	}

	/**
	 * Capabilities hooks.
	 *
	 * @since 1.5.8
	 */
	public function hooks() {
		add_action( 'admin_init', [ $this, 'get_caps' ], 10, 4 );
        add_action( 'admin_init', [ $this, 'grant_capabilities_to_admin' ], 10, 4 );
	}

	/**
	 * Get a list of all capabilities.
	 *
	 * @since 1.5.8
	 *
	 * @return array
	 */
	public function get_caps() {

		$capabilities = [
			'oymuap_access'                => __( 'Access Application', 'oymuap' ),
			'oymuap_view_settings'         => __( 'View Settings', 'oymuap' ),
			'oymuap_edit_settings'         => __( 'Edit Settings', 'oymuap' ),
			'oymuap_edit_debug'				=> __( 'Edit Debug', 'oymuap' ),
		];

		return \apply_filters( 'oymuap_access_capabilities_get_caps', $capabilities );
	}

    public function grant_capabilities_to_admin() {
        $all_roles = \get_editable_roles();

		foreach ($all_roles as $role_name => $role_info){
            if (array_key_exists("manage_options", $role_info['capabilities'])){
                $role = get_role( $role_name );  
				/*
				$role->remove_cap('oymuap_access');
				$role->remove_cap('oymuap_view_settings');
				$role->remove_cap('oymuap_edit_settings');
				$role->remove_cap('oymuap_edit_debug');
				*/
				if ( $role && ! $role->has_cap( 'oymuap_access' ) ) {
					$role->add_cap( 'oymuap_access' );
				}
				if ( $role && ! $role->has_cap( 'oymuap_view_settings' ) ) {
					$role->add_cap( 'oymuap_view_settings' );
				}
				if ( $role && ! $role->has_cap( 'oymuap_edit_settings' ) ) {
					$role->add_cap( 'oymuap_edit_settings' );
				}
				if ( $role && ! $role->has_cap( 'oymuap_edit_debug' ) ) {
					$role->add_cap( 'oymuap_edit_debug' );
				}
            }
        }
    }


}
$capabilities = new Capabilities();