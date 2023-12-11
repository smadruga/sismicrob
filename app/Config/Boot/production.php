<?php

/*
 | --------------------------------------------------------------------
 | HUAP constants
 | --------------------------------------------------------------------
 |
 | Custom Constants
 |
 */
defined('HUAP_APPNAME') || define('HUAP_APPNAME', env('mod.name.first'));
defined('HUAP_MSG_ERROR') || define('HUAP_MSG_ERROR', '<i class="fa-solid fa-exclamation-circle"></i> ERRO <i class="fa-solid fa-exclamation-circle"></i> Verifique os campos abaixo.');
defined('HUAP_MSG_ALERT') || define('HUAP_MSG_ALERT', env('mod.name.first'));
defined('HUAP_MSG_SUCCESS') || define('HUAP_MSG_SUCCESS', env('mod.name.first'));

/*
 |--------------------------------------------------------------------------
 | ERROR DISPLAY
 |--------------------------------------------------------------------------
 | Don't show ANY in production environments. Instead, let the system catch
 | it and display a generic error message.
 */
ini_set('display_errors', '0');
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);

/*
 |--------------------------------------------------------------------------
 | DEBUG MODE
 |--------------------------------------------------------------------------
 | Debug mode is an experimental flag that can allow changes throughout
 | the system. It's not widely used currently, and may not survive
 | release of the framework.
 */
defined('CI_DEBUG') || define('CI_DEBUG', false);
