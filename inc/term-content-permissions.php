<?php

class Members_Term_Content_Permissions {

	public function __construct() {

		if ( is_admin() ) {

			add_action( 'members_cp_tax_add_form_fields',  array( $this, 'create_screen_fields'), 10, 1 );
			add_action( 'members_cp_tax_edit_form_fields', array( $this, 'edit_screen_fields' ),  10, 2 );

			add_action( 'created_members_cp_tax', array( $this, 'save_data' ), 10, 1 );
			add_action( 'edited_members_cp_tax',  array( $this, 'save_data' ), 10, 1 );

		}

	}

	public function create_screen_fields( $taxonomy ) {

		global $wp_roles;

		// Get roles and sort.
		 $_wp_roles = $wp_roles->role_names;
		asort( $_wp_roles );

		// Set default values.
		$term_roles = '';
		?>

		<div class="form-field term-term_roles-wrap">
			<h2><?php _e( 'Content Permissions', 'members-terms' ); ?></h2>
			<?php foreach ( $_wp_roles as $role => $name ) : ?>
					<label>
						<input type="checkbox" name="members_access_role[]" <?php checked( is_array( $term_roles ) && in_array( $role, $term_roles ) ); ?> value="<?php echo esc_attr( $role ); ?>" />
						<?php echo esc_html( members_translate_role( $role ) ); ?>
					</label>
			<?php endforeach; ?>
			<p class="description"><?php _e( 'Select the roles required to view content within this category. Leave unchecked for all.', 'members-terms' ); ?></p>
		</div>
		<?php

	}

	public function edit_screen_fields( $term, $taxonomy ) {

		global $wp_roles;

		// Get roles and sort.
		 $_wp_roles = $wp_roles->role_names;
		asort( $_wp_roles );

		// Set default values.
		$term_roles = get_term_meta( $term->term_id, 'members_term_role', true );

		// Set default values.
		if ( empty( $term_roles ) )
			$term_roles = ''; ?>

		<tr class="form-field term-term_roles-wrap">
			<th scope="row"><?php _e( 'Content Permissions', 'members-terms' ); ?></th>
			<td class="term-roles-datalist">
				<?php foreach ( $_wp_roles as $role => $name ) : ?>
						<label>
							<input type="checkbox" name="members_access_role[]" <?php checked( is_array( $term_roles ) && in_array( $role, $term_roles ) ); ?> value="<?php echo esc_attr( $role ); ?>" />
							<?php echo esc_html( members_translate_role( $role ) ); ?>
						</label><br>
				<?php endforeach; ?>
				<p class="description"><?php _e( 'Select the roles required to view content in this category. Leave unchecked for all.', 'members-terms' ); ?></p>
			</td>
		</tr>
		<?php

	}

	public function save_data( $term_id ) {

		// Sanitize user input.
		$new_term_roles = isset( $_POST['term_roles'] ) ? $_POST['term_roles'] : '';

		// Update the meta field in the database.
		update_term_meta( $term_id, 'term_roles', $new_term_roles );

	}

}

new Members_Term_Content_Permissions;
