<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;

class ManageUserController extends Controller
{
    // Data user awal (Default)
    private $initialUsers = [
        ['id_pelanggan' => 1, 'username' => 'admin', 'email' => 'admin@maggot.com', 'role' => 'admin', 'foto_profil' => 'admin.jpg', 'password' => 'pass123'],
        ['id_pelanggan' => 2, 'username' => 'customer_a', 'email' => 'a@mail.com', 'role' => 'customer', 'foto_profil' => 'user-a.jpg', 'password' => 'pass123'],
        ['id_pelanggan' => 3, 'username' => 'customer_b', 'email' => 'b@mail.com', 'role' => 'customer', 'foto_profil' => '', 'password' => 'pass123'],
    ];

    private function getUsers()
    {
        if (!Session::has('users')) {
            Session::put('users', $this->initialUsers);
        }
        return Session::get('users');
    }

    private function saveUsers($users)
    {
        // Penting: array_values() mereset kunci numerik setelah delete/filter
        Session::put('users', array_values($users));
    }
    
    private function getNextId($users)
    {
        if (empty($users)) {
            return 1;
        }
        return max(array_column($users, 'id_pelanggan')) + 1;
    }
    
    /**
     * Mendapatkan daftar semua nama file gambar dari public/images.
     */
    private function getAvailableImages()
    {
        $imagesPath = public_path('images');
        $imageFiles = [];

        if (File::isDirectory($imagesPath)) {
            $files = File::files($imagesPath); 
            foreach ($files as $file) {
                $imageFiles[] = $file->getFilename(); 
            }
        }
        
        // Tambahkan opsi kosong/default sebagai elemen pertama
        array_unshift($imageFiles, ''); 
        
        return array_unique($imageFiles);
    }

    
    public function index()
    {
        $users = $this->getUsers();
        return view('manage-user.index', compact('users'));
    }

   
    public function create()
    {
        $availableImages = $this->getAvailableImages();
        return view('manage-user.create', compact('availableImages'));
    }

   
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,customer',
            'foto_profil' => 'nullable|string|max:255',
        ]);

        $users = $this->getUsers();
        $newId = $this->getNextId($users);
        
        $newUser = [
            'id_pelanggan' => $newId,
            'username' => $validated['username'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'foto_profil' => $validated['foto_profil'] ?? '', 
            'password' => $validated['password'],
        ];

        $users[] = $newUser;
        $this->saveUsers($users);

        return redirect()->route('user.index')->with('status_message', 'User **' . $validated['username'] . '** berhasil ditambahkan!');
    }

   
    public function edit($id)
    {
        $id = (int) $id;
        $users = $this->getUsers();
        
        $user = collect($users)->firstWhere('id_pelanggan', $id);

        if (!$user) {
            return redirect()->route('user.index')->withErrors('User tidak ditemukan.');
        }

        $availableImages = $this->getAvailableImages();
        return view('manage-user.edit', compact('user', 'availableImages'));
    }

    
    public function update(Request $request, $id)
    {
        $id = (int) $id;
        
        $validated = $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'nullable|string|min:6', 
            'role' => 'required|in:admin,customer',
            'foto_profil' => 'nullable|string|max:255', 
        ]);

        $users = $this->getUsers();
        $indexToUpdate = -1;

        foreach ($users as $index => $u) {
            if ($u['id_pelanggan'] === $id) {
                $indexToUpdate = $index;
                break;
            }
        }

        if ($indexToUpdate === -1) {
            return redirect()->route('user.index')->withErrors('User yang akan diperbarui tidak ditemukan.');
        }

        $users[$indexToUpdate]['username'] = $validated['username'];
        $users[$indexToUpdate]['email'] = $validated['email'];
        $users[$indexToUpdate]['role'] = $validated['role'];
        
        if (!empty($validated['password'])) {
            $users[$indexToUpdate]['password'] = $validated['password']; 
        }
        
        $users[$indexToUpdate]['foto_profil'] = $validated['foto_profil'] ?? $users[$indexToUpdate]['foto_profil']; 

        $this->saveUsers($users);

        return redirect()->route('user.index')->with('status_message', 'User **' . $validated['username'] . '** berhasil diperbarui!');
    }

    
    public function destroy($id)
    {
        $id = (int) $id;
        $users = $this->getUsers();
        
        $initialCount = count($users);
        
        $userToDelete = collect($users)->firstWhere('id_pelanggan', $id);
        $username = $userToDelete ? $userToDelete['username'] : 'ID ' . $id;

        $users = array_filter($users, fn($u) => $u['id_pelanggan'] !== $id);
        $finalCount = count($users);
        
        // Validasi keberadaan data
        if ($initialCount === $finalCount) {
            return redirect()->route('user.index')->withErrors('User **' . $username . '** tidak ditemukan dan gagal dihapus.');
        }

        $this->saveUsers($users); 
        
        return redirect()->route('user.index')->with('status_message', 'User **' . $username . '** berhasil dihapus!');
    }
}