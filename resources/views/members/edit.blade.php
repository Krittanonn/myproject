@extends('layouts.app')

@section('content')
<div class="container">
    <h1>แก้ไขข้อมูลสมาชิก</h1>

    @if($errors->any())
    <div class="error-box">
        <ul>
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('members.update', $member) }}" method="POST" enctype="multipart/form-data" class="form">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="title">คำนำหน้า</label>
            <input list="titles" name="title" id="title" value="{{ old('title', $member->title) }}" required />
            <datalist id="titles">
                @foreach($titles as $title)
                <option value="{{ $title }}"></option>
                @endforeach
            </datalist>
        </div>

        <div class="form-group">
            <label for="first_name">ชื่อ</label>
            <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $member->first_name) }}" required />
        </div>

        <div class="form-group">
            <label for="last_name">นามสกุล</label>
            <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $member->last_name) }}" required />
        </div>

        <div class="form-group">
            <label for="birthdate">วันเดือนปีเกิด</label>
            <input type="date" name="birthdate" id="birthdate" value="{{ old('birthdate', $member->birthdate->format('Y-m-d')) }}" required />
        </div>

        <div class="form-group">
            <label for="profile_image">รูปภาพโปรไฟล์ (ถ้ามี)</label>
            <input type="file" name="profile_image" id="profile_image" accept="image/*" />
            @if($member->profile_image)
            <img src="{{ asset('storage/' . $member->profile_image) }}" alt="Profile" class="profile-img">
            @endif
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-submit">บันทึก</button>
            <a href="{{ route('members.index') }}" class="btn-cancel">ยกเลิก</a>
        </div>
    </form>
</div>

<style>
.container {
    max-width: 600px;
    margin: 20px auto;
    padding: 16px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-family: Arial, sans-serif;
}

h1 {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 20px;
}

.error-box {
    background-color: #f8d7da;
    color: #721c24;
    padding: 10px 15px;
    border-radius: 4px;
    margin-bottom: 15px;
}

.error-box ul {
    margin: 0;
    padding-left: 20px;
}

.form-group {
    margin-bottom: 15px;
    display: flex;
    flex-direction: column;
}

label {
    font-weight: 600;
    margin-bottom: 6px;
}

input[type="text"],
input[type="date"],
input[list],
input[type="file"] {
    padding: 8px 10px;
    font-size: 16px;
    border: 1px solid #999;
    border-radius: 4px;
    box-sizing: border-box;
}

input[type="file"] {
    padding: 3px 5px;
}

.profile-img {
    margin-top: 10px;
    width: 96px;
    height: 96px;
    object-fit: cover;
    border-radius: 6px;
    border: 1px solid #ccc;
}

.form-actions {
    margin-top: 20px;
}

.btn-submit {
    background-color: #007bff;
    color: white;
    padding: 10px 18px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}

.btn-submit:hover {
    background-color: #0056b3;
}

.btn-cancel {
    margin-left: 15px;
    color: #555;
    text-decoration: none;
    font-size: 16px;
}

.btn-cancel:hover {
    text-decoration: underline;
}
</style>
@endsection
