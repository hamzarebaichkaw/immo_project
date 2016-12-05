<?php
namespace BooklyLite\Backend;

use BooklyLite\Backend\Modules;
use BooklyLite\Frontend;
use BooklyLite\Lib;

/**
 * Class Backend
 * @package BooklyLite\Backend
 */
class Backend
{
    public function __construct()
    {
        // Backend controllers.
 /*       $this->apearanceController     = Modules\Appearance\Controller::getInstance();
        $this->appointmentsController  = Modules\Appointments\Controller::getInstance();
        $this->calendarController      = Modules\Calendar\Controller::getInstance();
        $this->couponsController       = Modules\Coupons\Controller::getInstance();
        $this->customerController      = Modules\Customers\Controller::getInstance();
        $this->customFieldsController  = Modules\CustomFields\Controller::getInstance();
        $this->debugController         = Modules\Debug\Controller::getInstance();
        $this->notificationsController = Modules\Notifications\Controller::getInstance();
        $this->paymentController       = Modules\Payments\Controller::getInstance();
      
        $this->settingsController      = Modules\Settings\Controller::getInstance();
        $this->smsController           = Modules\Sms\Controller::getInstance();
        $this->staffController         = Modules\Staff\Controller::getInstance();*/
  $this->serviceController       = Modules\Services\Controller::getInstance();
        // Frontend controllers that work via admin-ajax.php.
        $this->bookingController = Frontend\Modules\Booking\Controller::getInstance();
        $this->customerProfileController = Frontend\Modules\CustomerProfile\Controller::getInstance();
        if ( ! Lib\Config::isPaymentDisabled( Lib\Entities\Payment::TYPE_AUTHORIZENET ) ) {
            $this->authorizeNetController = Frontend\Modules\AuthorizeNet\Controller::getInstance();
        }
        if ( ! Lib\Config::isPaymentDisabled( Lib\Entities\Payment::TYPE_PAYULATAM ) ) {
            $this->payulatamController = Frontend\Modules\PayuLatam\Controller::getInstance();
        }
        if ( ! Lib\Config::isPaymentDisabled( Lib\Entities\Payment::TYPE_STRIPE ) ) {
            $this->stripeController = Frontend\Modules\Stripe\Controller::getInstance();
        }
        $this->wooCommerceController = Frontend\Modules\WooCommerce\Controller::getInstance();

        add_action( 'admin_menu', array( $this, 'addAdminMenu' ) );
        add_action( 'wp_loaded',  array( $this, 'init' ) );
        add_action( 'admin_init', array( $this, 'addTinyMCEPlugin' ) );
    }

    public function init()
    {
        if ( ! session_id() ) {
            @session_start();
        }
    }

    public function addTinyMCEPlugin()
    {
        new Modules\TinyMce\Plugin();
    }

    public function addAdminMenu()
    {
        /** @var \WP_User $current_user */
        global $current_user;

        // Translated submenu pages.
     
        $services       = __( 'Services',      'bookly' );
      

        if ( $current_user->has_cap( 'administrator' ) || Lib\Entities\Staff::query()->where( 'wp_user_id', $current_user->ID )->count() ) {
            if ( function_exists( 'add_options_page' ) ) {
                $dynamic_position = '80.0000001' . mt_rand( 1, 1000 ); // position always is under `Settings`
                add_menu_page( 'services', 'services', 'read', 'ab-system', '',
                    plugins_url( 'resourcess/images/menu.png', __FILE__ ), $dynamic_position );
                
               
                add_submenu_page( 'ab-system', $services, $services, 'manage_options', Modules\Services\Controller::page_slug,
                    array( $this->serviceController, 'index' ) );
              /*  add_submenu_page( 'ab-system', $settings, $settings, 'manage_options', Modules\Settings\Controller::page_slug,
                    array( $this->settingsController, 'index' ) ); */

                if ( isset ( $_GET['page'] ) && $_GET['page'] == 'ab-debug' ) {
                    add_submenu_page( 'ab-system', 'Debug', 'Debug', 'manage_options', 'ab-debug',
                        array( $this->debugController, 'index' ) );
                }

                global $submenu;
                do_action( 'bookly_admin_menu', 'ab-system' );
                unset ( $submenu['ab-system'][0] );
            }
        }
    }

}