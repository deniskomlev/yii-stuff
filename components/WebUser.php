<?php

/**
 * Extended CWebUser class.
 */
class WebUser extends CWebUser
{
    protected function beforeLogin($id, $states, $fromCookie)
    {
        if ($fromCookie) {
            // When logging in from cookie, make sure the user is exists
            // and keep its actual session states.
            $user = User::model()->findByPk($id);
            if ($user) {
                return true;
            } else {
                return false;
            }
        }
        return true;
    }
}