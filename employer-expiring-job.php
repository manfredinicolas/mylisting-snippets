<?php
/**
 * Email content when notifying employers of an expiring job listing.
 *
 * This template can be overridden by copying it to yourtheme/job_manager/emails/employer-expiring-job.php.
 *
 * @see         https://wpjobmanager.com/document/template-overrides/
 * @author      Automattic
 * @package     WP Job Manager
 * @category    Template
 * @version     1.31.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * @var WP_Post $job
 */
$job = $args['job'];

/**
 * @var bool
 */
$expiring_today = $args['expiring_today'];

echo '<p>';
if ( $expiring_today ) {
	echo wp_kses_post(
		sprintf(
			__( 'The following job listing is expiring today from <a href="%s">%s</a>.', 'wp-job-manager' ),
			home_url(),
			get_bloginfo( 'name' )
		)
	);
} else {
	echo wp_kses_post(
		sprintf(
			__( 'The following job listing is expiring soon from <a href="%s">%s</a>.', 'wp-job-manager' ),
			home_url(),
			get_bloginfo( 'name' )
		)
	);
}
echo wp_kses_post(
	sprintf(
		' ' . __( 'Visit the <a href="%s">job listing dashboard</a> to manage the listing.', 'wp-job-manager' ),
		esc_url( wc_get_account_endpoint_url('my-listing') )
	)
);
echo '</p>';

/**
 * Show details about the job listing.
 *
 * @param WP_Post              $job            The job listing to show details for.
 * @param WP_Job_Manager_Email $email          Email object for the notification.
 * @param bool                 $sent_to_admin  True if this is being sent to an administrator.
 * @param bool                 $plain_text     True if the email is being sent as plain text.
 */
do_action( 'job_manager_email_job_details', $job, $email, false, $plain_text );
