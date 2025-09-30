<?php

namespace App\Http\Controllers\Admin;

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

    public function profile()
    {
        return view('backend.pages.profile.index');
    }

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

        // Image update
        $user->image = $this->handleImageUpload($request, 'image', $user->image);
        $user->passport_image = $this->handleImageUpload($request, 'passport_image', $user->passport_image);
        $user->trc_image = $this->handleImageUpload($request, 'trc_image', $user->trc_image);
        $user->study_confirmation_image = $this->handleImageUpload($request, 'study_confirmation_image', $user->study_confirmation_image);

        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }
        $user->phone = $request->phone;
        $user->address = $request->address;

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'প্রোফাইল সফলভাবে আপডেট হয়েছে!',
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'address' => $user->address,
                'image' => $user->image ? asset($user->image) : null,
                'passport_image' => $user->passport_image ? asset($user->passport_image) : null,
                'trc_image' => $user->trc_image ? asset($user->trc_image) : null,
                'study_confirmation_image' => $user->study_confirmation_image ? asset($user->study_confirmation_image) : null,
            ]
        ]);
    }

    private function handleImageUpload(Request $request, string $fileFieldName, ?string $currentImagePath): ?string
    {
        if ($request->hasFile($fileFieldName)) {
            if ($currentImagePath) {
                $oldPath = public_path($currentImagePath);
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }

            $file = $request->file($fileFieldName);
            $fileName = time() . '_' . $fileFieldName . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/image'), $fileName);

            return 'uploads/image/' . $fileName;
        }

        return $currentImagePath;
    }
}