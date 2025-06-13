<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        // กำหนดค่า default เป็นค่าว่างเพื่อไม่ให้ view เจอ undefined variable
        $search = $request->query('search', '');

        $query = Member::query();

        if ($search) {
            $query->where('first_name', 'like', "%$search%")
                ->orWhere('last_name', 'like', "%$search%");
        }

        // เรียงตามวันเกิด (เก่าสุดก่อน)
        $members = $query->orderBy('birthdate', 'asc')->paginate(10);

        return view('members.index', compact('members', 'search'));
    }

    public function create()
    {
        $titles = ['นาย', 'นาง', 'นางสาว'];
        return view('members.create', compact('titles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|in:นาย,นาง,นางสาว',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'birthdate' => 'required|date',
            'profile_image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profiles', 'public');
            $validated['profile_image'] = $path;
        }

        Member::create($validated);

        return redirect()->route('members.index')->with('success', 'เพิ่มสมาชิกสำเร็จ');
    }

    public function edit(Member $member)
    {
        $titles = ['นาย', 'นาง', 'นางสาว'];
        return view('members.edit', compact('member', 'titles'));
    }

    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'title' => 'required|string|in:นาย,นาง,นางสาว',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'birthdate' => 'required|date',
            'profile_image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('profile_image')) {
            if ($member->profile_image) {
                Storage::disk('public')->delete($member->profile_image);
            }

            $path = $request->file('profile_image')->store('profiles', 'public');
            $validated['profile_image'] = $path;
        }

        $member->update($validated);

        return redirect()->route('members.index')->with('success', 'แก้ไขสมาชิกสำเร็จ');
    }

    public function destroy(Member $member)
    {
        if ($member->profile_image) {
            Storage::disk('public')->delete($member->profile_image);
        }

        $member->delete();

        return redirect()->route('members.index')->with('success', 'ลบสมาชิกสำเร็จ');
    }

    public function report()
{
    // ดึงสมาชิกทั้งหมด
    $members = Member::all();

    // คำนวณอายุ และจัดกลุ่มช่วงอายุ (0-9, 10-19, 20-29, ...)
    $ageGroups = [];

    foreach ($members as $member) {
        $age = \Carbon\Carbon::parse($member->birthdate)->age;
        $group = floor($age / 10) * 10; // เช่น 25 -> 20

        if (!isset($ageGroups[$group])) {
            $ageGroups[$group] = 0;
        }
        $ageGroups[$group]++;
    }

    // เรียงช่วงอายุ
    ksort($ageGroups);

    return view('members.report', [
        'ageGroups' => $ageGroups
    ]);
}

}
