<?php
/**
 * Created by PhpStorm.
 * User: coelho
 * Date: 10/17/2017
 * Time: 1:53 AM
 */

namespace SONFin\Models;


interface UserInterface
{
    public function getId():int;
    public function getFullname():string;
    public function getEmail():string;
    public function getPassword():string;
}