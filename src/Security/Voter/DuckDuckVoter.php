<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\DuckDuck;

class DuckDuckVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['duck_new', 'duck_edit', 'duck_view', 'duck_delete'])
            && $subject instanceof DuckDuck;
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
            case 'duck_new':

                return  in_array('ROLE_ADMIN', $user->getRoles());

                break;
            case 'duck_edit':

                return in_array('ROLE_ADMIN', $user->getRoles()) || $user->getId() == $subject->getId();

                break;
            case 'duck_view':

                return  in_array('ROLE_ADMIN', $user->getRoles()) || $user->getId() == $subject->getId();

                break;
            case 'duck_delete':

                return  in_array('ROLE_ADMIN', $user->getRoles()) || $user->getId() == $subject->getId();

                break;
        }

        return false;
    }
}
