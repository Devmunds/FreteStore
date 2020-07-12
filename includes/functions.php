<?php
    function fs_options_page(){
        add_options_page("Frete Store", "Frete Store", "manage_options", "fretestore", "fs_options_page_layout");
    }
    //
    function fs_options_page_layout(){
        global $fs_pluginDir;

        include $fs_pluginDir . "views/templates/options_page_layout.php";
    }



?>