<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\Department;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    // Hiển thị danh sách staff
    public function index()
    {
        $staff = Staff::with('department')->paginate(10); // Phân trang 10 bản ghi
        return view('staff.index', compact('staff'));
    }

    // Hiển thị form tạo staff
    public function create()
    {
        $departments = Department::all(); // Lấy danh sách tất cả đơn vị
        return view('staff.create', compact('departments'));
    }

    // Lưu staff mới
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:staff,email',
            'phone' => 'nullable|string|max:15',
            'position' => 'nullable|string|max:255',
            'department_id' => 'required|exists:departments,id',
        ]);

        Staff::create($request->all());

        return redirect()->route('staff.index')->with('success', 'Staff added successfully.');
    }

    // Hiển thị form chỉnh sửa staff
    public function edit($id)
    {
        $staff = Staff::findOrFail($id);
        $departments = Department::all(); // Lấy danh sách tất cả đơn vị
        return view('staff.edit', compact('staff', 'departments'));
    }

    // Cập nhật thông tin staff
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|email|unique:staff,email,$id",
            'phone' => 'nullable|string|max:15',
            'position' => 'nullable|string|max:255',
            'department_id' => 'required|exists:departments,id',
        ]);

        $staff = Staff::findOrFail($id);
        $staff->update($request->all());

        return redirect()->route('staff.index')->with('success', 'Staff updated successfully.');
    }

    // Xóa staff
    public function destroy($id)
    {
        $staff = Staff::findOrFail($id);
        $staff->delete();

        return redirect()->route('staff.index')->with('success', 'Staff deleted successfully.');
    }
    public function __construct()
{
    $this->middleware('auth');
    $this->middleware('can:manage-staff')->except(['index', 'show']);
}
}
