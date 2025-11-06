 <?php
 namespace App\Exceptions;

 use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
 use Throwable;
 use Illuminate\Http\JsonResponse;
 use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
 use Illuminate\Database\Eloquent\ModelNotFoundException;
 
 class Handler extends ExceptionHandler
 {
    protected $dontReport = [];
    public function register(): void {}
    public function render($request, Throwable $e)
    {
        if ($request->expectsJson()) {
            $status = 500; $message = 'Server Error';
        if ($e instanceof ModelNotFoundException) {
            $status = 404; $message = 'Not Found';
        } elseif ($e instanceof HttpExceptionInterface) {
            $status = $e->getStatusCode();
            $message = $e->getMessage() ?: $message;
        }
            return new JsonResponse(['error' => $message], $status);
        }
        return parent::render($request, $e);
    }
 }