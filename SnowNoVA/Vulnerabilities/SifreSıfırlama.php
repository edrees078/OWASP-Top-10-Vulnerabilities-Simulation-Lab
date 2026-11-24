<!DOCTYPE html>
<html lang="tr">
<head>
  <title>SnowNoVA - Şifre Sıfırlama</title>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="format-detection" content="telephone=no">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="author" content="">
  <meta name="keywords" content="">
  <meta name="description" content="">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body.bg-dark {
      background-color: #343a40;
    }
    nav.bg-dark {
      background-color: #343a40;
      width: 100%;
      padding: 0 20px;
    }
    .navbar {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 56px;
    }
    .navbar-toggler {
      position: absolute;
      right: 15px;
      top: 10px;
    }
    .centered-form {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .form-container {
      background-color: #2c2f33;
      color: #ffffff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.3);
      width: 100%;
      max-width: 500px;
    }
    .form-control {
      background-color: #3c3f41;
      border: 1px solid #555;
      color: #ffffff;
    }
    .btn-primary {
      background-color: #7289da;
      border: none;
      display: block;
      width: 100%;
    }
    .btn-primary:hover {
      background-color: #5a6eaa;
    }
    .btn-container {
      text-align: center;
      color: white; 
    }
    h1 {
      color: white; 
    }
    a {
      color: white; 
    }
    .success-message {
      background-color: #d4edda;
      color: #155724;
      padding: 10px;
      border-radius: 5px;
      margin-top: 10px;
    }
  </style>
</head>
<body class="bg-dark">
  <header id="header">
    <nav class="navbar navbar-expand-lg navbar-light bg-dark">
      <a href="index1.html">
        <img src="images/SnowNoVa22222.png" alt="logo">
      </a>
    </nav>
  </header>
  <div class="centered-form">
    <div class="form-container">
      <h1 class="text-center">Şifre Sıfırlama</h1>
      <form action="" method="POST">
        <?php
        if(isset($_POST['email'])) {
            
            echo "<div class='success-message'>Simülasyon: Şifre sıfırlama bağlantısı gönderilmiş gibi gösterilir; gerçek e-posta gönderilmez.</div>";
        }
        ?>
        <div class="mb-3">
          <label for="email" class="form-label">E-posta</label>
          <input type="email" class="form-control" id="email" name="email" placeholder="E-postanızı girin" required>
        </div>
        <div class="btn-container">
          <button type="submit" class="btn btn-primary">Gönder</button>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
