<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Validation\ValidationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($this->isHttpException($e))
        {
            return $this->renderHttpException($e);
        }


        if (config('app.debug'))
        {
            return $this->renderExceptionWithWhoops($e);
        }

        return parent::render($request, $e);
    }

    /**
     * Render an exception using Whoops.
     *
     * @param  \Exception $e
     * @return \Illuminate\Http\Response
     */
    protected function renderExceptionWithWhoops(Exception $e)
    {
        $whoops = new \Whoops\Run;
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler());

        return new \Illuminate\Http\Response(
            $whoops->handleException($e),
            $e->getStatusCode(),
            $e->getHeaders()
        );
    }


    /**
     * @param $filename
     * @throws Exception
     * @return array
     */
    public static function readFile($filename)
    {
        try {
            $file = "assets/json/" . $filename;
            $content = file_get_contents($file);
        } catch (Exception $e) {
            throw new Exception("Error open file: " . $e->getMessage());
        }
        return json_decode($content, true);
    }

    /**
     * @param $file
     * @param $content
     * @throws Exception
     */
    public static function writeFile($file, $content)
    {
        $file = "assets/json/" . $file;
        try {
            if (file_exists($file)) {
                unlink($file);
            }

            $fh = fopen($file, 'w+');
            fwrite($fh, $content);
            fclose($fh);

        } catch (Exception $e) {
            throw new Exception("Error open file: " . $e->getMessage());
        }
    }
}
