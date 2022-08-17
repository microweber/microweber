<?php

/**
 * Microweber Coupon Module
 * Developed by: Bozhidar Slaveykov
 *
 * @category   Modules
 * @package    CouponClass
 * @author     Bozhidar Slaveykov <selfworksbg@gmail.com>
 * @copyright  2018 Microweber
 */
class CouponClass
{

	/** @var \MicroweberPackages\App\LaravelApplication */
	public $app;

	public $table = 'cart_coupons';

	public $table_logs = 'cart_coupon_logs';

	public function __construct($app = null)
	{
		if (is_object($app)) {
			$this->app = $app;
		} else {
			$this->app = mw();
		}
	}

	public static function log($coupon_code, $customer_email) {

		$customer_ip = user_ip();

		coupon_log_customer($coupon_code, $customer_email, $customer_ip);

		coupons_delete_session();

	}

	/**
	 * Calculate new price of total
	 *
	 * @param mixed $coupon_code
	 * @param float $total_amount
	 * @param int $customer_id
	 * @return float
	 */
	public static function calculate_new_price($coupon_code, $total_amount, $customer_id)
	{
		$newPrice = 0.00;
		$coupon = coupon_get_by_code($coupon_code);
		$checkLog = coupon_log_get_by_code_and_customer_id($coupon_code, $customer_id);

		if ($checkLog['uses_count'] > $coupon['uses_per_customer']) {
			return $total_amount;
		}

		if (! empty($coupon) && $total_amount >= $coupon['total_amount']) {

			if ($coupon['discount_type'] == 'percentage' or $coupon['discount_type'] == 'percentage') {

				// Discount with percentage
				$newPrice = $total_amount - ($total_amount * ($coupon['discount_value'] / 100));
			} else if ($coupon['discount_type'] == 'fixed_amount') {

				// Discount with amount
				$newPrice = $total_amount - $coupon['discount_value'];
			}
		} else {
			return $total_amount;
		}

		return $newPrice;
	}

	public static function calculate_total_price($total_amount, $discount_value, $discount_type)
	{
		if ($discount_type == 'percentage' or $discount_type == 'percentage') {

			// Discount with percentage
			$newPrice = $total_amount - ($total_amount * ($discount_value / 100));
		} else if ($discount_type == 'fixed_amount') {

			// Discount with amount
			$newPrice = $total_amount - $discount_value;
		}

		return $newPrice;
	}
}
