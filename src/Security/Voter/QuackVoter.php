<?php

namespace App\Security\Voter;

use App\Entity\Quack;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class QuackVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['quack_edit'])
            && $subject instanceof Quack;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'quack_edit':
                // logic to determine if the user can EDIT
//dd($subject->getAuthor()->getDuckname());
                return in_array('ROLE_ADMIN', $user->getRoles()) || $user->getDuckname() == $subject->getAuthor()->getDuckname();
                // return true or false
                break;
        }

        return false;
    }
}
