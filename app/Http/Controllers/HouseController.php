<?php

namespace App\Http\Controllers;


use App\Models\house;
use App\Models\house_images;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Input\Input;

class HouseController extends Controller
{

    public function index()
    {
        $houses = House::with('house_images')->get();

        return $houses;
    }

    public function store(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'numberOfRooms' => 'required|integer',
            'price' => 'required',
            'address' => 'required|string',
            'phoneNumber' => 'required|string',
            'email' => 'required|string',
            'image' => 'required|image', // Validation for image upload
        ]);

        // Create a new house record
        $house = House::create([
            'name' => $request->input('name'),
            'numberOfRooms' => $request->input('numberOfRooms'),
            'price' => $request->input('price'),
            'address' => $request->input('address'),
            'phoneNumber' => $request->input('phoneNumber'),
            'email' => $request->input('email'),
            'size' => $request->input('size'),
            'description' => $request->input('description'),
        ]);

        // Handle image uploads and store URLs in the house_images table
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('public/images');
            $house_Id = $house->id;
            $Images = house_images::create([
                'house_id' => $house_Id,
                'url' => $imagePath, // Store the generated URL in the database
            ]);
        }


        return response()->json(['message' => 'House added successfully', 'house' => $house, 'images_urls' => $Images]);
    }



    public function show(string $id)
    {
        $house = house::with('house_images')->find($id);
    
        if (!$house) {
            return response()->json(['message' => 'House not found'], 404);
        }
    
        return response()->json(['house' => $house]);
    }
    


    public function update(Request $request, string $id)
    {
        
    }

    public function destroy($id)
    {
        $house = house::with('house_images')->find($id);
        if (!$house) {
            return response()->json(['message' => 'House not found'], 404);
        }
        $house->house_images()->delete();
        $house->delete();
        return response()->json(['message' => 'House and related images deleted']);
    }



    public function search(string $name)
    {

        $houses = house::where('name', 'like', '%' . $name . '%')->get();

        return $houses;
    }


    public function filter(Request $request)
    {
        $name = $request->input('name');
        $sizeMin = $request->input('sizeMin');
        $sizeMax = $request->input('sizeMax');
        $address = $request->input('address');
        $priceMin = $request->input('priceMin');
        $priceMax = $request->input('priceMax');
        $roomsMin = $request->input('roomsMin');
        $roomsMax = $request->input('roomsMax');

        $query = house::query()->with('house_images');

        if ($sizeMin && $sizeMax) {
            $query->whereBetween('size', [$sizeMin, $sizeMax]);
        }

        if ($name) {
            $query->where('name', 'like', '%' . $name . '%');
        }

        if ($address) {
            $query->where('address', 'like', '%' . $address . '%');
        }

        if ($priceMin && $priceMax) {
            $query->whereBetween('priceOfHouse', [$priceMin, $priceMax]);
        }

        if ($roomsMin && $roomsMax) {
            $query->whereBetween('numberOfRooms', [$roomsMin, $roomsMax]);
        }

        $filteredHouses = $query->get();

        return response()->json($filteredHouses);
    }
}
