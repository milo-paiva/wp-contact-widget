<?php
/**
 * WP Contact Widget
 *
 * @link              https://github.com/milo-paiva/wp-contact-widget
 * @since             1.0.0
 * @package           WP Contact Widget
 *
 * @wordpress-plugin
 * Plugin Name:       WP Contact Widget
 * Plugin URI:        https://github.com/milo-paiva/wp-contact-widget
 * Description:       This plugin create a simple widget to show your contact information with icon and external link. 
 * Version:           1.0.0
 * Author:            Milo
 * Author URI:        https://github.com/milo-paiva
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-contact-widget
 * Domain Path:       /languages
 */

// Block direct requests
if ( !defined('ABSPATH') ) {
	exit();
}

define( 'CTWIDGET_PATH', plugin_dir_url(__FILE__) );

add_action( 'wp_enqueue_scripts', 'contact_link_styles' );
function contact_link_styles() {
	wp_enqueue_style('font-awesome', CTWIDGET_PATH . '/fonts/font-awesome.css'); 
	wp_enqueue_style('style', CTWIDGET_PATH . '/style.css'); 	
}


if( !class_exists( 'Kiwee_Contact_Info' )){
	class ContactLink_Widget extends WP_Widget {

		function __construct() {
			parent::__construct(
				'wpcontact_widget',
				__( 'WP Contact Widget', 'wp-contact-widget' ), 
				array( 'description' => __( 'Show your contact information with icon and external link', 'wp-contact-widget' ), ) // Args
			);
		}

		/**
		 * Front-end display of widget.
		 *
		 * @see WP_Widget::widget()
		 *
		 * @param array $args     Widget arguments.
		 * @param array $instance Saved values from database.
		 */
		public function widget( $args, $instance ) {
			
			$output='';
			echo $args['before_widget'];
			
			if ( !empty( $instance['title'] ) ) {
				echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
			}	

			if ( !empty( $instance['address'] ) )
			{
				$output .='<div class="ctwidget ctwidget-address">
					<i class="fa fa-map-marker"></i>
					<div class="ctwidget-contact_phone"><a href="'.$instance['addressLink'].'" target="_blank">'. esc_html_e($instance['address']) .'</a></div>
				</div>';
			}
			
			if ( !empty( $instance['phone'] ) )
			{
				$output .='<div class="ctwidget ctwidget-phone">
					<i class="fa fa-phone"></i>
					<div class="ctwidget-contact_phone"><a href="tel:055'.preg_replace('/[^0-9]/', '', $instance['phone']).'">'. esc_html__($instance['phone']) .'</a></div>
				</div>';
			}

			if ( !empty( $instance['whatsapp'] ) )
			{
				$output .='<div class="desk"><div class="ctwidget ctwidget-whats">
					<i class="fa fa-whatsapp"></i>
					<div class="ctwidget-contact_phone"><a href="https://web.whatsapp.com/send?phone=55'.preg_replace('/[^0-9]/', '', $instance['whatsapp']).'&text=Olá!%20Acessei%20o%20site%20e%20gostaria%20de%20mais%20informações%20sobre" target="_blank">'. esc_html_e($instance['whatsapp']) .'</a></div>
				</div></div>
				<div class="mob"><div class="ctwidget ctwidget-whats">
					<i class="fa fa-whatsapp"></i>
					<div class="ctwidget-contact_phone"><a href="https://api.whatsapp.com/send?phone=55'.preg_replace('/[^0-9]/', '', $instance['whatsapp']).'&text=Olá!%20Acessei%20o%20site%20e%20gostaria%20de%20mais%20informações%20sobre" target="_blank">'. esc_html_e($instance['whatsapp']) .'</a></div>
				</div></div>';
			}


			if ( !empty( $instance['contact_email'] ) )
			{
				$output .='<div class="ctwidget ctwidget-mail">
							<i class="fa fa-envelope"></i>
							<div class="ctwidget-contact_email"><a href="mailto:'.$instance['contact_email'].'">'. esc_html_e($instance['contact_email']) .'</a></div>
						</div>';	
			}	
			
			if ( !empty( $instance['open_hours'] ) )
			{
				$output .='<div class="ctwidget ctwidget-mail">
							<div class="ctwidget-open_hours"><i class="fa fa-clock-o"></i>'. esc_html_e($instance['open_hours']) .'</div>
						</div>';	
			}	
			
			echo $output;			
			
		echo $args['after_widget'];
		}

		public function form( $instance ) {
			$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Contact Information', 'wp-contact-widget' );
			$address = ! empty( $instance['address'] ) ? $instance['address'] : __( '', 'wp-contact-widget' );
			$addressLink = ! empty( $instance['addressLink'] ) ? $instance['addressLink'] : __( '', 'wp-contact-widget' );
			$phone = ! empty( $instance['phone'] ) ? $instance['phone'] : __( '', 'wp-contact-widget' );
			$whatsapp = ! empty( $instance['whatsapp'] ) ? $instance['whatsapp'] : __( '', 'wp-contact-widget' );
			$contact_email = ! empty( $instance['contact_email'] ) ? $instance['contact_email'] : __( '', 'wp-contact-widget' );
			$open_hours = ! empty( $instance['open_hours'] ) ? $instance['open_hours'] : __( '', 'wp-contact-widget' );
			?>
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title: ', 'wp-contact-widget' ); ?></label> 
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
			</p>
			<p>
				<label><?php esc_html_e( 'Address: ', 'wp-contact-widget' ); ?></label> 
				<input class="widefat" id="<?php echo $this->get_field_id( 'address' ); ?>" name="<?php echo $this->get_field_name( 'address' ); ?>" type="text" value="<?php echo esc_attr( $address ); ?>">
			</p>
			<p>
				<label><?php esc_html_e( 'Google Maps Link: ', 'wp-contact-widget' ); ?></label> 
				<input class="widefat" id="<?php echo $this->get_field_id( 'addressLink' ); ?>" name="<?php echo $this->get_field_name( 'addressLink' ); ?>" type="text" value="<?php echo esc_attr( $addressLink ); ?>">
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'phone' ); ?>"><?php esc_html_e( 'Telephone: ', 'wp-contact-widget'); ?></label> 
				<input class="widefat" id="<?php echo $this->get_field_id( 'phone' ); ?>" name="<?php echo $this->get_field_name( 'phone' ); ?>" type="text" value="<?php echo esc_attr( $phone ); ?>">
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'whatsapp' ); ?>"><?php esc_html_e( 'WhatsApp: ', 'wp-contact-widget' ); ?></label> 
				<input class="widefat" id="<?php echo $this->get_field_id( 'whatsapp' ); ?>" name="<?php echo $this->get_field_name( 'whatsapp' ); ?>" type="text" value="<?php echo esc_attr( $whatsapp ); ?>">
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'contact_email' ); ?>"><?php esc_html_e( 'Email: ', 'wp-contact-widget' ); ?></label> 
				<input class="widefat" id="<?php echo $this->get_field_id( 'contact_email' ); ?>" name="<?php echo $this->get_field_name( 'contact_email' ); ?>" type="email" value="<?php echo esc_attr( $contact_email ); ?>">
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'open_hours' ); ?>"><?php esc_html_e( 'Opening hours:  ', 'wp-contact-widget' ); ?></label> 
				<input class="widefat" id="<?php echo $this->get_field_id( 'open_hours' ); ?>" name="<?php echo $this->get_field_name( 'open_hours' ); ?>" type="text" value="<?php echo esc_attr( $open_hours ); ?>">
			</p>
			<?php 
		}

		/**
		 * Sanitize widget form values as they are saved.
		 *
		 * @see WP_Widget::update()
		 *
		 * @param array $new_instance Values just sent to be saved.
		 * @param array $old_instance Previously saved values from database.
		 *
		 * @return array Updated safe values to be saved.
		 */
		public function update( $new_instance, $old_instance ) {
			$instance = array();
			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? wp_strip_all_tags( $new_instance['title'] ) : '';
			$instance['address'] = ( ! empty( $new_instance['address'] ) ) ? wp_strip_all_tags( $new_instance['address'] ) : '';
			$instance['addressLink'] = ( ! empty( $new_instance['addressLink'] ) ) ? wp_strip_all_tags( $new_instance['addressLink'] ) : '';
			$instance['phone'] = ( ! empty( $new_instance['phone'] ) ) ? wp_strip_all_tags( $new_instance['phone'] ) : '';
			$instance['whatsapp'] = ( ! empty( $new_instance['whatsapp'] ) ) ? wp_strip_all_tags( $new_instance['whatsapp'] ) : '';
			$instance['contact_email'] = ( ! empty( $new_instance['contact_email'] ) ) ? wp_strip_all_tags( $new_instance['contact_email'] ) : '';
			$instance['open_hours'] = ( ! empty( $new_instance['open_hours'] ) ) ? wp_strip_all_tags( $new_instance['open_hours'] ) : '';

			return $instance;
		}

	} 
}	

function ctwidget_init() {
    register_widget( 'ContactLink_Widget' );
}
add_action( 'widgets_init', 'ctwidget_init' );