<?php
    namespace Dao\Interfaces;

    interface IDao{
        public function Add($object);
        public function Retrieve($var);
        public function RetrieveAll();
        public function Update($object);
        public function Delete($var);
    }