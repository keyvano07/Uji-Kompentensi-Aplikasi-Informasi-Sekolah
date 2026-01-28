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
            $query->where('text', 'like', '%' . $request->search . '%');
        }

        $infos = $query->get();
        return view('infos.index', compact('infos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:255',
        ]);

        Info::create([
            'text' => $request->text,
        ]);

        return back()->with('success', 'Info berhasil ditambahkan.');
    }

    public function update(Request $request, Info $info)
    {
        $request->validate([
            'text' => 'required|string|max:255',
        ]);

        $info->update([
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
