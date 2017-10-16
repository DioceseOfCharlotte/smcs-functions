<?php
wp_enqueue_style( 'smcs' );
$account_creater_id = sm_get_group_owner_id();
$sm_phone           = get_user_meta( $account_creater_id, 'sm_home_phone', true );
$sm_family          = rcpga_group_accounts()->members->get_group_name( $account_creater_id );
?>

<h2 class="profile-section-title sm-narrow u-1of1"><?php echo $sm_family; ?><span> Family</span></h2>

<div class="sm-home-info sm-flex-panel sm-card">
	<div class="home-info-wrap">
		<h5>Primary Phone</h5>
		<?php echo $sm_phone; ?>
	</div>
	<div class="home-info-wrap">
		<h5>Primary Address</h5>
		<?php echo sm_get_address( $account_creater_id ); ?>
	</div>
	<div class='u-1of1'>
		<?php echo do_shortcode( '[gravityview id="1812"]' ); ?>
	</div>
	<div class='u-1of1'>
		<?php echo do_shortcode( '[gravityview id="1732"]' ); ?>
	</div>
</div>
<?php
