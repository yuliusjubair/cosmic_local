<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class app_composer
{
     function __construct()
     {
         // COMPSER VENDOR DIRECTORY
         include(APPPATH.'vendor/autoload.php');
     }
}
