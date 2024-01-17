<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Group;
// Create a new Goutte client
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

class extractController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $itemsPerPage = 16;
        $page = $request->input('page', 1);
        $offset = ($page - 1) * $itemsPerPage;

        $groups = DB::table('groups')
            ->select('id', 'name', 'link', 'imageUrl')
            ->orderBy('id', 'desc')
            ->offset($offset)
            ->limit($itemsPerPage)
            ->get();

        return response()->json($groups);
        // $itemsPerPage = 16;
        // $page = $request->input('page', 1);
        // $offset = ($page - 1) * $itemsPerPage;

        // $groups = Group::select('id','name', 'link','imageUrl')
        //     ->offset($offset)
        //     ->limit($itemsPerPage)
        //     ->get();

        // return response()->json($groups);
    }


    public function fetchUrl(Request $request)
    {
        $link = $request->input('url');
        $response = Http::get($link);
        $html = $response->body();
        $crawler = new Crawler($html);
        $imageElement = $crawler->filter('img._9vx6')->first();
        $imageUrl = $imageElement->attr('src');
        return response()->json($imageUrl);
    }

    public function addGroup(Request $request)
    {
        $link = $request->input('link');
        $name = $request->input('name');
        $imageUrl = $request->input('imageUrl');

        // Fetch the last 50 records based on the link column
            $existingRecords = DB::table('groups')
            ->orderByDesc('id')
            ->take(5)
            ->pluck('link')
            ->toArray();

            // Check if the provided link does not exist in the last 50 records
            if (!in_array($link, $existingRecords)) {
                // Insert a new record if the link is not found
                DB::table('groups')->insert([
                    'name' => $name,
                    'link' => $link,
                    'imageUrl' => $imageUrl,
                ]);

                return response()->json(['message' => 'Item added successfully']);
            } else {
                return response()->json(['message' => 'Link already exists in the last 50 records']);
            }


        // DB::table('groups')->insert([
        //     'name' => $name,
        //     'link' => $link,
        //     'imageUrl' => $imageUrl,
        // ]);
    
        // return response()->json(['message' => 'Item added successfully']);
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $group = DB::table('groups')->where('id', $id)->first();

        if (!$group) {
            return response()->json(['message' => 'Group not found'], 404);
        }

        DB::table('groups')->where('id', $id)->delete();

        return response()->json(['message' => 'Group deleted successfully']);
    }
}
