<?php
/*
Plugin Name: CallbackKiller service widget
Plugin URI: http://callbackkiller.com/
Description: Виджет обратного звонка, онлайн чат сервиса CallbackKiller для WordPress
Author: CallbackKiller
Author URI: http://callbackkiller.com/
Version: 1.2
*/


class CallbackKillerWidget
{

  function __construct()
  {
    add_action('admin_menu', array($this, 'cbk_create_menu') );
    add_action('admin_init', array($this, 'cbk_init') );

    $pluginurl = plugin_basename(__FILE__);
    add_filter("plugin_action_links_$pluginurl", array($this,'cbk_add_plugin_option_link_page'));

    add_action( 'wp_head', array($this, 'cbk_wp_footer_code'), $priority = 10);

    add_action( 'wp_ajax_cbk_signin_v1', array($this, 'cbk_signin') );
    add_action( 'wp_ajax_nopriv_cbk_signin_v1', array($this, 'cbk_signin') );

    add_action( 'wp_ajax_cbk_signup_v1', array($this, 'cbk_signup') );
    add_action( 'wp_ajax_nopriv_cbk_signup_v1', array($this, 'cbk_signup') );

    add_action( 'wp_ajax_cbk_signout_v1', array($this, 'cbk_signout') );
    add_action( 'wp_ajax_nopriv_cbk_signout_v1', array($this, 'cbk_signout') );

    add_action( 'wp_ajax_cbk_save_v1', array($this, 'cbk_save') );
    add_action( 'wp_ajax_nopriv_cbk_save_v1', array($this, 'cbk_save') );
  }

  function cbk_create_menu() {
    add_menu_page('CallbackKiller service widget settings', 'CallbackKiller', 'administrator', __FILE__, array($this, 'cbk_create_settings_page'), plugins_url('/images/menulogo.png', __FILE__));
  }

  function cbk_init() {
    if ( !get_option('cbk_api_loginhash') ) {
      add_option("cbk_api_loginhash", "false", '', 'yes');
    }
    if ( !get_option('cbk_is_login') ) {
      add_option("cbk_is_login", "false", '', 'yes');
    }
    if ( !get_option('cbk_site_id') ) {
      add_option("cbk_site_id", "false", '', 'yes');
    }
    if ( !get_option('cbk_domain') ) {
      add_option("cbk_domain", "false", '', 'yes');
    }
  }

  function cbk_create_settings_page() {
    wp_register_style( 'cbk-settings-style', plugins_url( '/css/settings.css', __FILE__ ) );
    wp_enqueue_style( 'cbk-settings-style' );

    wp_register_script( 'cbk-settings-js', plugins_url( '/js/settings.js', __FILE__ ), array( 'jquery' ), NULL, TRUE );
    wp_enqueue_script( 'cbk-settings-js' );
    wp_localize_script( 'cbk-settings-js', 'cbk_ajax_object', array( 'cbk_ajax_url' => admin_url( 'admin-ajax.php' )) );

    $is_login = get_option('cbk_is_login');
    $country_list = $this->cbk_api_exec('getcountrylist');
    $country_list = $country_list['data'];

    if ($is_login && $is_login !== 'false') {
      $site_list = $this->cbk_api_exec('getsitelist', array('hash' => get_option('cbk_api_loginhash'), 'domain' => get_option('cbk_domain')));
      $site_list = $site_list['data'];
      $domain = 'http://' . get_option('cbk_domain') . "/user/dashboard/?loginhash=" . get_option('cbk_api_loginhash');
      $site_id = get_option('cbk_site_id');
    }
    require_once( 'callbackkiller-settings-page.php' );
  }

  function cbk_add_plugin_option_link_page($links) {
    $settings_link = '<a href="' . admin_url( 'admin.php?page=callbackkiller-widget/callbackkiller-widget.php') .'">Настройки</a>';
    array_unshift($links, $settings_link);
    return $links;
  }

  function cbk_wp_footer_code() {
    $cbk_site_id = get_option( 'cbk_site_id' );
    if ($cbk_site_id && $cbk_site_id !== 'false') {
      echo '<link rel="stylesheet" href="https://cdn.callbackkiller.com/widget/cbk.css">';
      echo '<script type="text/javascript" src="https://cdn.callbackkiller.com/widget/cbk.js?wcb_code=' . $cbk_site_id .'" charset="UTF-8" async></script>';
    }
  }

  function cbk_api_exec($actionName, $params = false){
    $params = json_encode($params);
    $url = "http://crm.callbackkiller.ru/cmsapi/{$actionName}/?params={$params}";
    $result = json_decode(file_get_contents($url), true);
    return $result;
  }

  function cbk_api_save($result){
    if ($result['result'] === 'success') {
      update_option('cbk_is_login', 'true');
      update_option('cbk_api_loginhash', $result['data']['hash']);
      if ($result['data']['siteid']) {
        update_option('cbk_site_id', $result['data']['siteid']);
      }
      update_option('cbk_domain', $result['data']['domain']);
    }
  }

  function cbk_signin(){  
    $result = $this->cbk_api_exec('signin', array('login' => $_REQUEST[ 'login' ], 'password' => $_REQUEST[ 'password' ], 's' => 'wps'));
    $this->cbk_api_save($result);
    echo json_encode($result);
    die();
  }

  function cbk_signup(){  
    $result = $this->cbk_api_exec('signup', array('login' => $_REQUEST[ 'login' ], 'password' => $_REQUEST[ 'password' ], 'country' => $_REQUEST[ 'country' ], 'site' => $_SERVER['HTTP_HOST'], 's' => 'wps'));
    $this->cbk_api_save($result);
    echo json_encode($result);
    die();
  }

  function cbk_signout(){ 
    update_option('cbk_is_login', 'false');
    update_option('cbk_api_loginhash', 'false');
    update_option('cbk_site_id', 'false');
    update_option('cbk_domain', 'false');
    echo json_encode(array('result'=>'success'));
    die();
  }

  function cbk_save(){ 
    update_option('cbk_site_id', $_REQUEST[ 'siteid' ]);
    echo json_encode(array('result'=>'success'));
    die();
  }
}
$CallbackKillerWidgetContainer = new CallbackKillerWidget;