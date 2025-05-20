<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
    }

    nav {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 10px 30px;
      background-color: transparent; 
    }

    .nav-left,
    .nav-center,
    .nav-right {
      display: flex;
      align-items: center;
    }

    .nav-left a {
      margin-right: 20px;
      text-decoration: none;
      color: black;
      font-weight: bold;
      font-size: 14px;
    }

    .nav-left a.active {
      border-bottom: 2px solid black;
      padding-bottom: 3px;
    }

    .nav-center input[type="search"] {
      padding: 8px;
      font-size: 14px;
      width: 300px;
      border: 1px solid #aaa;
      border-radius: 4px 0 0 4px;
      outline: none;
    }

    .nav-center button {
      padding: 8px 12px;
      border: 1px solid #aaa;
      background-color: white;
      border-left: none;
      border-radius: 0 4px 4px 0;
      cursor: pointer;
    }

    .nav-right {
      gap: 15px;
    }

    .nav-right i {
      font-size: 18px;
      color: black;
      cursor: pointer;
    }

    .nav-right .username {
      display: flex;
      align-items: center;
      font-weight: bold;
      font-size: 14px;
      gap: 5px;
    }
  </style>
</head>
<body>

  <nav>
    <div class="nav-left">
      <a href="../page/Home.php">BERANDA</a>
      <a href="../page/Sewa.php">SEWA</a>
      <a href="../page/Bantuan.php">BANTUAN</a>
    </div>

    <div class="nav-center">
      <input type="search" placeholder="Cari...">
      <button><i class="fas fa-search"></i></button>
    </div>

    <div class="nav-right">
    <a href="../page/keranjang.php"><i class="fas fa-shopping-cart" title="Keranjang"></i></a> 
      <div class="username">
        <i class="fas fa-user" title="Akun"></i>
        Ujang Kopling
      </div>
    </div>
  </nav>
