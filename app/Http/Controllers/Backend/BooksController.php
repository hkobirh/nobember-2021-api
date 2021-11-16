<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Book;
use Exception;

class BooksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return mixed
     */
    public function index()
    {
        $books = Book::select('id', 'title', 'author', 'publisher', 'edition', 'country', 'price', 'image',
            'create_by')->orderBy('id', 'DESC')->paginate(20);
        return $books;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'     => 'required|string|unique:books',
            'author'    => 'required|string',
            'publisher' => 'required|string',
            'edition'   => 'required|string',
            'country'   => 'required|string',
            'price'     => 'required|integer',
            //'image'     => 'required',
        ]);
        if ($validator->fails()) return error_validation($validator->errors());
         $image = $this->image_upload($request->image, 'books');
        try {
            $books = Book::create([
                'title'     => $request->title,
                'author'    => $request->author,
                'publisher' => $request->publisher,
                'edition'   => $request->edition,
                'country'   => $request->country,
                'price'     => $request->price,
                'image'     => $image['url'],
                'create_by' => $request->create_by
            ]);
            return success_message($books->only('title', 'author'), __('message.books.create.success'), 201);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function image_upload($image, $directory)
    {
        $file = explode(';base64,', $image);
        $file1 = explode('/', $file[0]);
        $file_ex = end($file1);
        $file_name = uniqid() . date('-Ymd-his.') . $file_ex;
        $file_data = str_replace(',', '', $file[1]);
        Storage::disk('public')->put($directory . '/' . $file_name, base64_decode($file_data));

        return [
            'name' => $file_name,
            'url'  => Storage::disk('public')->url($directory . '/' . $file_name)
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        $books = Book::select('id', 'title', 'author', 'publisher', 'edition', 'country', 'price', 'image', 'create_by')->find($id);
        return $books;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $book = Book::find($id);
        $validator = Validator::make($request->all(), [
            'title'     => 'required|string',
            'author'    => 'required|string',
            'publisher' => 'required|string',
            'edition'   => 'required|string',
            'country'   => 'required|string',
            'price'     => 'required|integer',
        ]);
        if ($validator->fails()) return error_validation($validator->errors());

        try {
                $book->title     = $request->title;
                $book->author    = $request->author;
                $book->publisher = $request->publisher;
                $book->edition   = $request->edition;
                $book->country   = $request->country;
                $book->price     = $request->price;
                $book->create_by = auth()->id();
                if(strlen($request->image > 100)){
                    $image = $this->image_upload($request->image, 'books');
                    $book->image = $image['url'];
                }
                $book->update();
            return success_message($book, __('message.books.update.success'), 201);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $book = Book::find($id);
        $book->delete();
        return response()->json([
            'success' => true,
            'message' => 'The book has been deleted successfully.'
        ]);
    }
}
