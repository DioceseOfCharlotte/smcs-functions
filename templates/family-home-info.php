<?php
wp_enqueue_style( 'smcs' );
$account_creater_id = sm_get_group_owner_id();
$sm_phone = get_user_meta( $account_creater_id, 'sm_home_phone', true );
$sm_family = rcpga_group_accounts()->members->get_group_name( $account_creater_id );
?>

<h1 class="profile-section-title u-text-center u-1of1"><?php echo $sm_family; ?></h1>

<div class="sm-home-info sm-flex-panel sm-card">
	<div class="home-info-wrap">
		<h5>Primary Phone</h5>
		<?php echo $sm_phone; ?>
	</div>
	<div class="home-info-wrap">
		<h5>Primary Address</h5>
		<?php echo sm_get_address( $account_creater_id ); ?>
	</div>
</div>
<?php
