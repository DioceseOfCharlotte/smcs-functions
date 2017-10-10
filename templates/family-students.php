<?php
wp_enqueue_style( 'smcs' );
$account_creater_id = sm_get_group_owner_id();
$students = sm_get_students( $account_creater_id );

if ( empty( $students ) ) {
	return;
}
?>

<h2 class="profile-section-title sm-narrow u-1of1">Students</h2>
<div class='sm-students sm-flex-panel'>

<?php
foreach ( $students as $student ) {
	if ( ! empty( ltrim( $student['name'] ) ) ) {
	?>
		<div class='sm-student sm-card sm-shadow sm-bg-white u-text-center'>
			<div class='s-name u-h4'>
				<?php echo $student['name']; ?>
			</div>
			<div class='s-grade'><h6 class="sm_card-subtitle">Grade</h6>
				<?php echo $student['grade']; ?>
			</div>
		</div>
		<?php
	}
}
?>
	<div class='u-1of1'>
		<?php echo do_shortcode( '[gravityview id="1731"]' ); ?>
	</div>
</div>
<?php
