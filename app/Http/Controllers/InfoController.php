<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Info;

class InfoController extends Controller
{
    public function index(Request $request)
    {
        $query = Info::latest();

        if ($request->has('search')) {
            $query->where('text', 'like', '%' . $request->search . '%')
                  ->orWhere('judul', 'like', '%' . $request->search . '%');
        }

        $infos = $query->get();
        return view('infos.index', compact('infos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'tipe' => 'required|in:info,pengumuman,berita',
            'text' => 'required|string',
        ]);

        Info::create([
            'judul' => $request->judul,
            'tipe' => $request->tipe,
            'text' => $request->text,
        ]);

        return back()->with('success', 'Info berhasil ditambahkan.');
    }

    public function update(Request $request, Info $info)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'tipe' => 'required|in:info,pengumuman,berita',
            'text' => 'required|string',
        ]);

        $info->update([
            'judul' => $request->judul,
            'tipe' => $request->tipe,
            'text' => $request->text,
        ]);

        return back()->with('success', 'Info berhasil diperbarui.');
    }

    public function destroy(Info $info)
    {
        $info->delete();

        return back()->with('success', 'Info berhasil dihapus.');
    }
}
