<?php

namespace MicroweberPackages\App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        \BadMethodCallException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {

        $exceptionRenderer = parent::render($request, $exception);

        if (is_admin()) {
            $exceptionContent = $exceptionRenderer->getContent();
            if (is_string($exceptionContent)) {
                if (strpos($exceptionContent, 'Ignition.start();') !== false) {
                    $exceptionContent .= $this->getMicroweberErrorBarHtml();
                    return $exceptionContent;
                }
            }
        }

        return $exceptionRenderer;
    }

    private function getMicroweberErrorBarHtml()
    {
        return '
        <style>
        h2 {
            font-size:25px;
            font-weight: bold;
        }
         #mw-error-clear-cache-form-holder{
            width:100%;
            background: #fff;
            border-top: 1px solid #d7d5e6;
            position: fixed;
            bottom: 5px;
            padding: 15px;
            z-index: 1000;
         }
         #mw-error-clear-cache-forms{
            margin:10px;
         }
         .btn {
            border: 1px solid #4b476d;
            background: #342d59;
            color: #fff;
            padding: 5px 37px;

         }
        </style>
        <div id="mw-error-clear-cache-form-holder">
            <div id="mw-error-clear-cache-forms">
                <h2>OOPS! There is some error...</h2>
                <h3>Try to fix it by yourself using following buttons.</h3>
                <br />
                 <a href="' . api_url('mw_post_update') . '?redirect_to='.url_current().'" class="btn">Reload database</a>
                 <a href="' . api_url('mw_reload_modules') . '?redirect_to='.url_current().'" class="btn">Reload modules</a>
                 <a href="' . api_url('clearcache') . '?redirect_to='.url_current().'" class="btn">Clear cache</a>

                 <a href="" class="btn">Refresh</a>
            </div>
        </div>
        ';
    }
}
