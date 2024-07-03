<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Ulang Kata Sandi Anda</title>
</head>
<body>
    <h2>Hai.. Kamu.. Iya Kamu.</h2>
    <div>
        <p>Saya mendengar permintaan Anda melalui email. Anda sepertinya mengalami masalah dengan Kata Sandi dengan subject <strong>{{$data['subject']}}</strong>. Demi keamanan, Silahkan ubah kata sandi anda melalui LINK khusus:</p>
        <table>
            <tr>
                <td>Nama Pengguna</td>
                <td>{{($data['username'])}}</td>
            </tr>
            <tr>
                <td>Email Anda</td>
                <td>{{$data['email']}}</td>
            </tr>
            <tr>
                <td>Klik Disini Untuk Mengatur Ulan Kata Sandi Anda</td>
                <td>{{$data['url_token']}}</td>
            </tr>
        </table>
        <p>Harap simpan kata sandi ini dengan aman dan jangan dibagikan kepada siapa pun termasuk Tim Esemkarinam itu sendiri.</p>
        <p>Salam Hangat,<br>Tim IT Esemkarinam</p>
    </div>
</body>
</html>
