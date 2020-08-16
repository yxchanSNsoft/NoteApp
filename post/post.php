<?php

include_once '../GUI/app.php';
include_once '../config/config.php';

if (isset($_POST['action'])) {

    switch ($_POST['action']) {
        case 'open':
            if (isset($_POST['note']) AND isset($_POST['category'])) {
                $note = $_POST['note'];
                $category = $_POST['category'];
                
                $files = App::get_note($note, $category, $config['relative_path']);
                $html = "";
                if (COUNT($files) > 0 ) {
                    $html = file_get_contents($config['relative_path'] . $category . "/" . $note . '/index.html');
                }
                //echo json_encode($files);
                echo $html;
            } else {
                echo "error";
            }
            break;
        case 'save':
            if (isset($_POST['title']) AND isset($_POST['currentDir']) AND isset($_POST['saveDir'])) {
                $title = $_POST['title'];
                $body = $_POST['body'] ? $_POST['body'] : "<html></html>" ;
                $saveDir = $_POST['saveDir'];
                $currentDir = $_POST['currentDir'];

                // if save destination not same with current file location, then rename the folder to match it
                if ($saveDir != $currentDir) {
                    rename($config['relative_path'] . $currentDir , $config['relative_path'] . $saveDir );
                } 

                $result = App::save($title, $body, $saveDir, $config['relative_path']);
                if ($result) {
                    echo "success";
                } else {
                    echo "error writing";
                }

            } else {
                echo "error isset";
            }
            break;
        case 'newCategory':
            if (isset($_POST['name'])) {
                $name = $_POST['name'];
                //check if folder exists
                if (file_exists($config['relative_path'] . $name)) {
                    die("Error, Category already exists");
                }
                $result = mkdir($config['relative_path'] . $name);
                if ($result) {
                    echo "success";
                } else {
                    die ("error");
                }
            } else {
                die ("error");
            }


            break;
        case 'refreshMenu';
            $menu = new App($config['relative_path']);
            $menuHTML = $menu->render_menu();
            unset($menu);
            if ($menuHTML) {
                echo $menuHTML;
            } else {
                die ("error");
            }
            break;
        default:

    }

}


