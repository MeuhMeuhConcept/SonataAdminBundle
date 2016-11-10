<?php

namespace MMC\SonataAdminBundle\Security\Voter;

use MMC\FosUserBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserVoter extends Voter
{
    const VIEW = 'ROLE_MMC_SONATA_ADMIN_USER_VIEW';
    const EDIT = 'ROLE_MMC_SONATA_ADMIN_USER_EDIT';

    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::VIEW, self::EDIT])) {
            return false;
        }

        // only vote on Post objects inside this voter
        if (!$subject instanceof User) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($subject, $user);
            case self::EDIT:
                return $this->canEdit($subject, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canView(User $subject, User $user)
    {
        // if they can edit, they can view
        if ($this->canEdit($subject, $user)) {
            return true;
        }

        return false;
    }

    private function canEdit(User $subject, User $user)
    {
        return $user === $subject;
    }
}
