# Rese（リーズ）
- 飲食店予約システム
![Rese-top](https://github.com/suzuki-miyu79/high_grade_simulation_project/assets/144597636/95f05a93-885b-44dd-aaa8-7ae9be5a0e95)

## 作成した目的
- 機能や画面がシンプルで使いやすい飲食店予約サービスを提供するため。

## アプリケーションURL
開発環境：http://localhost/

本番環境：http://43.207.191.144/

### テスト用アカウント（開発・本番）
[管理者アカウント]
- メールアドレス：admin@abc.com
- パスワード：12345678

[店舗管理者アカウント]
- メールアドレス：representative@abc.com
- パスワード：12345678

## 機能一覧
- 認証
    - 会員登録
    - ログイン
    - ログアウト
    - メール認証
- 飲食店情報取得機能
    - 飲食店一覧取得
    - 飲食店詳細取得
- お気に入り機能
    - お気に入り追加
    - お気に入り削除
- 予約機能
    - 予約情報追加
    - 予約情報削除
    - 予約情報変更
- QRコード発行機能
- 評価機能
    - 五段階評価
    - コメント送信
- 決済機能
- ユーザー情報取得機能
    - ユーザーお気に入り一覧取得
    - ユーザー予約情報取得
- 検索機能
    - エリア検索
    - ジャンル検索
    - 店名検索
- 管理機能（管理者）
    - 店舗代表者作成
- 管理機能（店舗代表者）
    - 店舗情報作成
    - 店舗情報変更
    - 予約情報確認
- メール送信機能
- リマインダー機能
- ストレージ保存機能
- バリデーション
- レスポンシブデザイン（768px）

## 使用技術（実行環境）
### プログラミング言語
- フロントエンド：HTML/CSS

- バックエンド：PHP(8.1.2)

### フレームワーク
- Laravel 10.35.0

### データベース
- MySQL 8.0

## テーブル設計
![テーブル設計](https://github.com/suzuki-miyu79/high_grade_simulation_project/assets/144597636/d8be949f-d4d5-46a6-b414-0d7a01b4c359)

## ER図
![er_high_grade_simulation_project](https://github.com/suzuki-miyu79/high_grade_simulation_project/assets/144597636/ffa9993a-ca6c-41d3-805d-84185c413efa)

# 環境構築
### 1．Laravel Sailをインストール
- Laravel sailをインストールするディレクトリに移動し、Laravel sailをインストールします。
  
  curl -s "https://laravel.build/high_grade_simulation_project" | bash

### 2．Laravel sailを起動する
- 「high_grade_simulation_project」ディレクトリへ移動し、Laravel sailを起動するコマンドを実行します。
  
  cd high_grade_simulation_project
 
  ./vendor/bin/sail up

### 3．環境変数の変更
- .envの環境変数を変更します。
    - メール設定
  ```
  MAIL_MAILER=smtp
  MAIL_HOST=smtp.gmail.com
  MAIL_PORT=465
  MAIL_USERNAME=<送信元gmailのアドレス>   #gmailの場合、USERNAMEはFROM_ADDRESSと同じ
  MAIL_PASSWORD=<アプリパスワード>　#gmailで二段階認証とアプリパスワードの発行を行ってください
  MAIL_ENCRYPTION=ssl
  MAIL_FROM_ADDRESS=<送信元gmailのアドレス>
  MAIL_FROM_NAME="${APP_NAME}"
  ```
    - Stripe設定
  ```
  STRIPE_KEY= # 公開可能キーを入力
  STRIPE_SECRET= # シークレットキーを入力
  ```
### 4．phpMyAdminを追加する
- 次の設定をdocker-compose.ymlに追加します。
```
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
```
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

### 8．リマインダー機能の確認
- スケジュールに設定している時間にリマインダー送信が実行されることを、以下のコマンドで確認できます。（ローカル環境）
  
  ./vendor/bin/sail artisan schedule:work
