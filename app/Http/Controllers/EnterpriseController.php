<?php

namespace App\Http\Controllers;

use App\Models\Enterprise;
use Illuminate\Http\Request;
use App\Http\Controllers\PropertyController;

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

        // Fetch all API properties to calculate count dynamically without N+1 query problems
        try {
            $allProperties = (new PropertyController())->fetchAllItems();
            foreach ($enterprises as $ent) {
                $matched = $this->getPropertiesForEnterprise($ent, $allProperties);
                $ent->api_properties_count = count($matched);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to count API properties for enterprises: ' . $e->getMessage());
            foreach ($enterprises as $ent) {
                $ent->api_properties_count = 0;
            }
        }

        return view('enterprises.index', compact('enterprises', 'search'));
    }

    /**
     * Display the specified enterprise details.
     */
    public function show($slug)
    {
        $enterprise = Enterprise::where('slug', $slug)->firstOrFail();

        // Fetch all properties (API and database)
        $allProperties = (new PropertyController())->fetchAllItems();

        // Filter and get properties for this enterprise
        $matched = $this->getPropertiesForEnterprise($enterprise, $allProperties);

        // Convert to Collection so view methods like count() and isEmpty() work perfectly
        $properties = collect($matched);

        return view('enterprises.show', compact('enterprise', 'properties'));
    }

    /**
     * Get properties associated with an enterprise from the API/DB list.
     */
    protected function getPropertiesForEnterprise($enterprise, array $allProperties)
    {
        $matched = [];
        
        // Define keywords for slug matching
        $keywords = [];
        if ($enterprise->slug === 'vinhomes') {
            $keywords = ['vinhomes', 'landmark', 'grand park', 'central park', 'ocean park', 'golden river', 'time city', 'royal city'];
        } elseif ($enterprise->slug === 'novaland') {
            $keywords = ['novaland', 'novaworld', 'sunrise', 'aqua city', 'lexington', 'sun avenue', 'lakeview', 'tropic garden'];
        } elseif ($enterprise->slug === 'dat-xanh') {
            $keywords = ['đất xanh', 'dat xanh', 'gem sky', 'opal', 'luxgarden', 'sunview', 'luxcity'];
        } elseif ($enterprise->slug === 'nam-long') {
            $keywords = ['nam-long', 'nam long', 'mizuki', 'akari', 'waterpoint', 'ehome', 'flora'];
        } elseif ($enterprise->slug === 'dai-duong-group') {
            $keywords = ['đại dương', 'dai duong', 'ocean'];
        }

        // 1. First pass: Keyword match
        if (!empty($keywords)) {
            foreach ($allProperties as $item) {
                $text = strtolower(($item['title'] ?? '') . ' ' . ($item['address'] ?? '') . ' ' . ($item['description'] ?? ''));
                foreach ($keywords as $kw) {
                    if (str_contains($text, $kw)) {
                        $matched[] = $item;
                        break; // Avoid duplicate matching of the same property
                    }
                }
            }
        }

        // 2. Second pass: If matched items is less than 6, fill up using deterministic modulo fallback
        if (count($matched) < 6) {
            $remainder = 0;
            if ($enterprise->slug === 'vinhomes') $remainder = 0;
            elseif ($enterprise->slug === 'novaland') $remainder = 1;
            elseif ($enterprise->slug === 'dat-xanh') $remainder = 2;
            elseif ($enterprise->slug === 'nam-long') $remainder = 3;
            elseif ($enterprise->slug === 'dai-duong-group') $remainder = 4;
            
            // Collect matching IDs to avoid duplication
            $existingIds = array_column($matched, 'id');
            
            foreach ($allProperties as $item) {
                if (count($matched) >= 6) {
                    break;
                }
                $itemId = $item['id'] ?? 0;
                if (!in_array($itemId, $existingIds)) {
                    if ($itemId % 5 === $remainder) {
                        $matched[] = $item;
                    }
                }
            }
        }

        return $matched;
    }
}
