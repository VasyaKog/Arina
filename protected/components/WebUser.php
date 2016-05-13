<?php
/**
 * Permission of standard CWebUser.
 *
 */
class WebUser extends CWebUser
{
    /**
     * @var null flowing values for result of model
     */
    private $_model = null;

    /**
     * Receiving username of the current user.
     *
     * @return array|mixed|null
     */
    public function getUsername()
    {

        if ($user = $this->getModel()) {
            return $user->username;
        }
    }

    /**
     * Data acquisition from base
     *
     * @return User|CActiveRecord|null
     */
    private function getModel()
    {
        if (!$this->isGuest && $this->_model === null) {
            $this->_model = User::model()->findByAttributes(array('id' => $this->id), array('select' => 'username, role, id'));
        }
        return $this->_model;
    }

    public function getMainMenu()
    {
        $menu = array();
        if (Yii::app()->user->checkAccess('admin')) {
            require_once('menu/admin.php');
            return $menu;
        } else if (Yii::app()->user->checkAccess('dephead')) {
            require_once('menu/dephead.php');
            return $menu;
        } else if (Yii::app()->user->checkAccess('cychead')) {
            require_once('menu/cychead.php');
            return $menu;
        } else if (Yii::app()->user->checkAccess('curator')) {
            require_once('menu/curator.php');
            return $menu;
        } else if (Yii::app()->user->checkAccess('teacher')) {
            require_once('menu/teacher.php');
            return $menu;
        } else if (Yii::app()->user->checkAccess('student')) {
            require_once('menu/student.php');
            return $menu;
        } else if (Yii::app()->user->checkAccess('inspector')) {
            require_once('menu/inspector.php');
            return $menu;
        }else if (Yii::app()->user->checkAccess('navch')) {
            require_once('menu/navch.php');
            return $menu;
        }else if (Yii::app()->user->checkAccess('director')) {
            require_once('menu/director.php');
            return $menu;
        }else if (Yii::app()->user->checkAccess('zastupnik')) {
            require_once('menu/zastupnik.php');
            return $menu;
        } else {
            require_once('menu/guest.php');
            return $menu;
        }
    }

    /**
     * @return int
     */
    public function getRole()
    {
        /**
         * @var User $user
         */
        if ($user = $this->getModel()) {
            return $user->role;
        }
        return User::ROLE_GUEST;
    }

    /**
     * @return int
     */
    public function getIdentityId()
    {
        /**
         * @var User $user
         */
        if ($user = $this->getModel()) {
            return $user->identity_id;
        }
        return 0;
    }
}