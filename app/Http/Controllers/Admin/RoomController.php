<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RoomController extends Controller
{
    public const FEATURE_KEYS = [
        'bed_double' => 'Çift Kişilik Yatak',
        'bed_single' => 'Tek Kişilik Yatak',
        'ac' => 'Klima',
        'tv' => 'TV',
        'wifi' => 'Wi-Fi',
        'fridge' => 'Mini Buzdolabı',
        'safe' => 'Kasa',
        'balcony' => 'Balkon',
        'sea_view' => 'Deniz Manzarası',
        'family_friendly' => 'Aile Dostu',
    ];

    public function index(): View
    {
        $rooms = Room::orderBy('sort_order')->get();

        return view('admin.rooms.index', compact('rooms'));
    }

    public function create(): View
    {
        return view('admin.rooms.edit', [
            'room' => new Room(['is_active' => true, 'features' => [], 'availability_note' => 'Müsaitliğe göre']),
            'isNew' => true,
            'features' => self::FEATURE_KEYS,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $room = Room::create($this->validateData($request));

        return redirect()->route('admin.rooms.edit', $room)->with('success', 'Oda eklendi.');
    }

    public function edit(Room $room): View
    {
        return view('admin.rooms.edit', [
            'room' => $room,
            'isNew' => false,
            'features' => self::FEATURE_KEYS,
        ]);
    }

    public function update(Request $request, Room $room): RedirectResponse
    {
        $room->update($this->validateData($request));

        return back()->with('success', 'Oda güncellendi.');
    }

    public function destroy(Room $room): RedirectResponse
    {
        $room->delete();

        return redirect()->route('admin.rooms.index')->with('success', 'Oda silindi.');
    }

    public function uploadImage(Request $request, Room $room): JsonResponse
    {
        $request->validate(['image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096']]);
        $f = $request->file('image');
        $name = Str::slug($room->slug ?: 'room').'-'.time().'-'.Str::random(6).'.'.$f->extension();
        $f->move(public_path('storage/uploads/rooms'), $name);

        return response()->json(['ok' => true, 'filename' => $name]);
    }

    /** @return array<string, mixed> */
    private function validateData(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['required', 'string', 'alpha_dash', 'max:80'],
            'short_description' => ['nullable', 'string', 'max:255'],
            'long_description' => ['nullable', 'string', 'max:5000'],
            'features' => ['nullable', 'array'],
            'features.*' => ['string', 'in:'.implode(',', array_keys(self::FEATURE_KEYS))],
            'availability_note' => ['nullable', 'string', 'max:200'],
            'main_image' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
        ]);
        $data['is_active'] = $request->boolean('is_active');
        $data['features'] = array_values($data['features'] ?? []);

        return $data;
    }
}
