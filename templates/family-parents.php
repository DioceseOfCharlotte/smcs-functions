<?php
wp_enqueue_style( 'smcs' );
$account_creater_id = sm_get_group_owner_id();
$parent_2_email     = get_user_meta( $account_creater_id, 'sm_parent_2_email', true );
$member_user        = get_user_by( 'email', $parent_2_email );
?>
<h2 class="profile-section-title sm-narrow u-1of1">Parents</h2>
<div class="sm-parents sm-flex-panel">

	<div class="parent-wrap sm-card sm-shadow sm-bg-white">
		<?php echo sm_get_parent( $account_creater_id, '1' ); ?>
	</div>

	<div class="parent-wrap sm-card sm-shadow sm-bg-white">
		<?php echo sm_get_parent( $account_creater_id, '2' ); ?>
		<?php
		if ( ! $member_user ) {
				echo '<a class="edit-btn" href="' . home_url( 'accounts/add-group-member/' ) . '">Add this person to your family group.</a>';
		}
		?>
	</div>
	<div class='u-1of1'>
		<?php echo do_shortcode( '[gravityview id="1730"]' ); ?>
	</div>
</div>
<?php
