<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
// Если админ уже залогинен, перенаправляем в панель
if (!empty($_SESSION['user_name'])) {
    header('Location: /admin/home');
    exit;
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

                <!-- Блок для динамического вывода ошибок через JS -->
                <div class="error-message" id="js-error" style="display: none;" data-aos="fade-down">
                    <span class="error-icon">⚠️</span>
                    <span class="error-text" id="js-error-text"></span>
                </div>

                <form class="login-form" action="javascript:void(0);" id="adminLoginForm">
                    <div class="form-group">
                        <div class="input-wrapper">
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
                        <button type="submit" class="login-btn" id="submitBtn">
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
        </div>
    </div>
</div>

<!-- Подключение jQuery и обработчик отправки -->
<script src="/api/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('#adminLoginForm').on('submit', function(e) {
            e.preventDefault(); // Запрещаем стандартную отправку страницы

            const $err = $('#error-message');
            const $btn = $('#submitBtn');

            $err.hide();
            $btn.prop('disabled', true);

            // Отправляем асинхронный запрос к нашему API
            $.ajax({
                url: '/api/login.php',
                type: 'POST',
                dataType: 'json',
                data: {
                    username: $('#username').val(),
                    password: $('#password').val()
                },
                success: function(res) {
                    if (res.success) {
                        // Редирект средствами JS
                        window.location.href = res.redirect;
                    } else {
                        $err.text(res.error).show();
                        $btn.prop('disabled', false);
                    }
                },
                error: function(xhr) {
                    $err.text('Ошибка сервера (код ' + xhr.status + ')').show();
                    $btn.prop('disabled', false);
                }
            });
        });
    });
</script>
