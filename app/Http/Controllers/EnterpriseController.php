<?php

namespace App\Http\Controllers;

use App\Models\Enterprise;
use Illuminate\Http\Request;

class EnterpriseController extends Controller
{
    /**
     * Display a listing of enterprises.
     */
    public function index(Request $request)
    {
        $search = $request->query('q');

        $query = Enterprise::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('address', 'like', '%' . $search . '%');
            });
        }

        // Paginate by 9 for a beautiful grid
        $enterprises = $query->orderBy('name', 'asc')->paginate(9);

        return view('enterprises.index', compact('enterprises', 'search'));
    }

    /**
     * Display the specified enterprise details.
     */
    public function show($slug)
    {
        $enterprise = Enterprise::where('slug', $slug)->firstOrFail();

        // Get local projects developed by this enterprise
        // Load owner/agent info as well for the property cards
        $properties = $enterprise->properties()->with('owner')->get()->map(function($p) {
            // Normalize so it matches the card format in properties grid
            return [
                'id' => $p->id + 1000,
                'title' => $p->title,
                'slug' => $p->slug,
                'featureimg' => $p->feature_img,
                'gallery' => is_array($p->images) ? $p->images : (json_decode($p->images, true) ?: [$p->feature_img]),
                'geolocation' => $p->geolocation,
                'price' => $p->price,
                'rentprice' => $p->price,
                'total_area' => $p->total_area,
                'floors' => $p->floors,
                'rstype' => $p->rstype,
                'bed' => $p->bed,
                'bath' => $p->bath,
                'province' => 'Thành phố Hồ Chí Minh',
                'address' => $p->address,
                'phone' => $p->owner->phone ?? '0932030958',
                'email' => $p->owner->email ?? 'nks.diaocchinhchu@nks.vn',
                'sale' => [
                    'id' => $p->user_id,
                    'name' => $p->owner->name ?? 'Chủ nhà',
                    'avatar' => $p->owner->avatar ?? 'https://api.dicebear.com/7.x/adventurer/svg?seed=nks',
                    'phone' => $p->owner->phone ?? '0932030958',
                    'email' => $p->owner->email ?? 'nks.diaocchinhchu@nks.vn'
                ],
                'formatedPrice' => $p->formated_price,
                'formatedSqrPrice' => $p->total_area > 0 ? (number_format($p->price / $p->total_area / 1000, 0) . 'k/m²') : '',
                'transaction_type' => $p->transaction_type
            ];
        });

        return view('enterprises.show', compact('enterprise', 'properties'));
    }
}
