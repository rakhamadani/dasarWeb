<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$page_title = "Login Petugas";
include __DIR__ . '/../includes/header.php';
?>

<div class="auth-wrapper" style="display: flex; justify-content: center; align-items: center; min-height: calc(70vh - 60px); padding: 2rem 1rem;">
    <div class="auth-card" style="background: #ffffff; padding: 2.5rem; border-radius: 12px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); width: 100%; max-width: 420px; border: 1px solid #eaeaea;">
        
        <div style="text-align: center; margin-bottom: 2rem;">
            <h2 style="color: #2c3e50; font-size: 1.75rem; margin-bottom: 0.5rem; font-weight: 700;">Selamat Datang</h2>
            <p style="color: #7f8c8d; font-size: 0.95rem; margin: 0;">Silakan masuk untuk mengakses sistem SIMPUS-Mini</p>
        </div>

        <!-- Tampilkan Flash Message jika ada error login -->
        <?php if (isset($_SESSION['flash'])): ?>
            <div style="background-color: #f8d7da; color: #721c24; padding: 0.85rem 1rem; border-radius: 8px; margin-bottom: 1.5rem; font-size: 0.9rem; border: 1px solid #f5c6cb;">
                <?php 
                    echo $_SESSION['flash']['pesan']; 
                    unset($_SESSION['flash']);
                ?>
            </div>
        <?php endif; ?>

        <form action="proses_login.php" method="POST">
            <div style="margin-bottom: 1.25rem;">
                <label for="username" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #34495e; font-size: 0.9rem;">Username</label>
                <input type="text" id="username" name="username" required placeholder="Masukkan username Anda" 
                       style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #dcdde1; border-radius: 6px; font-size: 1rem; outline: none; transition: border-color 0.2s; box-sizing: border-box;"
                       onfocus="this.style.borderColor='#3498db'" onblur="this.style.borderColor='#dcdde1'">
            </div>

            <div style="margin-bottom: 1.25rem;">
                <label for="password" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #34495e; font-size: 0.9rem;">Password</label>
                <input type="password" id="password" name="password" required placeholder="Masukkan password Anda" 
                       style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #dcdde1; border-radius: 6px; font-size: 1rem; outline: none; transition: border-color 0.2s; box-sizing: border-box;"
                       onfocus="this.style.borderColor='#3498db'" onblur="this.style.borderColor='#dcdde1'">
            </div>

            <div style="margin-bottom: 1.5rem; display: flex; align-items: center;">
                <label style="display: flex; align-items: center; color: #555; font-size: 0.9rem; cursor: pointer;">
                    <input type="checkbox" name="remember" style="margin-right: 0.5rem; width: 16px; height: 16px; accent-color: #3498db; cursor: pointer;"> 
                    Ingat Saya
                </label>
            </div>

            <button type="submit" style="width: 100%; background-color: #3498db; color: white; border: none; padding: 0.75rem; border-radius: 6px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: background-color 0.2s;"
                    onmouseover="this.style.backgroundColor='#2980b9'" onmouseout="this.style.backgroundColor='#3498db'">
                Masuk ke Sistem
            </button>
        </form>

        <div style="text-align: center; margin-top: 1.5rem; border-top: 1px solid #f1f2f6; padding-top: 1.25rem;">
            <p style="color: #7f8c8d; font-size: 0.9rem; margin: 0;">
                Belum punya akun? <a href="register.php" style="color: #3498db; text-decoration: none; font-weight: 600;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">Daftar di sini</a>
            </p>
        </div>

    </div>
</div>

<?php 
include __DIR__ . '/../includes/footer.php'; 
?>