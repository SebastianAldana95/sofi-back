<?php

namespace App\Models\Passport;

use App\Models\User;
use Laravel\Passport\Http\Controllers\HandlesOAuthErrors;
use Laravel\Passport\TokenRepository;
use Lcobucci\JWT\Parser as JwtParser;
use League\OAuth2\Server\AuthorizationServer as AuthorizationServer;
use Nyholm\Psr7\Response as Psr7Response;
use Psr\Http\Message\ServerRequestInterface;

class Authenticator
{
    use HandlesOAuthErrors;

    private $tokens;
    private $server;
    private $jwt;
    private $request = null;

    public function __construct(ServerRequestInterface $request)
    {
        $this->jwt = resolve(JwtParser::class);
        $this->server = resolve(AuthorizationServer::class);
        $this->tokens = resolve(TokenRepository::class);
        $this->request = $request;
    }

    public function authenticate(PassportClient $client, $username, $password)
    {
        $request = $this->request->withParsedBody([
            "username" => $username,
            "password" => $password,
            "client_id" => $client->id,
            "client_secret" => $client->secret,
            "grant_type" => "password"
        ]);

        $response = $this->withErrorHandling(function () use ($request) {
            return $this->convertResponse($this->server->respondToAccessTokenRequest($request, new Psr7Response));
        })->content();

        return json_decode($response);

    }

}
