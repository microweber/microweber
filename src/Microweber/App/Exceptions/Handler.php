<?php namespace Microweber\App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;


use Psr\Log\LoggerInterface;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Container\Container;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\Autmaintenancexception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\Debug\Exception\FlattenException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Console\Application as ConsoleApplication;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Debug\ExceptionHandler as SymfonyExceptionHandler;
use Illuminate\Contracts\Debug\ExceptionHandler as ExceptionHandlerContract;
use Symfony\Component\HttpFoundation\RedirectResponse as SymfonyRedirectResponse;

class Handler extends ExceptionHandler
{

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        'Symfony\Component\HttpKernel\Exception\HttpException'
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $e
     * @return void
     */
    public function report(Exception $e)
    {
        return parent::report($e);
    }


    /**
     * Create a Symfony response for the given exception.
     *
     * @param  \Exception $e
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function convertExceptionToResponse(Exception $e)
    {


        if (!function_exists('is_admin')) {
            return parent::convertExceptionToResponse($e);
        }


        $e = FlattenException::create($e);

        $handler = new SymfonyExceptionHandler(config('app.debug', false));

        $body = $handler->getHtml($e);
        if ($body) {
            if (is_admin()) {
                $body = str_replace('<h1>Whoops, looks like something went wrong.</h1>',
                    '<h1>Whoops, looks like something went wrong.</h1>' . $this->__get_error_bar_html()
                    , $body);

                if(is_ajax()){
                    $body = html_entity_decode(strip_tags($handler->getContent($e)));

                }

            }

        }
        return SymfonyResponse::create($body, $e->getStatusCode(), $e->getHeaders());
    }

    private function __get_error_bar_html()
    {
        return "
<style>
 #mw-error-clear-cache-form-holder{
  width:100%;
 }

 #mw-error-clear-cache-forms{
  margin:10px;

 }
  #mw-error-clear-cache-forms form{

  display: inline-block;
  }
 #mw-error-clear-cache-iframe{
 border: none;
 height: 30px;
 width: 100%;
 background: transparent;
 }
</style>
<div id='mw-error-clear-cache-form-holder'>
<div id='mw-error-clear-cache-forms'>
    <h1>You can perform some tasks:
   <form action='" . api_url('mw_post_update') . "' target='mw-error-clear-cache-iframe'>
<button type='submit'>Reload database</button></form>
<form action='" . api_url('clearcache') . "' target='mw-error-clear-cache-iframe'>
   <button type='submit'>Clear cache</button></form>
   <form  method='get' >
   <button type='submit'>Refresh</button></form>
   </h1>
</div>
<iframe name='mw-error-clear-cache-iframe' id='mw-error-clear-cache-iframe'></iframe>
</div>
";
    }

// onsubmit='alert(\"Done\")'
}
