<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminLapanganController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('search');
        $category = $request->input('category');
        $allQueryParams = $request->query();
        $needsRedirect = false;

        if ($category && in_array($category, ['standar', 'internasional'])) {
            if (isset($allQueryParams['standar_page']) || isset($allQueryParams['internasional_page'])) {
                unset($allQueryParams['standar_page']);
                unset($allQueryParams['internasional_page']);
                $needsRedirect = true;
            }

            if ($needsRedirect) {
                return redirect()->route('admin.lapangan.index', $allQueryParams);
            }

            $lapangansQuery = Lapangan::query();

            if ($query) {
                $lapangansQuery->where(function ($q) use ($query) {
                    $q->where('nama_lapangan', 'like', "%{$query}%")
                        ->orWhere('kategori', 'like', "%{$query}%")
                        ->orWhere('jenis_lapangan', 'like', "%{$query}%")
                        ->orWhere('tipe_venue', 'like', "%{$query}%");
                });
            }

            $lapangansQuery->where('kategori', $category);
            $appendsParams = ['search' => $query, 'category' => $category];
            $lapangans = $lapangansQuery->paginate(6, ['*'], 'page')->appends($appendsParams);

            $standarLapangans = null;
            $internasionalLapangans = null;
            $mainPaginator = $lapangans;
            $viewMode = 'single';
        } else {
            $viewMode = 'split';

            $standarQuery = Lapangan::query()->where('kategori', 'standar');
            $internasionalQuery = Lapangan::query()->where('kategori', 'internasional');

            if ($query) {
                $standarQuery->where(function ($q) use ($query) {
                    $q->where('nama_lapangan', 'like', "%{$query}%")
                        ->orWhere('jenis_lapangan', 'like', "%{$query}%")
                        ->orWhere('tipe_venue', 'like', "%{$query}%");
                });

                $internasionalQuery->where(function ($q) use ($query) {
                    $q->where('nama_lapangan', 'like', "%{$query}%")
                        ->orWhere('jenis_lapangan', 'like', "%{$query}%")
                        ->orWhere('tipe_venue', 'like', "%{$query}%");
                });
            }

            $queryParams = ['search' => $query];

            // Use same page name for both to synchronize pagination
            $standarLapangans = $standarQuery->paginate(6, ['*'], 'page')
                ->appends($queryParams);
            $internasionalLapangans = $internasionalQuery->paginate(6, ['*'], 'page')
                ->appends($queryParams);

            // Determine which paginator to use for links (the one with more pages)
            $mainPaginator = $standarLapangans->lastPage() >= $internasionalLapangans->lastPage()
                ? $standarLapangans
                : $internasionalLapangans;

            $lapangans = null;
        }

        return view('admin.lapangan.index', compact(
            'lapangans',
            'standarLapangans',
            'internasionalLapangans',
            'mainPaginator',
            'viewMode'
        ));
    }

    public function create()
    {
        return view('admin.lapangan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lapangan' => 'required|string|max:255',
            'kategori' => 'required|in:standar,internasional',
            'jenis_lapangan' => 'required|in:sintetis,vinyl',
            'tipe_venue' => 'required|in:indoor,outdoor',
            'image' => 'nullable|file|mimes:jpg,jpeg,png',
        ], [
            'image.mimes' => 'File harus berformat JPG, JPEG, atau PNG.',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('lapangan', 'public');
        }

        Lapangan::create($validated);

        return redirect()->route('admin.lapangan.index')->with('success', 'Lapangan created successfully.');
    }

    public function show(Lapangan $lapangan)
    {
        return view('admin.lapangan.show', compact('lapangan'));
    }

    public function edit(Lapangan $lapangan)
    {
        return view('admin.lapangan.edit', compact('lapangan'));
    }

    public function update(Request $request, Lapangan $lapangan)
    {
        $validated = $request->validate([
            'nama_lapangan' => 'required|string|max:255',
            'kategori' => 'required|in:standar,internasional',
            'jenis_lapangan' => 'required|in:sintetis,vinyl',
            'tipe_venue' => 'required|in:indoor,outdoor',
            'image' => 'nullable|file|mimes:jpg,jpeg,png',
        ], [
            'image.mimes' => 'File harus berformat JPG, JPEG, atau PNG.',
        ]);

        if ($request->hasFile('image')) {
            if ($lapangan->image) {
                Storage::disk('public')->delete($lapangan->image);
            }
            $validated['image'] = $request->file('image')->store('lapangan', 'public');
        }

        $lapangan->update($validated);

        return redirect()->route('admin.lapangan.index')->with('success', 'Lapangan updated successfully.');
    }

    public function destroy(Lapangan $lapangan)
    {
        if ($lapangan->image) {
            Storage::disk('public')->delete($lapangan->image);
        }
        $lapangan->delete();

        return redirect()->route('admin.lapangan.index')->with('success', 'Lapangan deleted successfully.');
    }
}
