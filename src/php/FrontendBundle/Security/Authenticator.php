<?php

namespace Frontastic\Catwalk\FrontendBundle\Security;

use Frontastic\Catwalk\ApiCoreBundle\Domain\ContextService;
use Frontastic\Common\AccountApiBundle\Domain\Account;
use Frontastic\Common\AccountApiBundle\Domain\AccountService;
use Frontastic\Common\AccountApiBundle\Domain\Session;
use Frontastic\Common\CartApiBundle\Domain\CartApi;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects) FIXME: Introcued by hotfix
 */
class Authenticator extends AbstractGuardAuthenticator
{
    private $accountService;

    private $cartApi;

    private $contextService;

    public function __construct(AccountService $accountService, CartApi $cartApi, ContextService $contextService)
    {
        $this->accountService = $accountService;
        $this->cartApi = $cartApi;
        $this->contextService = $contextService;
    }

    /**
     * Get the authentication credentials from the request and return them
     * as any type (e.g. an associate array). If you return null, authentication
     * will be skipped.
     *
     * Whatever value you return here will be passed to getUser() and checkCredentials()
     *
     * For example, for a form login, you might:
     *
     *      if ($request->request->has('_username')) {
     *          return array(
     *              'username' => $request->request->get('_username'),
     *              'password' => $request->request->get('_password'),
     *          );
     *      } else {
     *          return;
     *      }
     *
     * Or for an API token that's on a header, you might use:
     *
     *      return array('api_key' => $request->headers->get('X-API-TOKEN'));
     *
     * @param Request $request
     * @return ?mixed
     */
    public function getCredentials(Request $request)
    {
        $content = json_decode($request->getContent(), true);

        if (!$content) {
            return false;
        }

        $context = $this->contextService->createContextFromRequest($request);
        $content['locale'] = $context->locale;
        return $content;
    }

    /**
     * Return a UserInterface object based on the credentials.
     *
     * The *credentials* are the return value from getCredentials()
     *
     * You may throw an AuthenticationException if you wish. If you return
     * null, then a UsernameNotFoundException is thrown for you.
     *
     * @param mixed $credentials
     * @param UserProviderInterface $userProvider
     * @return ?UserInterface
     * @throws AuthenticationException
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        try {
            $user = new Account([
                'email' => $credentials['email'],
            ]);
            $user->setPassword($credentials['password']);
            return $this->accountService->login(
                $user,
                $this->cartApi->getAnonymous(session_id(), $credentials['locale']),
                $credentials['locale']
            );
        } catch (\Exception $e) {
            throw new AuthenticationException('Not authenticated.', 0, $e);
        }
    }

    /**
     * Returns true if the credentials are valid.
     *
     * If any value other than true is returned, authentication will
     * fail. You may also throw an AuthenticationException if you wish
     * to cause authentication to fail.
     *
     * The *credentials* are the return value from getCredentials()
     *
     * @param mixed $credentials
     * @param UserInterface $user
     * @return bool
     * @throws AuthenticationException
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        if (!($user instanceof Account)) {
            return false;
        }

        return $user->confirmed;
    }

    /**
     * Called when authentication executed and was successful!
     *
     * This should return the Response sent back to the user, like a
     * RedirectResponse to the last page they visited.
     *
     * If you return null, the current request will continue, and the user
     * will be authenticated. This makes sense, for example, with an API.
     *
     * @param Request $request
     * @param TokenInterface $token
     * @param string $providerKey The provider (i.e. firewall) key
     *
     * @return Response
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return new JsonResponse(new Session([
            'account' => $token->getUser(),
            'loggedIn' => true,
        ]));
    }

    /**
     * Called when authentication executed, but failed (e.g. wrong username password).
     *
     * This should return the Response sent back to the user, like a
     * RedirectResponse to the login page or a 403 response.
     *
     * If you return null, the request will continue, but the user will
     * not be authenticated. This is probably not what you want to do.
     *
     * @param Request $request
     * @param AuthenticationException $exception
     * @return ?Response
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new JsonResponse(
            new Session([
                'loggedIn' => false,
                'message' => $exception->getMessage() ? ('Unauthenticated: ' . $exception->getMessage()) : null,
            ]),
            ($request->get('_route') === 'Frontastic.AccountApi.Api.logout') ? 200 : 403
        );
    }

    /**
     * Called when authentication executed and was successful!
     *
     * This should return the Response sent back to the user, like a
     * RedirectResponse to the last page they visited.
     *
     * If you return null, the current request will continue, and the user
     * will be authenticated. This makes sense, for example, with an API.
     *
     * @param Request $request
     * @param TokenInterface $token
     * @param string $providerKey The provider (i.e. firewall) key
     * @return Response
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new JsonResponse(
            new Session([
                'loggedIn' => (bool)$authException,
                'message' => $authException ? $authException->getMessage() : null,
            ]),
            $authException ? 403 : 302
        );
    }

    /**
     * Does this method support remember me cookies?
     *
     * Remember me cookie will be set if *all* of the following are met:
     *  A) This method returns true
     *  B) The remember_me key under your firewall is configured
     *  C) The "remember me" functionality is activated. This is usually
     *      done by having a _remember_me checkbox in your form, but
     *      can be configured by the "always_remember_me" and "remember_me_parameter"
     *      parameters under the "remember_me" firewall key
     *
     * @return bool
     */
    public function supportsRememberMe()
    {
        return false;
    }

    /**
     * Does the authenticator support the given Request?
     *
     * If this returns false, the authenticator will be skipped.
     *
     * @param Request $request
     *
     * @return bool
     */
    public function supports(Request $request)
    {
        if ($request->attributes->get('_route') !== 'Frontastic.AccountApi.Api.login') {
            return false;
        }

        if (empty($request->getContent())) {
            return false;
        }

        $content = json_decode($request->getContent(), true);

        if (!$content) {
            return false;
        }

        return isset($content['email']) && isset($content['password']);
    }

    public function createAuthenticatedToken(UserInterface $user, $providerKey)
    {
        return parent::createAuthenticatedToken($user->cleanForSession(), $providerKey);
    }
}
