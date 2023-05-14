<?php
namespace WordCamp\App\Controllers;


class SideNavController
{
    public function index(): string
    {
        return wep_view('application.php');
    }
}