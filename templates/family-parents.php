<?php
wp_enqueue_style( 'smcs' );
$account_creater_id = sm_get_group_owner_id();
?>
<h2 class="profile-section-title sm-narrow u-1of1">Parents</h2>
<div class="sm-parents sm-flex-panel">

	<div class="parent-wrap sm-card sm-shadow sm-bg-white">
		<?php echo sm_get_parent( $account_creater_id, '1' ); ?>
		<?php echo do_shortcode( '[gravityview id="1730"]' ); ?>
	</div>

	<div class="parent-wrap sm-card sm-shadow sm-bg-white">
		<?php echo sm_get_parent( $account_creater_id, '2' ); ?>
		<?php has_user_account(); ?>
	</div>
</div>
<?php
