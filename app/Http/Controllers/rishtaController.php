<?php

namespace App\Http\Controllers;

use App\Models\rishtaModel;
use Illuminate\Http\Request;

class rishtaController extends Controller
{
    //
    public function rishtaForm(){
        return view('visitors.rishta_form');

    }
    public function rishtaFormSubmit(Request $request)
    {
        $rishta = new rishtaModel();
        $rishta->full_name = $request->full_name;
        $rishta->gender = $request->gender;
        $rishta->birthdate = $request->birthdate;
        $rishta->marital_status = $request->marital_status;
        $rishta->city = $request->city;
        $rishta->country = $request->country;
        $rishta->mother_tongue = $request->mother_tongue;
        $rishta->education = $request->education;
        $rishta->profession = $request->profession;
        $rishta->ethnicity = $request->ethnicity;
        $image_paths = []; // Initialize an array to store image paths
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $image_path = rand(0, 9999) . time() . '.' . $image->getClientOriginalName();
                $image->move(public_path('uploads'), $image_path);
                $image_paths[] = $image_path; // Append image path to the array
            }
            // Convert the array of image paths to a string (e.g., using implode) if needed
            $rishta->images = implode(',', $image_paths);
        }
        $rishta->save();
        // Notification::route('mail', 'maaz.sohail.ostech@gmail.com')->notify(new FormSubmittedNotification($rishta));
        return redirect()->route('rishta_list');
    }
    public function rishtaList(){
        $rishta = rishtaModel::where('status', true)->get();
        return view('visitors.rishta', compact('rishta'));
    }

    public function rishtaDetail($id)
    {
        $pageId = $id;
        $rishtaModel= rishtaModel::where('id',$id)->first();
        $relatedProducts = rishtaModel::where('city', $rishtaModel->city)
                             ->where('id', '!=', $rishtaModel->id) // Exclude the current rishta
                             ->limit(3) // Limit the number of related products to 3
                             ->get();
        if(!$rishtaModel){
            return redirect('/');
        }
        return view('visitors.rishta_detail',compact('rishtaModel','relatedProducts'));
    }

    public function fetchRishtaMasjid(Request $request)
    {
        $country = $request->input('country');
        $city = $request->input('city');
    
        $query = RishtaModel::where('status', true);
    
        if ($country) {
            $query->where('country', $country);
        }
    
        if ($city) {
            $query->where('city', $city);
        }
    
        $rishtaList = $query->get();
    
        // Load images for each rishta (assuming 'images' is a comma-separated string)
        foreach ($rishtaList as $rishta) {
            $imagePaths = explode(',', $rishta->images);
            $rishta->firstImage = $imagePaths[0]; // Store the first image path in the object
        }
    
        $baseUrl = url('/');
    
        return response()->json(['rishtaList' => $rishtaList, 'baseUrl' => $baseUrl]);
    }
    

}
