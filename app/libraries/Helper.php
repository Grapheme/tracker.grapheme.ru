<?php

class Helper {

    public static function d($array) {
        echo "<pre style='text-align:left'>" . print_r($array, 1) . "</pre>";
    }

    public static function dd($array) {
        self::d($array);
        die;
    }

    public static function ta($object) {

        $return = $object;
        if (is_object($object)) {
            $return = $object->toArray();
        } elseif (is_array($object)) {
            foreach ($object as $o => $obj) {
                $return[$o] = is_object($obj) ? $obj->toArray() : $obj;
            }
        }
        self::d($return);
    }

    public static function tad($object) {
        self::dd(is_object($object) ? $object->toArray() : $object);
    }

    public static function layout($file = '') {

        return Config::get('site.template') . ($file ? '.' . $file : '');
    }

    public static function acclayout($file = '') {

        return AuthAccount::getStartPage().($file ? '.'. $file : '');
    }

    public static function nl2br($text) {
        $text = preg_replace("~[\r\n]+~is", "\n<br/>\n", $text);
        return $text;
    }

    /**************************************************************************************/

    public static function cookie_set($name = false, $value = false, $lifetime = 86400) {
        if (is_object($value) || is_array($value)) {
            $value = json_encode($value);
        }
        setcookie($name, $value, time() + $lifetime, "/");
        if ($lifetime > 0) {
            $_COOKIE[$name] = $value;
        }
    }

    public static function cookie_get($name = false) {
        $return = @isset($_COOKIE[$name]) ? $_COOKIE[$name] : false;
        $return2 = @json_decode($return, 1);
        if (is_array($return2)) {
            $return = $return2;
        }
        return $return;
    }

    public static function cookie_drop($name = false) {
        self::cookie_set($name, false, 0);
        $_COOKIE[$name] = false;
    }

    /**************************************************************************************/

    public static function isRoute($route_name = false, $route_params = array(), $match_text = ' class="active"', $mismatch_text = '') {

        $match = true;
        $route = Route::getCurrentRoute();
        if (is_string($route_params)) {
            preg_match('~\{([^\}]+?)\}~is', $route->getPath(), $matches);
            if (@$matches[1] != '') {
                $route_params = array($matches[1] => $route_params);
            } else {
                $route_params = array();
            }
        }
        if (count($route_params)) {
            $route_params = URL::get_modified_parameters($route_name, $route_params);
            foreach ($route_params as $key => $value) {

                #Helper::d("[" . $key . "] => " . $route->getParameter($key) . " = " . $value);

                if ($route->getParameter($key) != $value) {
                    $match = false;
                    break;
                }
            }
        }
        return (Route::currentRouteName() == $route_name && $match) ? $match_text : $mismatch_text;
    }
}

