<?php
    if(session_status() !== PHP_SESSION_ACTIVE)
    {
        session_start();
    }
    if(!empty($_POST))
    {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $host = 'localhost';
        $port = '5432';
        $dbname = 'butbalanced';
        $user = 'postgres'; // В PostgreSQL суперпользователь называется postgres, а не root!
        $password_db = 'jdp96n'; // Пароль, который ты установил при инсталляции PostgreSQL

        // Строка подключения
        $connection_string = "host=$host port=$port dbname=$dbname user=$user password=$password_db";

        // Пробуем подключиться
        $con = pg_connect($connection_string);
        $query = 'SELECT * FROM users WHERE login = $1 AND password = $2';
        $par = [
            'user_name' => $username,
            'user_password' => $password
        ];
        $result = pg_query_params($con,$query,$par);
        $user = pg_fetch_assoc($result);
        if(!empty($user))
        {
            // print_r($user);
            $_SESSION['user_name'] = $user['login'];
            $_SESSION['user_acl'] = $user['acl'];
            Header('Location:home');
            exit;
        }
    }
?>
<div class="bb_root" style="width:100%;">
    <div class="_2cvxxeAj5gGKb86simBsB-">
        <div class="login-container">
            <div class="login-background">
                <div class="login-overlay"></div>
            </div>

            <div class="login-form-wrapper" data-aos="fade-up" data-aos-duration="800">
                <div class="login-header">
                    <div class="admin-badge">
                        <span class="admin-icon">🗿</span>
                        <span class="admin-text">ADMIN PANEL</span>
                    </div>
                    <h1 class="login-title">Вход в <span class="login-title-highlight">ButBalanced</span></h1>
                    <div class="login-divider"></div>
                    <p class="login-subtitle">Доступ только для администраторов</p>
                </div>

                <?php if (isset($error)): ?>
                <div class="error-message" data-aos="fade-down">
                    <span class="error-icon">⚠️</span>
                    <span class="error-text"><?php echo htmlspecialchars($error); ?></span>
                </div>
                <?php endif; ?>

                <form class="login-form" method="POST" action="">
                    <div class="form-group">
                        <div class="input-wrapper">
                            <!-- Важно: placeholder должен быть " ", чтобы CSS понимал, что поле пустое -->
                            <input type="text" id="username" name="username" class="form-input" placeholder=" " required autocomplete="off">
                            <label class="form-label" for="username">Login</label>
                            <span class="input-focus-border"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="input-wrapper">
                            <input type="password" id="password" name="password" class="form-input" placeholder=" " required>
                            <label class="form-label" for="password">Password</label>
                            <span class="input-focus-border"></span>
                        </div>
                    </div>

                    <div class="form-actions" data-aos="fade-up" data-aos-delay="300">
                        <button type="submit" class="login-btn">
                            <span class="btn-icon">▶</span>
                            <span class="btn-text">Войти в панель управления</span>
                        </button>
                    </div>
                </form>

                <div class="login-footer" data-aos="fade-up" data-aos-delay="400">
                    <div class="security-notice">
                        <span class="lock-icon">🔐</span>
                        <span>Защищенное соединение</span>
                    </div>
                </div>
            </div>

            <div class="login-decoration">
                <div class="decoration-line"></div>
                <div class="decoration-dots">
                    <span></span><span></span><span></span>
                </div>
                <div class="decoration-line"></div>
            </div>
        </div>
    </div>
</div>
