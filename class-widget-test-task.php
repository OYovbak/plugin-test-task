<?php 
class Test_Task_Widget extends WP_Widget {

	function __construct(){

		parent::__construct(
			'test_widget',
			'Test Task Windget',
			array('description' => 'Widget for test task')
		);
	}

	function widget($args, $instance){
		global $wpdb;

		echo $args['before_widget'];

		if($instance['zero_comments'] == 1){
			$find = "SELECT wp_users.display_name as user_name, COUNT(wp_comments.comment_author) as count 
		FROM wp_users LEFT JOIN wp_comments ON  wp_users.display_name = wp_comments.comment_author GROUP BY wp_comments.comment_author, wp_users.display_name ORDER BY count DESC";
		}
		else {
			$find = "SELECT wp_users.display_name as user_name, COUNT(wp_comments.comment_author) as count 
		FROM wp_users RIGHT JOIN wp_comments ON  wp_users.display_name = wp_comments.comment_author GROUP BY wp_comments.comment_author, wp_users.display_name ORDER BY count DESC";
		}

		if($instance['num_of_users'] > 0) $find .= " LIMIT ".$instance['num_of_users'];

		$results = $wpdb->get_results($find);

		if($instance['title']) echo $instance['title'];

		$result_template = "<ul>";

		if($instance['num_of_comments'] == 1){
			foreach ($results as $result) {
				if($result->user_name) {
					$result_template .= "<li>".$result->user_name."(".$result->count.")</li>";
				}
			}
		}
		else{
			foreach ($results as $result) {
				if($result->user_name) $result_template .= "<li>".$result->user_name."</li>";
			}
		}
		$result_template .= "</ul>";
		echo $result_template;

		echo $args['after_widget'];

	}

	function form($instance){

		$title = ! empty($instance['title']) ? $instance['title'] : '';
		$num_of_users = ! empty($instance['num_of_users']) ? $instance['num_of_users'] : 5;
		$zero_comments = ! empty($instance['zero_comments']) ? $instance['zero_comments'] : 0;
		$num_of_comments = ! empty($instance['num_of_comments']) ? $instance['num_of_comments'] : 0;
		?>
		<p>
			<lable for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></lable>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $title ); ?>" />
		</p>

		<p>
			<lable for="<?php echo $this->get_field_id( 'num_of_users' ); ?>"><?php _e( 'Enter the number of users:' ); ?></lable>
			<input type="number" step="1" min="1" step="1" class="widefat" id="<?php echo $this->get_field_id( 'num_of_users' ); ?>" name="<?php echo $this->get_field_name( 'num_of_users' ); ?>" value="<?php echo esc_attr( $num_of_users ); ?>" />
		</p>

		<p>
			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'zero_comments' ); ?>" name="<?php echo $this->get_field_name( 'zero_comments' ); ?>"  value="1" <?php checked( $zero_comments, 1 ); ?> /> 
			<lable for="<?php echo $this->get_field_id( 'zero_comments' ); ?>"><?php _e( 'Show users without comments' ); ?></lable>
		</p>

		<p>
			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'num_of_comments' ); ?>" name="<?php echo $this->get_field_name( 'num_of_comments' ); ?>"  value="1" <?php checked( $num_of_comments, 1 ); ?> /> 
			<lable for="<?php echo $this->get_field_id( 'num_of_comments' ); ?>"><?php _e( 'Show number of comments' ); ?></lable>
		</p>
		<?php
	}

	function update($new_instance, $old_instance){

		$instance = $old_instance;
		$instance['title'] = ! empty($new_instance['title']) ? $new_instance['title'] : '';

		$instance['num_of_users'] = (absint($new_instance['num_of_users']) && absint($new_instance['num_of_users'])>0) ? absint($new_instance['num_of_users']) : 5;

		$instance['zero_comments'] = empty( $new_instance['zero_comments'] ) ? 0 : 1;
		$instance['num_of_comments'] = empty( $new_instance['num_of_comments'] ) ? 0 : 1;

		return $instance;
	}


}