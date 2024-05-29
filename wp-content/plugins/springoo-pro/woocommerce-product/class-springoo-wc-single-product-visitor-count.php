<?php

class Springoo_WC_Single_Product_Visitor_Count {
	/**
	 * Springoo Single Product Constructor
	 */
	public function __construct() {
		add_action( 'woocommerce_single_product_summary', [ $this, 'springoo_visitor_count' ] , -1 );
	}

	/**
	 * single product visitor Count
	 *
	 *
	 * @return mixed
	 */
	public function springoo_visitor_count() {
		global  $product;

		$base      = "base_count.dat";
		$last_time = time() - 300;
		touch( $base );
		$file = file( $base );

		$pID    = $product->get_id();// Getting current product ID
		$ip     = $this->springoo_getRealIpAddr(); // Getting the user's computer IP
		$date   = date( "Y-m-d" ); // Getting the current date
		$time   = time();
		$res_file = [];

		if ( empty( $file ) ) {
			$res_file[] = trim( $pID ) . '|' . trim( $ip ) . '|' . trim( $date ) . '|' . $time . PHP_EOL;
		} else {
			foreach ( $file as $line ) {
				list( $p, $i, $d, $t ) = explode( '|', $line );

				if (  $p == $pID &&  $i == $ip  ) {
					return 0;
				} else {
					$res_file[] = trim( $p ) . '|' . trim( $i ) . '|' . trim( $d ) . '|' . $t ;
				}
			}
			$res_file[] = trim( $pID ) . '|' . trim( $ip ) . '|' . trim( $date ) . '|' . $time . PHP_EOL;
		}

		file_put_contents( $base, $res_file, LOCK_EX );

	}

	/**
	 * get Real IP Address
	 *
	 *
	 * @return mixed
	 */
	protected function springoo_getRealIpAddr() {

		if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} else if ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}

		$ip = trim( $ip ); // just to be safe
		if ( filter_var( $ip, FILTER_VALIDATE_IP ) ) {
			return $ip;
		}
	}

}

new Springoo_WC_Single_Product_Visitor_Count;
