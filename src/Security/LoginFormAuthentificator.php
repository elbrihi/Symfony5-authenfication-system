<?php 
//47
namespace App\Security;

use Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\PasswordUpgradeBadge;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator ;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Repository\UserRepository;

//class LoginFormAuthentificator implements AuthenticatorInterface

class LoginFormAuthentificator extends AbstractAuthenticator
{
    private $userRepository;
    private $urlGeneretor ;

    public function __construct(UserRepository $userRepository, UrlGeneratorInterface $urlGeneretor)
    {
        
        $this->userRepository = $userRepository;
        $this->urlGeneretor = $urlGeneretor ;
    
    } 
     
    public function supports(Request $request): ?bool
    {
        
        

        return $request->attributes->get('_route') === 'app_login'
                && $request->isMethod('POST');
    }

    /**
     * @throws AuthenticationException
     */
    public function authenticate(Request $request): PassportInterface
    {

        $user = $this->userRepository->findOneByUsername($request->request->get('email'));

        if (!$user) {
            throw new UsernameNotFoundException();
        }

        return new Passport($user, new PasswordCredentials($request->request->get('password')), [
            // and CSRF protection using a "csrf_token" field
            
            new CsrfTokenBadge('login_form', $request->request->get('csrf_token'))

            // and add support for upgrading the password hash
            //new PasswordUpgradeBadge($request->request->get('password'), $this->userRepository)
        ]);
 
    }


    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
       // dd('success');
        return new RedirectResponse($this->urlGeneretor->generate('app_home'));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new RedirectResponse($this->urlGeneretor->generate('app_login'));

        //dump('failer');die;
        
    }
}
?>