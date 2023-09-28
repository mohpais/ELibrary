<?php
    function getBaseUrl($pathDirection) {
        // Is connection secure? Do we need https or http?
        // See http://stackoverflow.com/a/16076965/1150683
        $isSecure = false;
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
            $isSecure = true;
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) 
            && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https'
            || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) 
            && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
                $isSecure = true;
        }

        $REQUEST_PROTOCOL = $isSecure ? 'https' : 'http';
        $REQUEST_PROTOCOL .= '://';

        // Create path variable to the root of this project
        $path_var = $REQUEST_PROTOCOL.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

        // If URL ends in a slash followed by one or more digits (e.g. http://domain.com/abcde/1), 
        // returns a cleaned version of the URL, e.g. http://domain.com/
        if (preg_match("/\/\d+\/?$/", $path_var)) {
            $path_var = preg_replace("/\w+\/\d+\/?$/", '', $path_var);
        }

        return $path_var;
    }

    function get_current_url() {
        $url = $_SERVER['REQUEST_URI'];
        $seperateUrl = explode("/", $url);
        $currentUrl = $seperateUrl[count($seperateUrl) - 1];
        $pageOfFile = explode(".", $currentUrl);
        $page = $pageOfFile[0];
        if( strpos($page, '-') ) {
            $seperatePage = explode("-", $page);
            $newUrl = "";
            for ($i=0; $i < count($seperatePage); $i++) { 
                $newUrl = $newUrl . ' ' . $seperatePage[$i];
            }
            $page = $newUrl;
        }
        
        return trim($page);
    }

    function active_navbar($fileName) {
        print_r($fileName);
        if (isset($_SESSION['page_before']) && get_current_url() == "review-kategori") {
            return in_array($_SESSION['page_before'], $fileName) ? 'active' : '';
        }
        return in_array(get_current_url(), $fileName) ? 'active' : '';
    }
