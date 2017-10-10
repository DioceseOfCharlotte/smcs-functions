<?php
wp_enqueue_style( 'smcs' );
$account_creater_id = sm_get_group_owner_id();
?>
<h2 class="profile-section-title u-text-center u-1of1">Parents</h2>
<div class="sm-parents sm-flex-panel">

	<div class="parent-wrap sm-card sm-shadow sm-bg-white">
		<?php echo sm_get_parent( $account_creater_id, '1' ); ?>
	</div>

	<div class="parent-wrap sm-card sm-shadow sm-bg-white">
		<?php echo sm_get_parent( $account_creater_id, '2' ); ?>
	</div>
</div>
<?php
