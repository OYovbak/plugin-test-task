<?php
/**
 * Plugin Name: Custom plugin
 * Description: Custom plugin for adding widget
 * Author:      author
 */

require_once dirname( __FILE__ ) . '/class-widget-test-task.php';

function load_test_widget() {
	register_widget( 'Test_Task_Widget' );
}
add_action( 'widgets_init', 'load_test_widget' );


function remove_test_widget(){
	unregister_widget('Test_Task_Widget');
}
register_deactivation_hook( __FILE__, 'remove_test_widget' );