<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['role', 'department'])
            ->orderBy('name')
            ->get();
            
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        $departments = Department::active()->get();
        
        return view('users.create', compact('roles', 'departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'department_id' => 'nullable|exists:departments,id',
            'status' => 'required|in:active,inactive',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        
        $user = User::create($validated);
        
        ActivityLog::log('created', "Created user: {$user->name}", $user);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil ditambahkan!');
    }

    public function show(User $user)
    {
        $user->load(['role', 'department', 'tasks', 'evaluations', 'programs']);
        
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $departments = Department::active()->get();
        
        return view('users.edit', compact('user', 'roles', 'departments'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'department_id' => 'nullable|exists:departments,id',
            'status' => 'required|in:active,inactive',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);
        
        ActivityLog::log('updated', "Updated user: {$user->name}", $user);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil diupdate!');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri!');
        }

        $name = $user->name;
        
        ActivityLog::log('deleted', "Deleted user: {$name}", $user);
        
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', "User {$name} berhasil dihapus!");
    }

    /**
     * Show import form with CSV format guide
     */
    public function importForm()
    {
        $departments = Department::active()->get();
        $roles = Role::all();
        
        return view('users.import', compact('departments', 'roles'));
    }

    /**
     * Process CSV import
     */
    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getPathname(), 'r');
        
        // Skip header row
        $header = fgetcsv($handle);
        
        $results = [
            'success' => [],
            'errors' => [],
        ];
        
        $rowNumber = 1;
        
        while (($row = fgetcsv($handle)) !== false) {
            $rowNumber++;
            
            // Map CSV columns
            $data = [
                'name' => trim($row[0] ?? ''),
                'email' => trim($row[1] ?? ''),
                'password' => trim($row[2] ?? ''),
                'role' => strtolower(trim($row[3] ?? '')),
                'department' => trim($row[4] ?? ''),
            ];
            
            // Validate required fields
            if (empty($data['name']) || empty($data['email']) || empty($data['password']) || empty($data['role'])) {
                $results['errors'][] = [
                    'row' => $rowNumber,
                    'data' => $data['name'] ?: $data['email'] ?: "Row {$rowNumber}",
                    'message' => 'Kolom wajib tidak lengkap (name, email, password, role)',
                ];
                continue;
            }
            
            // Check email unique
            if (User::where('email', $data['email'])->exists()) {
                $results['errors'][] = [
                    'row' => $rowNumber,
                    'data' => $data['email'],
                    'message' => 'Email sudah terdaftar',
                ];
                continue;
            }
            
            // Validate email format
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $results['errors'][] = [
                    'row' => $rowNumber,
                    'data' => $data['email'],
                    'message' => 'Format email tidak valid',
                ];
                continue;
            }
            
            // Password length
            if (strlen($data['password']) < 6) {
                $results['errors'][] = [
                    'row' => $rowNumber,
                    'data' => $data['name'],
                    'message' => 'Password minimal 6 karakter',
                ];
                continue;
            }
            
            // Find role
            $role = Role::where('slug', $data['role'])->first();
            if (!$role) {
                $results['errors'][] = [
                    'row' => $rowNumber,
                    'data' => $data['name'],
                    'message' => "Role '{$data['role']}' tidak ditemukan (gunakan: admin, bph, kabinet, staff)",
                ];
                continue;
            }
            
            // Find department (optional)
            $departmentId = null;
            if (!empty($data['department'])) {
                $department = Department::where('name', 'LIKE', "%{$data['department']}%")->first();
                if ($department) {
                    $departmentId = $department->id;
                }
            }
            
            // Create user
            try {
                $user = User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => Hash::make($data['password']),
                    'role_id' => $role->id,
                    'department_id' => $departmentId,
                    'status' => 'active',
                ]);
                
                $results['success'][] = [
                    'row' => $rowNumber,
                    'data' => $user->name,
                    'message' => "User berhasil ditambahkan",
                ];
                
                ActivityLog::log('created', "Imported user: {$user->name}", $user);
                
            } catch (\Exception $e) {
                $results['errors'][] = [
                    'row' => $rowNumber,
                    'data' => $data['name'],
                    'message' => 'Gagal menyimpan: ' . $e->getMessage(),
                ];
            }
        }
        
        fclose($handle);
        
        return redirect()->route('users.import')
            ->with('import_results', $results)
            ->with('success', count($results['success']) . ' user berhasil diimport, ' . count($results['errors']) . ' gagal');
    }

    /**
     * Download CSV template
     */
    public function downloadTemplate()
    {
        $content = "name,email,password,role,department\n";
        $content .= "John Doe,john@example.com,password123,staff,Divisi IT\n";
        $content .= "Jane Doe,jane@example.com,password456,kabinet,Divisi Humas\n";
        $content .= "Admin User,admin@example.com,admin123,admin,\n";
        
        return response($content)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="user_import_template.csv"');
    }
}
