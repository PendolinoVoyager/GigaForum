<?php
namespace App\Security\Voter;

use App\Entity\Post;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class PostVoter extends Voter
    {
        public function __construct(private readonly Security $security)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [Post::EDIT, Post::VIEW])
        && $subject instanceof Post;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // Check if the user is a UserInterface instance
        if (!$user instanceof UserInterface) {
          return false;
        }

        if ($this->security->isGranted('ROLE_ADMIN') || $this->security->isGranted('ROLE_MODERATOR')) {
         return true;
        }

        return match ($attribute) {
            Post::EDIT => $user->getId() === $subject->getAuthor()->getId(),
            Post::VIEW => true,
            default => false,
        };

    }
}
