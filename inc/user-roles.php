<?php
/**
 * Roles for use with Members content permissions.
 *
 * @package  SMCS
 */

function smcs_roles_on_plugin_activation() {

	add_role( 'sm_family', 'SM Family',
		array(
			'read' => true,
			'gravityview_view_entries' => true,
			'gravityview_view_others_entries' => true,
		)
	);

	add_role( 'sm_faculty', 'SM Faculty',
		array(
			'read' => true,
			'gravityview_view_entries' => true,
			'gravityview_view_others_entries' => true,
		)
	);

	add_role( 'sm_pto_admin', 'SM PTO Admin',
		array(
			'read' => true,
			'gravityview_view_entries' => true,
			'gravityview_view_others_entries' => true,
		)
	);

	add_role( 'sm_sports_admin', 'SM Sports Admin',
		array(
			'read' => true,
			'gravityview_edit_others_entries' => true,
			'gravityview_moderate_entries' => true,
			'gravityview_delete_others_entries' => true,
			'gravityview_view_entry_notes' => true,
			'gravityview_edit_entries' => true,
			'gravityview_edit_entry' => true,
			'gravityview_edit_form_entries' => true,
			'gravityview_delete_entries' => true,
			'gravityview_delete_entry' => true,
			'gravityview_view_entries' => true,
			'gravityview_view_others_entries' => true,
			'gravityview_edit_others_entry_notes' => true,
			'gravityview_view_others_entry_notes' => true,
		)
	);
}
