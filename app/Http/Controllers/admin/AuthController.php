<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;


class AuthController extends Controller
{
    public function login()
    {
        return view('backend.pages.auth.login');
    }
    public function loginAll(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Perform login logic here
        $remember = $request->filled('remember');
        if (Auth::attempt($request->only('email', 'password'), $remember)) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        return redirect()->back()->withErrors(['Invalid credentials']);
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    // Profile view
    public function profile()
    {
        return view('backend.pages.profile.index');
    }

    // Profile update
    public function profileUpdate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'password' => 'nullable|min:6',
            'phone' => 'required|string|max:15',
            'address' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'passport_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'trc_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'study_confirmation_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user = Auth::user();

        // Call the private method for each image field
        $user->image = $this->handleImageUpload($request, 'image', $user->image);
        $user->passport_image = $this->handleImageUpload($request, 'passport_image', $user->passport_image);
        $user->trc_image = $this->handleImageUpload($request, 'trc_image', $user->trc_image);
        $user->study_confirmation_image = $this->handleImageUpload($request, 'study_confirmation_image', $user->study_confirmation_image);

        // Update other user data
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }
        $user->phone = $request->phone;
        $user->address = $request->address;

        $user->save();

        return redirect()->route('profile')->with('success', 'Profile updated successfully.');
    }
    private function handleImageUpload(Request $request, string $fileFieldName, ?string $currentImagePath): ?string
    {
        // Check if a new file exists for the given field
        if ($request->hasFile($fileFieldName)) {
            // Delete the old file if it exists
            if ($currentImagePath) {
                $oldPath = public_path($currentImagePath);
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }

            // Upload the new file
            $file = $request->file($fileFieldName);
            $fileName = time() . '_' . $fileFieldName . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/image'), $fileName);

            // Return the new file path to be saved in the database
            return 'uploads/image/' . $fileName;
        }

        // If no new file is uploaded, return the existing path
        return $currentImagePath;
    }
    // remember

}
