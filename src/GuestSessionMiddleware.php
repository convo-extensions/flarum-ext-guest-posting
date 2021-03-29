<?php

namespace Alter\GuestPosting;

use Dflydev\FigCookies\FigResponseCookies;
use Flarum\Http\CookieFactory;
use Flarum\Settings\SettingsRepositoryInterface;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Session\Store;
use Illuminate\Support\Arr;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use SessionHandlerInterface;

class GuestSessionMiddleware implements MiddlewareInterface
{
    const COOKIE_NAME = 'guest';

    protected $handler;
    protected $cookie;

    public function __construct(SessionHandlerInterface $handler, CookieFactory $cookie, ConfigRepository $config)
    {
        $this->handler = $handler;
        $this->cookie = $cookie;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $session = new Store(
            self::COOKIE_NAME,
            $this->handler,
            Arr::get($request->getCookieParams(), $this->cookie->getName(self::COOKIE_NAME))
        );

        $session->start();

        GuestManager::loadSession($session);

        $response = $handler->handle($request);

        GuestManager::saveSession($session);

        if (GuestManager::isActive()) {
            /**
             * @var $settings SettingsRepositoryInterface
             */
            $settings = resolve(SettingsRepositoryInterface::class);

            return FigResponseCookies::set(
                $response,
                $this->cookie->make($session->getName(), $session->getId(), $settings->get('guest-posting.sessionLifetime', 48) * 3600)
            );
        }

        return $response;
    }
}
