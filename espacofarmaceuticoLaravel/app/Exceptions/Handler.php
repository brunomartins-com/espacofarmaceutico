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
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        return parent::render($request, $e);
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
