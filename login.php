
<!DOCTYPE html>
<html>
<head>
    <title>Login Aplikasi Kasir</title>

    <style>
        body {
            font-family: Arial;
            background: #e9eef5;
        }

        .login {
            width: 350px;
            margin: 100px auto;
            padding: 25px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px #ccc;
        }

        h2 {
            text-align: center;
            color: #2563eb;
        }

        input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #2563eb;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background: #1d4ed8;
        }
    </style>
</head>

<body>

<div class="login">

    <h2>LOGIN KASIR</h2>

    <form action="proses_login.php" method="POST">

        <label>Username</label>
        <input type="text" name="username"
               placeholder="Masukkan Username"
               required>

        <label>Password</label>
        <input type="password" name="password"
               placeholder="Masukkan Password"
               required>

        <button type="submit" name="login">
            LOGIN
        </button>

    </form>

</div>

</body>
</html>