<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');


class StudentController extends Controller
{
    
    private $student = [
        'student_id'  => 'MCC2024-00104',
        'name'        => 'Apostol, rhemz jhon Cyrus C.',
        'course'      => 'BSIT',
        'year'        => '3rd Year',
        'section'     => '3-F3',
        'email'       => 'rhemzjhoncyruscapostol@gmail.com',
        'address'     => 'Puerto Galera',
        'contact'     => '0981-272-9490',
        'skills'      => 'PHP, JavaScript, UI Design',
        'bio'         => 'Kaya ko, kaya ko! I am a passionate and dedicated student with a strong interest in web development and programming. I enjoy learning new technologies and applying them to real-world projects. In my free time, I like to explore new places, read tech blogs, and work on personal coding projects.',
    ];

  
    public function index()
    {
       
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['profile_access'] = true;

        $data['name'] = $this->student['name'];
        $data['denied'] = isset($_GET['denied']);

        $this->call->view('student_home', $data);
    }

    
    public function profile()
    {
        $this->call->view('student_profile', $this->student);
    }
}
