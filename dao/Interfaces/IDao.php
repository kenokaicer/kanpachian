<?php
    namespace Dao\Interfaces;

    interface IDao{
        public function Add($object);
        public function Get($var);
        public function getAll();
        public function Update($object);
        public function Delete($var);
    }