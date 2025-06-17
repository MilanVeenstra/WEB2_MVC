<?php
namespace Framework\AccessControl;

use Framework\Http\SessionInterface;

class AuthenticationService implements AuthenticationInterface
{
    private SessionInterface $session;
    private UserProviderInterface $provider;

    public function __construct(SessionInterface $session, UserProviderInterface $provider)
    {
        $this->session  = $session;
        $this->provider = $provider;
    }

    public function login(string $username, string $password): bool
    {
        $user = $this->provider->loadUserByUsername($username);
        if ($user !== null && password_verify($password, $user->getPasswordHash())) {
            $this->session->set('user_id', $user->getId());
            return true;
        }
        return false;
    }

    public function logout(): void
    {
        $this->session->remove('user_id');
    }

    public function getUser(): ?UserInterface
    {
        $id = $this->session->get('user_id', null);
        if ($id === null) {
            return null;
        }
        return $this->provider->loadUserById($id);
    }

    public function authenticate(): void
    {
        $this->session->start();
    }
}
