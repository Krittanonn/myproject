<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>ระบบสมาชิก</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.2/dist/tailwind.min.css" rel="stylesheet" />
</head>
<body class="bg-gray-50 min-h-screen">
    <nav class="bg-white shadow p-4 mb-8">
        <div class="container mx-auto flex justify-between">
            <a href="{{ route('members.index') }}" class="font-bold text-xl text-blue-600">Member System</a>
        </div>
    </nav>

    @yield('content')
</body>
</html>
