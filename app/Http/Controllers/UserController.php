<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilterRequest;
use Illuminate\Support\Facades\File;
use Illuminate\Support\LazyCollection;
use Illuminate\Support\Str;


class UserController extends Controller
{
    private array $modelClasses;

    public function __construct()
    {
        $this->bootModels();
    }

    public function index(FilterRequest $request)
    {
       // memory allocations is important, so we will use LazyCollection to avoid memory overflow
        //todo: more code refactoring is needed but I was busy

        $filteredUsers = LazyCollection::make(function () use ($request) {
            foreach ($this->getAllArrayInModelClass() as $user) {

                if ($request->has('provider') && $user->provideType !== substr($request->provider, -1)) {
                    continue;
                }

                if ($request->has('statusCode') && $user->statusString !== $request->statusCode) {
                    continue;
                }
                if ($request->has('balanceMin') && $user->balance < $request->balanceMin) {
                    continue;
                }
                if ($request->has('balanceMax') && $user->balance > $request->balanceMax) {
                    continue;
                }
                if ($request->has('currency') && $user->currency !== $request->currency) {
                    continue;
                }
                yield $user;
            }
        });

        return $this->returnStream($filteredUsers);

    }

    private function returnStream($filteredUsers): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        // todo: create pagination for return to increase the performance and avoid memory overflow
        return response()->stream(function () use ($filteredUsers) {
            echo '[';
            $firstChunk = true;
            $chunkSize = request('chunkSize', 100);

            foreach ($filteredUsers->chunk($chunkSize) as $chunk) {
                if (!$firstChunk) {
                    echo ',';
                }
                echo json_encode($chunk);
                $firstChunk = false;
            }
            echo ']';
        }, 200, ['Content-Type' => 'application/json']);
    }


    protected function bootModels()
    {
        $this->modelClasses = [];

        foreach (File::allFiles(app_path('Models')) as $modelFile) {
            $modelClass = 'App\\Models\\' . Str::replaceLast('.php', '', $modelFile->getFilename());
            if (is_subclass_of($modelClass, 'App\\Models\\Provider') && method_exists($modelClass, 'boot')) {
                $this->modelClasses[] = $modelClass;
                $modelClass::boot();
            }
        }

    }

    private function getAllArrayInModelClass()
    {
        return LazyCollection::make(function () {
            foreach ($this->modelClasses as $modelClass) {
                foreach ($modelClass::all() as $item) {
                    yield $item;
                }
            }
        });

    }


}
