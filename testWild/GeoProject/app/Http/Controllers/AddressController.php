<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\Adress;
use Illuminate\Support\Facades\Log;

class AddressController extends Controller
{
    public function firstP()
    {
        $address = Adress::paginate(15);
        $coord = [];
        foreach ($address as &$value) { 
            $response = Http::get('https://nominatim.openstreetmap.org/search?q=' . $value->address . '&format=json&limit=1'); 
            $data = json_decode($response->body(), true);
            if (array_key_exists("0",$data)) {

                $data1 = $data[0];
                
                $value->latitude = $data1["lat"];
                $value->longitude = $data1["lon"];
                $value->save();
                $coord[] = array("display_name" => $data1["display_name"], "lat" => $data1["lat"], "lon" => $data1["lon"]);
             }
            
            }
            
        
            return view('welcome', compact('address', 'coord'));
            
        }
        // add
        public function store(Request $coord)
        {
            $coord->validate([
                "latitude" => "required",
                "longitude" => "required",
                
            ]);
        }
    }