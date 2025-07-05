<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel Schedule Manager</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">        
        
        <!-- Favicon -->
        <link rel="shortcut icon" href="{{ asset('faviconsekai.png') }}" type="image/png">        

        <!-- Custom Styles -->
        <style>
            body {
                font-family: 'Figtree', Arial, sans-serif;
                background: linear-gradient(135deg, #00bfff 0%, #0099cc 100%);
                min-height: 100vh;
                margin: 0;
                padding: 20px 0;
            }
            .main-container {
                background: rgba(255, 255, 255, 0.95);
                border-radius: 20px;
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
                padding: 40px;
                margin: 20px auto;
                max-width: 1200px;
            }
            .header-title {
                color: #333;
                font-weight: 700;
                font-size: 2.5rem;
                margin-bottom: 30px;
                text-align: center;
            }
            .auth-nav {
                position: absolute;
                top: 20px;
                right: 20px;
                z-index: 1000;
            }
            .auth-nav a {
                background: rgba(255, 255, 255, 0.9);
                color: #333;
                padding: 10px 20px;
                border-radius: 25px;
                text-decoration: none;
                margin-left: 10px;
                font-weight: 600;
                transition: all 0.3s ease;
                border: 2px solid transparent;
            }
            .auth-nav a:hover {
                background: #00bfff;
                color: white;
                border-color: white;
                transform: translateY(-2px);
            }
            .hero-image {
                width: 100%;
                max-width: 800px;
                height: auto;
                border-radius: 15px;
                box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
                margin: 30px auto;
                display: block;
            }
            .feature-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 30px;
                margin: 40px 0;
            }
            .feature-card {
                background: white;
                padding: 30px;
                border-radius: 15px;
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
                text-align: center;
                transition: transform 0.3s ease;
            }
            .feature-card:hover {
                transform: translateY(-5px);
            }
            .feature-icon {
                font-size: 3rem;
                margin-bottom: 20px;
            }
            .cta-button {
                background: linear-gradient(135deg, #00bfff, #0099cc);
                color: white;
                padding: 15px 40px;
                border: none;
                border-radius: 50px;
                font-size: 1.2rem;
                font-weight: 600;
                text-decoration: none;
                display: inline-block;
                margin: 20px 10px;
                transition: all 0.3s ease;
                box-shadow: 0 5px 15px rgba(0, 191, 255, 0.3);
            }
            .cta-button:hover {
                transform: translateY(-3px);
                box-shadow: 0 8px 25px rgba(0, 191, 255, 0.4);
                color: white;
            }
            .footer {
                text-align: center;
                margin-top: 50px;
                padding-top: 30px;
                border-top: 1px solid #eee;
                color: #666;
            }
        </style>
    </head>
    <body>
        <!-- 認証ナビゲーション -->
        <!--
        @if (Route::has('login'))
            <nav class="auth-nav">
                @auth
                    <a href="{{ url('/dashboard') }}">Dashboard</a>
                @else
                    <a href="{{ route('login') }}">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}">Register</a>
                    @endif
                @endauth
            </nav>
        @endif
         -->

        <div class="container">
            <div class="main-container">
                <!-- メインヘッダー -->
                <h1 class="header-title">
                    💰 マイ家計簿アプリ
                </h1>
                
                <!-- ヒーロー画像 -->
                <img src="{{ asset('Designer (5).jpeg') }}" alt="Schedule Manager" class="hero-image">
                
                <!-- 機能紹介 -->
                <div class="feature-grid">
                    <div class="feature-card">
                        <div class="feature-icon">📊</div>
                        <h3>日次記録管理</h3>
                        <p>毎日の活動を簡単に記録・管理できます。カテゴリ別に整理して、効率的にデータを管理しましょう。</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">📈</div>
                        <h3>進捗追跡</h3>
                        <p>月別・カテゴリ別のサマリーで、あなたの進捗を視覚的に確認できます。</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">🎯</div>
                        <h3>目標達成</h3>
                        <p>継続的な記録で習慣を身につけ、目標達成をサポートします。</p>
                    </div>
                </div>

                <!-- CTA ボタン -->
                <div class="text-center">
                    @auth
                        <a href="{{ url('/daily-records') }}" class="cta-button">
                            📝 記録を開始する
                        </a>
                        <a href="{{ url('/dashboard') }}" class="cta-button">
                            📊 ダッシュボード
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="cta-button">
                            🚀 今すぐ始める
                        </a>
                        <a href="{{ route('login') }}" class="cta-button">
                            🔑 ログイン
                        </a>
                    @endauth
                </div>

                <!-- フッター -->
                <div class="footer">
                    <p>Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})</p>
                    <p>&copy; {{ date('Y') }} Laravel Schedule Manager. All rights reserved.</p>
                </div>
            </div>
        </div>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    </body>
</html>
