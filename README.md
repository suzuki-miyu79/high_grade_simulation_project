# Rese（リーズ）
- 飲食店予約システム
![Atte_top_image](https://github.com/suzuki-miyu79/basic_simulation_project/assets/144597636/c47a8d01-5c8d-4edf-81d3-658ffbcc52e9)

## 作成した目的
- 機能や画面がシンプルで使いやすい飲食店予約サービスを提供するため。

## アプリケーションURL
開発環境：http://localhost/

本番環境：http://43.207.191.144/

### 本番環境テスト用アカウント
[管理者アカウント]
- メールアドレス：aka@abc
- パスワード：12345678

[店舗管理者アカウント]
- メールアドレス：aka@abc
- パスワード：12345678

[一般ユーザーアカウント]
- メールアドレス：aka@abc
- パスワード：12345678

## 機能一覧
- 会員登録、ログイン、ログアウト機能
- 打刻機能（勤務開始、勤務終了、休憩開始、休憩終了）
- 検索機能（ユーザー名のキーワード検索）
- 日付別勤怠情報取得、ユーザー別勤怠情報取得機能
- ページネーション機能

## 使用技術（実行環境）
### プログラミング言語
- フロントエンド：HTML/CSS

- バックエンド：PHP(8.1.2)

### フレームワーク
- Laravel 10.35.0

### デーベース
- MySQL 8.0

## テーブル設計
![table_image](https://github.com/suzuki-miyu79/basic_simulation_project/assets/144597636/1bb54bc1-b7fc-44b1-a562-3df0f91da80e)

## ER図
![er](https://github.com/suzuki-miyu79/basic_simulation_project/assets/144597636/97afd063-8e80-4740-8a47-4a45584774e3)

# 環境構築
### 1．Laravel Sailをインストール
- Laravel sailをインストールするディレクトリに移動し、Laravel sailをインストールします。
  
　curl -s "https://laravel.build/high_grade_simulation_project" | bash

### 2．Laravel sailを起動する
- 「high_grade_simulation_project」ディレクトリへ移動し、Laravel sailを起動するコマンドを実行します。
  
　cd high_grade_simulation_project
 
　./vendor/bin/sail up

### 3．環境変数の変更
- .env.exampleをコピーして.envを作成し、環境変数を変更します。
- メールドライバやアドレスの設定は以下のように変更してください。

MAIL_MAILER=smtp

MAIL_HOST=smtp.gmail.com

MAIL_PORT=465

MAIL_USERNAME=<送信元gmailのアドレス>   #gmailの場合、USERNAMEはFROM_ADDRESSと同じ

MAIL_PASSWORD=<アプリパスワード>　#gmailで二段階認証とアプリパスワードの発行を行ってください

MAIL_ENCRYPTION=ssl

MAIL_FROM_ADDRESS=<送信元gmailのアドレス>

MAIL_FROM_NAME="${APP_NAME}"

### 4．phpMyAdminを追加する
- 次の設定をdocker-compose.ymlに追加します。

   phpmyadmin:
  
        image: phpmyadmin/phpmyadmin
        links:
            - mysql:mysql
        ports:
            - 8080:80
        environment:
            MYSQL_USERNAME: '${DB_USERNAME}'
            MYSQL_ROOT_PASSWORD: '${DB_PASSWORD}'
            PMA_HOST: mysql
        networks:
            - sail

### 5．Laravel Breeze(ユーザー認証パッケージ)のインストール
- larabel/breezeのパッケージを追加します。

  ./vendor/bin/sail composer require larabel/breeze --dev

- breezeをインストールします。

  ./vendor/bin/sail artisan breeze:install

### 6．migrateコマンドの実行
- マイグレーションファイルの内容をデータベースに反映させます。

  ./vendor/bin/sail artisan migrate

### 7．ダミーデータの作成
- シーディングでダミーデータを作成します。

  ./vendor/bin/sail artisan db:seed
