<?php
namespace Api;
use Pest\Api;

class Pic extends Api
{

    public $post = array(
            
    );

    public function post ()
    {
        $uploads_dir = '/var/web/vhost/jige-rest/public/static/uploads';
        $tmp_name = $_FILES["pic"]["tmp_name"];
        $name = (string) new \MongoId(null).'.jpg';
        move_uploaded_file($tmp_name, "$uploads_dir/$name");
        echo '<script>document.domain="mallschool.me";parent.loadfile("'.$name.'")</script>';
    }
}