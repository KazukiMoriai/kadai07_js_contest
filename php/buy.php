<!DOCTYPE html>
<html lang="ja">
<!-- 最初の設定は終わっています　必要な方は触ってください -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>LoveYourCars 購入</title>
    <style>
    a {
        color: inherit; /* 親要素の色を継承する */
        text-decoration: none; /* 下線を消す */
    }
    </style>
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/buy.css">
    <!-- サイト全体のフォント -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Gabarito:wght@400..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <!-- topのLoveYourCarsのフォント -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400..900&display=swap" rel="stylesheet">
</head>
<!-- 最初の設定は終わっています　必要な方は触ってください -->
<body>
    <!-- ここから下にコードを書く -->
    <?php include("../html/header.html");?>
    <!-- 絞り込み機能 -->
    <form action="" method="post">
        <div id="sort">
            <div id="s00">
                <span id="s1">
                    <span id="s11">メーカ：  
                        <select name="maker" id="maker" onchange="updateModels()">
                            <option value="">選択してください</option>
                            <!-- 国内メーカー -->
                            <option value="toyota">トヨタ</option>                    
                            <option value="honda">ホンダ</option>
                            <option value="nissan">日産</option>
                            <option value="suzuki">スズキ</option>
                            <option value="mazda">マツダ</option>
                            <option value="subaru">スバル</option>
                            <option value="mitsubishi">三菱</option>
                            <option value="daihatsu">ダイハツ</option>
                            <option value="isuzu">いすゞ</option>
                            <!-- 海外メーカー -->
                            <option value="ford">フォード</option>
                            <option value="chevrolet">シボレー</option>
                            <option value="gmc">GMC</option>
                            <option value="tesla">テスラ</option>
                            <option value="jeep">ジープ</option>
                            <option value="bmw">BMW</option>
                            <option value="mercedes-benz">メルセデス・ベンツ</option>
                            <option value="audi">アウディ</option>
                            <option value="volkswagen">フォルクスワーゲン</option>
                            <option value="porsche">ポルシェ</option>
                            <option value="peugeot">プジョー</option>
                            <option value="renault">ルノー</option>
                            <option value="citroen">シトロエン</option>
                            <option value="volvo">ボルボ</option>
                            <option value="saab">サーブ</option>
                            <option value="fiat">フィアット</option>
                            <option value="ferrari">フェラーリ</option>
                            <option value="lamborghini">ランボルギーニ</option>
                            <option value="maserati">マセラティ</option>
                            <option value="jaguar">ジャガー</option>
                            <option value="land-rover">ランドローバー</option>
                            <option value="hyundai">ヒュンダイ</option>
                            <option value="kia">キア</option>
                        </select>
                    </span>
                    <span id="s2">
                        <div id="s21">モデル：  
                        <select name="model" id="model">
                            <option value="">選択してください</option>
                        </select>
                        </div>
                    </span>
                </span>
            </div>
            <div id="s0">
                <span id="s1">
                    <div id="s11">支払い総額：</div>
                    <span id="s12">
                        <span id="s121"><input type="text" name="miniPrice" value="<?php if( !empty($_POST["miniPrice"]) ){ echo $_POST["miniPrice"]; } ?>">万円</span>
                        <span id="s122">～</span>
                        <span id="s123"><input type="text" name="maxPrice" value="<?php if( !empty($_POST["maxPrice"]) ){ echo $_POST["maxPrice"]; } ?>">万円</span>
                    </span>
                </span>
                <span id="s2">
                    <div id="s21">年式：</div>
                    <div id="s22">
                        <span id="s221"><input type="text" name="oldestYear" value="<?php if( !empty($_POST["oldestYear"]) ){ echo $_POST["oldestYear"]; } ?>">年</span>
                        <span id="s222">～</span>
                        <span id="s223"><input type="text" name="latestYear" value="<?php if( !empty($_POST["latestYear"]) ){ echo $_POST["latestYear"]; } ?>">年</span>
                    </div>
                </span>
            </div>
            <input type="submit" value="検索" id="searchButton">
        </div>
    </form>

    <?php
        // 検索条件を取得
        $selectMaker  = !empty($_POST["maker"]) ? $_POST["maker"] : null;
        $selectModel  = !empty($_POST["model"]) ? $_POST["model"] : null;
        $miniPrice  = !empty($_POST["miniPrice"]) ? intval($_POST["miniPrice"]) : 0;
        $maxPrice   = !empty($_POST["maxPrice"]) ? intval($_POST["maxPrice"]) : PHP_INT_MAX;
        $oldestYear = !empty($_POST["oldestYear"]) ? intval($_POST["oldestYear"]) : 0;
        $latestYear = !empty($_POST["latestYear"]) ? intval($_POST["latestYear"]) : PHP_INT_MAX;      

        include "./class.php";
        // CSVから車情報を取得
        $cars = [];
        $file = fopen("../csv/data.csv", "r");
        while ($line = fgetcsv($file)) {
            list($random, $maker, $model, $price, $year) = $line;
            $price = intval($price);
            $year = intval($year);   
            $car = new Car($random, $maker, $model, $price, $year);
            $cars[] = $car; 
        } 
        fclose($file);

        // 条件に合う車両のみ表示
        foreach ($cars as $car) {
            if (
                ($selectMaker === null || $selectMaker == $car->getMaker()) &&
                ($selectModel === null || $selectModel == $car->getModel()) &&
                $miniPrice <= $car->getPrice() && 
                $car->getPrice() <= $maxPrice && 
                $oldestYear <= $car->getYear() && 
                $car->getYear() <= $latestYear) {
    ?>
    <div class="c1">
        <div class="c11">
            <span class="c111">車両画像</span>
            <span class="c112">
                <div class="c1121"><?php echo $car->getMaker(); ?></div>
                <div class="c1122"><?php echo $car->getModel(); ?></div>
                <div class="c1123">
                    <span class="c11231">支払い総額：<?php echo $car->getPrice(); ?>万円</span>
                    <span class="c11232">年式：<?php echo $car->getYear(); ?>年  </span>
                </div>
            </span>
        </div>
        <div class="c12">
            <span class="c121">顔写真</span>
            <span class="c122">査定コメント</span>
        </div>
    </div>
    <?php 
            }
        }
        include("../html/footer.html");
    ?>

    <script>
    // モデルのプルダウン作成用のデータ
        const carModels = {
            toyota: ["アクア", "プリウス", "クラウン", "カムリ", "ランドクルーザー"],
            honda: ["フィット", "シビック", "アコード", "CR-V", "オデッセイ"],
            nissan: ["ノート", "リーフ", "セレナ", "エクストレイル", "GT-R"],
            suzuki: ["アルト", "スイフト", "ワゴンR", "ジムニー", "ソリオ"],
            mazda: ["CX-5", "マツダ3", "ロードスター", "MX-30"],
            subaru: ["インプレッサ", "レヴォーグ", "フォレスター", "BRZ"],
            mitsubishi: ["アウトランダー", "ランサー", "デリカ", "エクリプスクロス"],
            daihatsu: ["ムーヴ", "タント", "アトレー", "ミライース"],
            isuzu: ["D-MAX", "MU-X"],
            ford: ["フォード・マスタング", "エクスプローラー", "F-150"],
            chevrolet: ["シボレー・コルベット", "シボレー・カマロ"],
            gmc: ["シエラ", "アカディア"],
            tesla: ["モデルS", "モデル3", "モデルX", "モデルY"],
            jeep: ["ラングラー", "チェロキー", "グランドチェロキー"],
            bmw: ["3シリーズ", "5シリーズ", "7シリーズ", "X5"],
            "mercedes-benz": ["Aクラス", "Cクラス", "Eクラス", "GLC"],
            audi: ["A3", "A4", "A6", "Q5"],
            volkswagen: ["ゴルフ", "ポロ", "パサート", "ティグアン"],
            porsche: ["911", "カイエン", "マカン"],
            peugeot: ["208", "3008", "5008"],
            renault: ["カングー", "メガーヌ", "トゥインゴ"],
            citroen: ["C3", "C4", "C5"],
            volvo: ["XC60", "XC90", "V60"],
            saab: ["9-3", "9-5"],
            fiat: ["500", "パンダ"],
            ferrari: ["488", "F8", "ローマ"],
            lamborghini: ["ウラカン", "アヴェンタドール"],
            maserati: ["ギブリ", "クアトロポルテ"],
            jaguar: ["Fタイプ", "XE", "XJ"],
            "land-rover": ["ディスカバリー", "レンジローバー"],
            hyundai: ["エラントラ", "ソナタ", "ツーソン"],
            kia: ["セルトス", "スポーテイジ", "ソウル"]
        };
        // 車種プルダウンを更新する関数
        function updateModels() {
            // メーカーとモデルのセレクトボックスを取得
            const makerSelect = document.getElementById("maker");
            const modelSelect = document.getElementById("model");
            
            // 選択されたメーカーを取得
            const selectedMaker = makerSelect.value;

            // 既存の車種オプションをクリア
            modelSelect.innerHTML = '<option value="">車種を選択してください</option>';

            // 選択されたメーカーが存在する場合にのみ処理
            if (selectedMaker && carModels[selectedMaker]) {
                // メーカーの全モデルに繰り返し処理
                carModels[selectedMaker].forEach(model => {
                    const option = document.createElement("option");
                    option.value = model;
                    option.textContent = model;
                    modelSelect.appendChild(option);
                });
            }
        }
    </script>
</body>
</html>
