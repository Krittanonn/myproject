@extends('layouts.app')

@section('content')
<style>
    /* ตัวอย่าง CSS สำหรับตารางสมาชิก */
    table {
        border-collapse: collapse;
        width: 100%;
    }
    th, td {
        border: 1px solid #D1D5DB; /* เทียบเท่ากับ border-gray-300 */
        padding: 8px;
        text-align: left;
    }
    thead tr {
        background-color: #F3F4F6; /* เทียบเท่ากับ bg-gray-100 */
    }
    img.profile-img {
        height: 48px;
        width: 48px;
        border-radius: 50%;
        object-fit: cover;
        display: block;
        margin: 0 auto;
    }
    .btn-blue {
        background-color: #3B82F6; /* bg-blue-500 */
        color: white;
        padding: 8px 16px;
        border-radius: 4px;
        text-decoration: none;
        font-weight: 600;
    }
    .btn-blue:hover {
        background-color: #2563EB; /* hover:bg-blue-600 */
    }
    .btn-green {
        background-color: #22C55E; /* bg-green-500 */
        color: white;
        padding: 8px 16px;
        border-radius: 4px;
        text-decoration: none;
        font-weight: 600;
    }
    .btn-green:hover {
        background-color: #16A34A; /* hover:bg-green-600 */
    }
    .text-blue-link {
        color: #2563EB;
        cursor: pointer;
        text-decoration: underline;
    }
    .text-red-link {
        color: #DC2626;
        cursor: pointer;
        text-decoration: underline;
    }
    .text-blue-link:hover, .text-red-link:hover {
        opacity: 0.8;
    }
    .alert-success {
        background-color: #DCFCE7; /* bg-green-200 */
        color: #166534; /* text-green-800 */
        padding: 8px;
        border-radius: 4px;
        margin-bottom: 16px;
    }
    .container {
        max-width: 960px;
        margin-left: auto;
        margin-right: auto;
        padding: 16px;
    }
    .flex-between {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }
    form.flex-space {
        display: flex;
        gap: 8px;
    }
    input[type="text"] {
        border: 1px solid #D1D5DB;
        border-radius: 4px;
        padding: 6px 12px;
    }
    .btn-grey {
        background-color: #9CA3AF; /* เทียบเท่ากับ bg-gray-400 */
        color: white;
        padding: 8px 16px;
        border-radius: 4px;
        text-decoration: none;
        font-weight: 600;
    }
    .btn-grey:hover {
        background-color: #6B7280; /* เทียบเท่ากับ hover:bg-gray-600 */
    }
</style>

<div class="container">
    <h1 style="font-size: 1.875rem; font-weight: 700; margin-bottom: 1rem;">สมาชิกทั้งหมด</h1>
    

    @if(session('success'))
    <div class="alert-success">
        {{ session('success') }}
    </div>
    @endif

    <div class="flex-between">
        <form method="GET" action="{{ route('members.index') }}" class="flex-space">
            <input type="text" name="search" value="{{ $search }}" placeholder="ค้นหาชื่อ-นามสกุล" />
            <button type="submit" class="btn-blue">ค้นหา</button>
        </form>
        
        <a href="{{ route('members.create') }}" class="btn-green">เพิ่มสมาชิก</a>
    </div>
    <div style="margin-bottom:12px;">
        @php
            $toggleOrder = $order === 'desc_age' ? 'asc_age' : 'desc_age';
            $toggleText  = $order === 'desc_age' ? 'เรียงอายุ มาก → น้อย' : 'เรียงอายุ น้อย → มาก';
        @endphp
        <a href="{{ route('members.index', ['search' => $search, 'order' => $toggleOrder]) }}" class="btn-grey">
            {{ $toggleText }}
        </a>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>รูป</th>
                <th>คำนำหน้า</th>
                <th>ชื่อ</th>
                <th>นามสกุล</th>
                <th>วันเกิด</th>
                <th>อายุ</th>
                <th>แก้ไขล่าสุด</th>
                <th>จัดการ</th>
            </tr>
        </thead>
        <tbody>
            @forelse($members as $member)
            <tr>
                <td style="text-align:center;">
                    @if($member->profile_image)
                    <img src="{{ asset('storage/' . $member->profile_image) }}" alt="Profile" class="profile-img" />
                    @else
                    -
                    @endif
                </td>
                <td>{{ $member->title }}</td>
                <td>{{ $member->first_name }}</td>
                <td>{{ $member->last_name }}</td>
                <td>{{ $member->birthdate->format('d/m/Y') }}</td>
                <td>{{ $member->age }}</td>
                <td>{{ $member->updated_at->format('d/m/Y H:i') }}</td>
                <td>
                    <a href="{{ route('members.edit', $member) }}" class="text-blue-link">แก้ไข</a>
                    <form action="{{ route('members.destroy', $member) }}" method="POST" style="display:inline;" onsubmit="return confirm('คุณต้องการลบสมาชิกนี้หรือไม่?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-link" style="background:none; border:none; padding:0; font:inherit;">ลบ</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align:center; padding: 8px;">ไม่มีข้อมูลสมาชิก</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    

    <div style="margin-top: 1rem;">
        {{ $members->withQueryString()->links() }}
    </div>

    <a href="{{ route('members.report') }}" class="btn-green">ดูรายงาน</a>
</div>
@endsection
