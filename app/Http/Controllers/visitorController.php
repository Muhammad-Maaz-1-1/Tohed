<?php

namespace App\Http\Controllers;

use App\Models\masjidModel;
use Illuminate\Http\Request;
use Faker\Factory as Faker;
use App\Notifications\FormSubmittedNotification;
use Illuminate\Support\Facades\Notification;
class visitorController extends Controller
{


    
    //
    public function index()
    {
        return view('visitors.masjid_form');
    }
    public function masjid()
    {
        $masjidList = masjidModel::where('status', true)->get();
        return view('visitors.masjid', compact('masjidList'));
    }
    public function masjidDetail($id)
    {
        $pageId = $id;
        $masjidModel= masjidModel::where('id',$id)->first();
        $relatedProducts = masjidModel::where('city', $masjidModel->city)
                             ->where('id', '!=', $masjidModel->id) // Exclude the current masjid
                             ->limit(3) // Limit the number of related products to 3
                             ->get();
        if(!$masjidModel){
            return redirect('/');
        }
        return view('visitors.masjid_detail',compact('masjidModel','relatedProducts'));
    }
    public function masjidFormSubmit(Request $request)
    {
        $masjid = new masjidModel;
        $masjid->name = $request->name;
        $masjid->masjid_name = $request->masjid_name;
        $masjid->masjid_address = $request->masjid_address;
        $masjid->city = $request->city;
        $masjid->country = $request->country;
        $masjid->imam_name = $request->imam_name;
        $masjid->khateeb_name = $request->khateeb_name;
        $masjid->contact_number = $request->contact_number;
        $image_paths = []; // Initialize an array to store image paths
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $image_path = rand(0, 9999) . time() . '.' . $image->getClientOriginalName();
                $image->move(public_path('uploads'), $image_path);
                $image_paths[] = $image_path; // Append image path to the array
            }
            // Convert the array of image paths to a string (e.g., using implode) if needed
            $masjid->images = implode(',', $image_paths);
        }
        $masjid->save();
        // Notification::route('mail', 'maaz.sohail.ostech@gmail.com')->notify(new FormSubmittedNotification($masjid));
        return redirect()->route('masjid_list');
    }
    public function records(){
        // $faker = Faker::create();
        $faker = Faker::create('ur_PK'); // Set Faker locale to Urdu (Pakistan)
        $recordsToAdd = 20;

        for ($i = 0; $i < $recordsToAdd; $i++) {
            masjidModel::create([
                'name' => $faker->name,
                'masjid_name' => $faker->company,
                'masjid_address' => $faker->address,
                'images' => $faker->imageUrl(),
                'city' => $faker->city,
                'country' => $faker->country,
                'imam_name' => $faker->name,
                'khateeb_name' => $faker->name,
                'contact_number' => $faker->phoneNumber,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return response()->json(['message' => 'Records added successfully']);
    }
    public function fetchMasjid(Request $request)
    {
        $country = $request->input('country');
        $city = $request->input('city');
        
        $query = masjidModel::where('status', true);
    
        if ($country) {
            $query->where('country', $country);
        }
    
        if ($city) {
            $query->where('city', $city);
        }
    
        $masjidList = $query->get();
    
        // Load images for each masjid (assuming 'images' is a comma-separated string)
        foreach ($masjidList as $masjid) {
            $imagePaths = explode(',', $masjid->images);
            $masjid->firstImage = $imagePaths[0]; // Store the first image path in the object
        }
    
        $baseUrl = url('/');
    
        return response()->json(['masjidList' => $masjidList, 'baseUrl' => $baseUrl]);
    }
    
    
    
}
