<?php
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
