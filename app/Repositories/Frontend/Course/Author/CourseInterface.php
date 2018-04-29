<?php
namespace App\Repositories\Frontend\Course\Author;


interface CourseInterface {



    public function getAll();


    public function find($id);


    public function delete($id);
    
    
    
}