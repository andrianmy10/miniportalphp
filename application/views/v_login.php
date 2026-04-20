<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login System - Mini Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@800&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0; padding: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #87CEEB 0%, #FFFFFF 100%);
            height: 100vh;
            display: flex; justify-content: center; align-items: center;
        }
        .login-box {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            width: 350px;
            text-align: center;
            animation: fadeInUp 0.8s ease-out;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Style buat teks logo Mini Portal */
        .logo-text {
            font-family: 'Montserrat', sans-serif;
            font-size: 32px;
            font-weight: 800;
            color: #0077be; /* Biru sesuai request */
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 5px;
            text-shadow: 2px 2px 4px rgba(0, 119, 190, 0.2); /* Shadow tipis biar hidup */
        }

        /* Style teks LOGIN disesuaikan biar nggak balapan sama logo */
        h2 { 
            color: #777; 
            font-size: 16px;
            font-weight: 400;
            margin-top: 0;
            margin-bottom: 30px; 
            letter-spacing: 2px;
        }

        input {
            width: 100%;
            padding: 12px 15px;
            margin: 10px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
            outline: none;
            transition: 0.3s;
        }
        input:focus { border-color: #87CEEB; box-shadow: 0 0 8px rgba(135,206,235,0.5); }
        button {
            width: 100%;
            background-color: #0077be;
            color: white;
            padding: 12px;
            margin: 20px 0 10px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: 0.3s;
        }
        button:hover { background-color: #005f99; transform: translateY(-2px); }
        .error-msg { color: #ff4d4d; font-size: 13px; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="login-box">
        <div class="logo-text">Mini Portal</div>
        <h2>LOGIN</h2>
        
        <?php if($this->session->flashdata('error')): ?>
            <div class="error-msg"><?= $this->session->flashdata('error'); ?></div>
        <?php endif; ?>
        
        <form action="<?= base_url('auth/login_process'); ?>" method="post">
            <input type="text" name="username" placeholder="Username" required autofocus>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">MASUK</button>
        </form>
    </div>
</body>
</html>