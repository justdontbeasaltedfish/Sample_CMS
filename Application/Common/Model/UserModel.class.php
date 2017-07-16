<?php

namespace Common\Model;

use Think\Model;

class UserModel extends Model
{
    private $_db;

    public function __construct()
    {
        $this->_db = M('user');
    }

    public function getUserDataByUsername($user_name)
    {
        return $this->_db
            ->where('user_name="' . $user_name . '"')
            ->find();
    }

    public function doesEmailAlreadyExist($user_email)
    {
        return $this->_db
            ->where('user_email="' . $user_email . '"')
            ->find();
    }

    public function writeNewUserToDatabase($data)
    {
        return $this->_db->add($data);
    }

    public function findUserNameById($user_id)
    {
        $result = $this->_db
            ->field('user_name')
            ->where('user_id=' . $user_id)
            ->select();
        $user_name = $result[0]['user_name'];
        return $user_name;
    }
}