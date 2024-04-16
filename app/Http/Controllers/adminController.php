<?php

namespace App\Http\Controllers;

use App\Models\masjidModel;
use App\Models\rishtaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class adminController extends Controller
{
    public function index()
    {
        $masjidApproved = masjidModel::where('status', true)->get();
        $masjidModelPending = masjidModel::where('status', false)->get();
        return view('admin.index', compact('masjidApproved', 'masjidModelPending'));
    }
    public function login()
    {
        return view('admin.login');
    }
    public function loginSubmit(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }


    public function masjid()
    {
        $masjid = masjidModel::get();
        return view('admin.masjid', compact('masjid'));
    }
    public function approvedListings()
    {
        $masjid = masjidModel::where('status', true)->get();
        return view('admin.approved_listings', compact('masjid'));
    }
    public function pendingListings()
    {
        $masjid = masjidModel::where('status', false)->get();
        return view('admin.pending_listings', compact('masjid'));
    }
    public function masjidForm()
    {
        return view('admin.masjid_form');
    }
    public function approval($id)
    {
        $formId = $id;
        $masjid = masjidModel::where('id', $formId)->first();
        if ($masjid) {
            // Update the status to true
            $masjid->status = true;
            $masjid->update(); // Save the changes to the database
        }
        return redirect()->back();
    }
    public function delete($id)
    {
        $formId = $id;
        $masjid = MasjidModel::where('id', $formId)->delete(); // Corrected model name to match the convention
        return redirect()->back();
    }


    public function edit($id)
    {
        $formId = $id;
        $masjid = masjidModel::where('id', $formId)->first();

        return view('admin.masjid_edit', compact('masjid'));
    }

    public function update(Request $request)
    {
        $masjid = masjidModel::where('id', $request->id)->first();
        $masjid->name = $request->name;
        $masjid->masjid_name = $request->masjid_name;
        $masjid->masjid_address = $request->masjid_address;
        $masjid->city = $request->city;
        $masjid->country = $request->country;
        $masjid->imam_name = $request->imam_name;
        $masjid->khateeb_name = $request->khateeb_name;
        $masjid->contact_number = $request->contact_number;
        $masjid->content = $request->content;

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
        return redirect()->back();
    }

    public function rishta()
    {
        $rishta = rishtaModel::get();
        return view('admin.rishta', compact('rishta'));
    }
    public function rishtaPending()
    {
        $rishta = rishtaModel::where('status', false)->get();

        return view('admin.rishta_pending', compact('rishta'));
    }
    public function rishtaApproved()
    {
        $rishta = rishtaModel::where('status', true)->get();
        return view('admin.rishta_approved', compact('rishta'));
    }
    public function rishtaForm()
    {
        return view('admin.rishta_form');
    }
    public function rishtaEdit($id)
    {
        $formId = $id;
        $rishta = rishtaModel::where('id', $formId)->first();

        return view('admin.rishta_edit', compact('rishta'));
    }
    public function rishtaApproval($id)
    {
        $formId = $id;
        $rishta = rishtaModel::where('id', $formId)->first();
        if ($rishta) {
            // Update the status to true
            $rishta->status = true;
            $rishta->update(); // Save the changes to the database
        }
        return redirect()->back();
    }
    public function rishtaDelete($id)
    {
        $formId = $id;
        $rishta = rishtaModel::where('id', $formId)->delete(); // Corrected model name to match the convention
        return redirect()->back();
    }
    public function rishtaUpdate(Request $request)
    {
        $rishta = rishtaModel::where('id', $request->id)->first();
        $rishta->full_name = $request->full_name;
        $rishta->gender = $request->gender;
        $rishta->birthdate = $request->birthdate;
        $rishta->marital_status = $request->marital_status;
        $rishta->city = $request->city;
        $rishta->country = $request->country;
        $rishta->mother_tongue = $request->mother_tongue;
        $rishta->education = $request->education;
        $rishta->profession = $request->profession;
        $rishta->content = $request->content;
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
        return redirect()->back();
    }
}
