<!DOCTYPE html>
    <html lang="uk">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Головна</title>
    
        <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap"
          rel="stylesheet">
    
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/css/datepicker.min.css">
    <link rel="stylesheet" href="https://cdn/css/swiper-bundle.min.css">
    <link rel="stylesheet" href="./css/components.css?v=002">
    </head>
    <body class="login-page">
    
    
    <div class="main" id="main">
        <form action="{{route('login')}}" method="POST">
            @csrf
            <p class="form-title">Авторизація</p>
            
            <div class="form-group required" data-valid="mask, empty">
                <label for="email">Електронна пошта</label>
                <input class="_js-mask-email" type="text" id="email" name="email" placeholder="Пошта">
                <div class="help-block" data-empty="Обов`язкове поле"></div>
            </div>
    
            <div class="form-group required" data-valid="empty">
                <label for="password">Пароль</label>
                <div class="input-wrapper">
                    <input type="password" name="password" id="password" placeholder="Пароль">
                    <button type="button" class="icon-visible btn-password-visible"></button>
                </div>
                <div class="help-block" data-empty="Обов`язкове поле"></div>
            </div>
    
            <div class="form-group checkbox">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Запам’ятати мене</label>
            </div>
    
            <button type="submit" class="btn-primary btn-blue">Увійти</button>
        </form>
        <div class="hello">
            <h1 class="h1">
                👋 Вітаємо у <strong>AL-KO Service</strong>
            </h1>
            <div class="desc">
                <p>
                    Для доступу до порталу введіть свої електронну пошту та пароль
                </p>
            </div>
        </div>
    </div>
    
    
    <script src="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/js/datepicker-full.min.js"></script>
    <script src="/cdn/js/swiper-bundle.min.js" ></script>
    <script src="/cdn/js/popper.min.js" ></script>
    <script src="/cdn/js/tippy-bundle.umd.min.js"></script>
    <script src="/cdn/js/maskinput.js" id="maskinput-script" defer></script>
    <!--<script src="/cdn/js/custom-select.js"></script>-->
    <script src="/js/components.js?v=002"></script>
    
    <script src="/js/main.js?v=002"></script>
    </body>
    </html>
    