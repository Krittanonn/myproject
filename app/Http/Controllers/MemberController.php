<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(Request $request)
{
    // ค่าค้นหา
    $search = $request->query('search', '');

    // คำสั่งเรียง (asc_age | desc_age)  – ค่าเริ่มต้นให้เป็น desc_age = แก่ไปเด็ก
    $order  = $request->query('order', 'desc_age');

    $query = Member::query();

    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('first_name', 'like', "%$search%")
              ->orWhere('last_name', 'like', "%$search%");
        });
    }

    // จับทิศการเรียงอายุ → แปลงเป็นการเรียง birthdate
    // อายุมาก (แก่) → birthdate เก่ากว่า → ASC
    // อายุเล็ก (เด็ก) → birthdate ใหม่กว่า → DESC
    $birthdateSort = ($order === 'desc_age') ? 'asc' : 'desc';

    $members = $query->orderBy('birthdate', $birthdateSort)->paginate(10);

    return view('members.index', compact('members', 'search', 'order'));
}

    /**
     * แสดงฟอร์มสร้างสมาชิกใหม่
     */
    public function create()
    {
        $titles = ['นาย', 'นาง', 'นางสาว', 'เด็กชาย', 'เด็กหญิง'];
        return view('members.create', compact('titles'));
    }

    /**
     * บันทึกสมาชิกใหม่ลงฐานข้อมูล
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:20',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birthdate' => 'required|date',
            'email' => 'nullable|email|unique:members,email',
            'phone' => 'nullable|string|max:20',
        ]);

        Member::create([
            'title' => $request->title,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'birthdate' => $request->birthdate,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return redirect()->route('members.index')
                        ->with('success', 'เพิ่มสมาชิกเรียบร้อยแล้ว');
    }

    /**
     * แสดงข้อมูลสมาชิกคนหนึ่ง
     */
    public function show($id)
    {
        $member = Member::findOrFail($id);
        return view('members.show', compact('member'));
    }

    /**
     * แสดงฟอร์มแก้ไขสมาชิก
     */
    public function edit($id)
    {
        $member = Member::findOrFail($id);
        $titles = ['นาย', 'นาง', 'นางสาว', 'เด็กชาย', 'เด็กหญิง'];
        return view('members.edit', compact('member', 'titles'));
    }

    /**
     * อัพเดทข้อมูลสมาชิก
     */
    public function update(Request $request, $id)
    {
        $member = Member::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:20',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birthdate' => 'required|date',
            'email' => 'nullable|email|unique:members,email,' . $member->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $member->update([
            'title' => $request->title,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'birthdate' => $request->birthdate,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return redirect()->route('members.index')
                        ->with('success', 'อัพเดทข้อมูลเรียบร้อยแล้ว');
    }

    /**
     * ลบสมาชิก
     */
    public function destroy($id)
    {
        $member = Member::findOrFail($id);
        $member->delete();

        return redirect()->route('members.index')
                        ->with('success', 'ลบสมาชิกเรียบร้อยแล้ว');
    }

    /**
     * รายงานสมาชิกตามอายุ
     */
    public function report()
    {
        // ดึงข้อมูลทั้งหมด
        $members = Member::all();
        
        // จัดกลุ่มตามอายุ
        $ageGroups = [
            '0-10' => 0,
            '11-20' => 0,
            '21-30' => 0,
            '31-40' => 0,
            '41-50' => 0,
            '51-60' => 0,
            '60+' => 0
        ];

        $detailedReport = [];
        
        foreach ($members as $member) {
            $age = \Carbon\Carbon::parse($member->birthdate)->age;
            
            // จัดกลุ่มอายุ
            if ($age <= 10) {
                $ageGroups['0-10']++;
                $group = '0-10 ปี';
            } elseif ($age <= 20) {
                $ageGroups['11-20']++;
                $group = '11-20 ปี';
            } elseif ($age <= 30) {
                $ageGroups['21-30']++;
                $group = '21-30 ปี';
            } elseif ($age <= 40) {
                $ageGroups['31-40']++;
                $group = '31-40 ปี';
            } elseif ($age <= 50) {
                $ageGroups['41-50']++;
                $group = '41-50 ปี';
            } elseif ($age <= 60) {
                $ageGroups['51-60']++;
                $group = '51-60 ปี';
            } else {
                $ageGroups['60+']++;
                $group = '60+ ปี';
            }

            // สร้างรายละเอียดแต่ละคน
            $detailedReport[] = [
                'title' => $member->title ?? '',
                'name' => $member->first_name . ' ' . $member->last_name,
                'birthdate' => $member->birthdate->format('d/m/Y'),
                'age' => $age,
                'age_group' => $group
            ];
        }

        // เรียงข้อมูลตามอายุ
        usort($detailedReport, function($a, $b) {
            return $a['age'] - $b['age'];
        });

        // เตรียมข้อมูลสำหรับกราห
        $chartData = [
            'labels' => array_keys($ageGroups),
            'data' => array_values($ageGroups)
        ];

        return view('members.report', compact('ageGroups', 'detailedReport', 'chartData'));
    }
}