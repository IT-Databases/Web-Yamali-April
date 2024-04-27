<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Perpustakaan;
use Illuminate\Http\Request;

use function Laravel\Prompts\search;

class PerpusController extends Controller
{
    public function index(Request $request) {
        $status = 'perpustakaan';
        $perpustakaans = Perpustakaan::orderBy('created_at', 'desc');

        // Check if tags are present in the request
        if ($request->has('tags')) {
            $tags = is_array($request->tags) ? $request->tags : explode(',', $request->tags);
            $perpustakaans->where(function ($query) use ($tags) {
                foreach ($tags as $tag) {
                    $query->orWhere('tag', 'like', '%' . $tag . '%');
                }
            });
        }

        $perpustakaans = $perpustakaans->paginate(8);

        return view('perpustakaan', compact('perpustakaans', 'status'));
    }

    public function detail($slug){
        $status ='perpustakaan';
        $perpustakaan = Perpustakaan::where('slug', $slug)->first();
        $perpustakaanRandom = Perpustakaan::inRandomOrder()->take(5)->get();
        return view ('perpustakaan-detail', compact('perpustakaan','status','perpustakaanRandom'));
    }

    public function cari(Request $request){
        $perpustakaans = Perpustakaan::where('judul','LIKE','%'.$request->search.'%')->paginate(8);
        return view ('perpustakaan', compact('perpustakaans'));
    }
}
