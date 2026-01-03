<?php
require __DIR__ . '/../app/init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $pass = $_POST['password'] ?? '';
    // Basic auth flow: use User model (example)
    $userModel = new App\Models\User($DB);
    $user = $userModel->findByEmail($email);
    if ($user && password_verify($pass, $user['password'])) {
        $_SESSION['user'] = ['id' => $user['id'], 'email' => $user['email'], 'role' => ($user['role_id']==1?'admin':'user')];
        header('Location: /?r=admin');
        exit;
    }
    $error = 'Giriş başarısız.';
}

?><!doctype html>
<html><head><meta charset="utf-8"><title>Admin Giriş</title></head><body>
<h1>Admin Giriş</h1>
<?php if (!empty($error)) echo '<p style="color:red">'.htmlspecialchars($error).'</p>'; ?>
<form method="post">
    <label>E-posta <input type="email" name="email" required></label><br>
    <label>Parola <input type="password" name="password" required></label><br>
    <button type="submit">Giriş</button>
    <input type="hidden" name="_csrf" value="<?= htmlspecialchars(csrf_token()) ?>">
</form>
</body></html>
