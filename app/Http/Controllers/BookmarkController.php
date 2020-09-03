<?php

namespace App\Http\Controllers;

use App\Bookmark;
use Illuminate\Http\Request;
use App\Entities\Excel\Excel;
use App\Entities\Grabber\Grabber;
use Hash;

class BookmarkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $sortBy = 'created_at';
        $orderBy = 'desc';
        $perPage = 5;

        if ($request->has('sortBy')) {
            $sortBy = $request->query('sortBy');
        }
        if ($request->has('orderBy')) {
            $orderBy = $request->query('orderBy');
        }

        $reverseOrder = $orderBy === 'desc' ? 'asc' : 'desc';

        $bookmarks = Bookmark::orderBy($sortBy, $orderBy)->paginate($perPage);

        return view('index', ['bookmarks' => $bookmarks, 'orderBy' => $reverseOrder]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('create');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \PHPHtmlParser\Exceptions\ChildNotFoundException
     * @throws \PHPHtmlParser\Exceptions\CircularException
     * @throws \PHPHtmlParser\Exceptions\ContentLengthException
     * @throws \PHPHtmlParser\Exceptions\LogicalException
     * @throws \PHPHtmlParser\Exceptions\NotLoadedException
     * @throws \PHPHtmlParser\Exceptions\StrictException
     */
    public function store(Request $request)
    {
        $url = $request->input('url');
        $password = $request->input('password');

        if (strlen(trim($url)) === 0) {
            return redirect()->back()->withErrors(['message' => 'Empty string given']);
        }

        $isExist = Bookmark::where('url', $url)->exists();

        if ($isExist) {
            return redirect()->back()->withErrors(['duplicate' => 'Bookmark already exists']);
        }

        $page = new Grabber($url);

        if ($page->error() != 0) {
            return redirect()->back()->withErrors(['message' => $this->errorMessage()]);
        }

        if ($page->status() != 200) {
            return redirect()->back()->withErrors(['message' => 'Page not available or not exists']);
        }

        $page->fill();
        $fields = $page->fields();
        $fields['password'] = (strlen(trim($password)) > 0 ? bcrypt($password) : null);
        $bookmark = Bookmark::create($fields);

        return redirect('/bookmarks/' . $bookmark->id);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $bookmark = Bookmark::find($id);

        if (!$bookmark) {
            return redirect('/');
        }

        return view('show', ['bookmark' => $bookmark]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Request $request, $id)
    {
        $bookmark = Bookmark::find($id);

        if (!$bookmark) {
            return response()->json(['success' => false, 'message' => 'No bookmark exist']);
        }

        $password = $request->input('password');

        if (!is_null($bookmark->password) && !Hash::check($password, $bookmark->password)) {
            return response()->json(['success' => false, 'message' => 'Wrong password']);
        }

        Bookmark::find($id)->delete();

        return response()->json(['success' => true]);
    }

    public function downloadExcel()
    {
        $bookmarks = Bookmark::select('favicon', 'url', 'title', 'desc', 'keywords')->get();
        $header = ['Favicon', 'Url', 'Title', 'Desc', 'Keywords'];

        $excel = new Excel($bookmarks, $header, 'Bookmarks');
        $excel->download();
    }
}
