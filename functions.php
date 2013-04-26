<?php
    /*
    TwigPress is a boilerplate Twig Engine for Wordpress.
    Copyright (C) 2013 Julian Xhokaxhiu

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

    Need help? Contact me at http://about.me/JulianXhokaxhiu
    */

    require_once __DIR__.'/core/lib/Twig/Autoloader.php';
    Twig_Autoloader::register();

    /* DEBUG ONLY */
    define('TwigPress_Debug', true);
    define('TwigPress_Template_URI', get_stylesheet_directory_uri().'/templates');

    class WP_API_Proxy{
        /*
            Description: Proxy Wordpress APIs so they could be available to Twig without mapping them.
            Based upon: Darko Goles (http://inchoo.net/wordpress/twig-wordpress-part2/)
        */
        public function __call($function,$arguments) {
            if (!function_exists($function)) {
                trigger_error('['.get_class(self).'] '.'function ['.$function.'] do not exist. Is it a Wordpress API?',E_USER_ERROR);
                return NULL;
            }
            return call_user_func_array($function,$arguments);
        }
    }

    class TwigPress_API_Proxy{
        public function __call($function,$arguments) {
            if (!function_exists($function)) {
                trigger_error('['.get_class(self).'] '.'function ['.$function.'] do not exist. Is it a Wordpress API?',E_USER_ERROR);
                return NULL;
            }
            return call_user_func_array($function,$arguments);
        }
        /* APIs */
        public function addCss($path){
            $hash = md5(TwigPress_Template_URI.$path);
            wp_register_style($hash,TwigPress_Template_URI.$path);
            wp_enqueue_style($hash);
        }
        public function addJs($path){
            $hash = md5(TwigPress_Template_URI.$path);
            wp_register_script($hash,TwigPress_Template_URI.$path);
            wp_enqueue_script($hash);
        }
    }

    class TwigPress{
        protected $apis;
        protected $twig;
        function __construct($args = array()){
            $loader = new Twig_Loader_Filesystem(get_stylesheet_directory().'/templates');
            $this->twig = new Twig_Environment($loader, array(
                'cache' => TwigPress_Debug ? false : get_stylesheet_directory().'/cache',
                'debug' => TwigPress_Debug
            ));
            if(TwigPress_Debug) $this->twig->addExtension(new Twig_Extension_Debug());
            $this->render(get_current_template(),$args['vars']);
        }
        protected function render($name,$tpl_vars = array()){
            if(isset($name)){
                $this->set_base_api('fn', new TwigPress_API_Proxy());
                $this->set_base_api('template_name',$name);

                $template = $this->twig->loadTemplate($name.'.twig');
                echo $template->render($this->init_apis($tpl_vars));
            }else $this->display_error('No template specified.');
        }
        protected function init_apis($tpl_vars = array()){
            $core_vars = array(
                "twpapi" => $this->get_base_api(),
                "wpapi" => new WP_API_Proxy(),
                "tplapi" => $tpl_vars
            );

            return $core_vars;
        }
        protected function get_base_api(){
            $this->set_base_api('base_uri',TwigPress_Template_URI);
            return $this->apis;
        }
        protected function set_base_api($key,$value){
            $this->apis[$key] = $value;
        }
        /* UTILITY */
        protected function display_error($error){
            trigger_error('['.get_class($this).'] '.$error,E_USER_ERROR);
        }
    }

    /*
        Description: get the current requested template
        URL: http://wordpress.stackexchange.com/questions/10537/get-name-of-the-current-template-file/10565#10565
    */
    add_filter('template_include','var_template_include');
    function var_template_include($t){
        $tpl_path = pathinfo($t);
        $GLOBALS['current_theme_template'] = $tpl_path['filename'];
        return $t;
    }
    function get_current_template(){
        if(!isset($GLOBALS['current_theme_template'])) return false;
        return $GLOBALS['current_theme_template'];
    }