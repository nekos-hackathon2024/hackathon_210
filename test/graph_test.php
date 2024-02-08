<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Progress Bar Example</title>
</head>

<body>
    <h2>Download Progress</h2>
    <style>
        progress {
            height: 25px;
            width: 300px;
            /* デフォルトよりちょっと太めに */
            border-radius: 5px;
            /* 角に丸みを帯びる */
            background-color: #ccc;
            /* 灰色の背景 */
        }

        /* 進捗バーのスタイル */
        progress::-webkit-progress-bar {
            background-color: #ccc;
            /* 灰色の背景 */
            border-radius: 5px;
            /* 角に丸みを帯びる */
        }

        progress::-webkit-progress-value {
            background-color: orange;
            /* オレンジ色の進捗 */
            border-radius: 5px;
            /* 角に丸みを帯びる */
        }

        progress::-moz-progress-bar {
            background-color: orange;
            /* オレンジ色の進捗 */
            border-radius: 5px;
            /* 角に丸みを帯びる */
        }
    </style>
    <progress id="downloadProgress" value="0" max="100"></progress>

    <button onclick="startDownload()">Start Download</button>

    <script>
        function startDownload() {
            // ダウンロード開始
            let progress = 0;
            const progressBar = document.getElementById('downloadProgress');

            // タイマーを使用して進捗を模擬的に更新
            const downloadTimer = setInterval(() => {
                progress += 5; // 進捗を増やす
                progressBar.value = progress; // 進捗バーを更新

                // ダウンロードが完了したらタイマーを停止
                if (progress >= 100) {
                    clearInterval(downloadTimer);
                    alert('Download complete!');
                }
            }, 500); // 500ミリ秒ごとに進捗を更新
        }
    </script>
</body>

</html>