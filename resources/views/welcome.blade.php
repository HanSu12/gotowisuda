<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Klasifikasi Tingkat Kekeringan Cabai</title>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,600,700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #e74c3c;
            --secondary-color: #3498db;
            --panel-bg: #ffffff;
            --text-dark: #2c3e50;
            --text-light: #7f8c8d;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Montserrat', sans-serif;
            background-image: url("{{ asset('images/background.png') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 100vh;
            color: var(--text-dark);
            display: flex;
            flex-direction: column;
        }
        .header {
            background: var(--panel-bg);
            padding: 20px 40px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 20px;
        }
        .logo-title { display: flex; align-items: center; gap: 15px; }
        .logo {
            width: 50px; height: 50px;
            border-radius: 50%; display: flex;
            align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .logo img { width: 100%; height: 100%; object-fit: cover; }
        .title { font-size: 22px; font-weight: 700; color: var(--primary-color); }
        .howto-btn {
            background: var(--secondary-color); color: white; border: none;
            padding: 10px 20px; border-radius: 25px; cursor: pointer;
            font-size: 14px; font-weight: 600;
        }
        .main-container {
            flex-grow: 1; display: flex; flex-direction: column;
            align-items: center; padding: 40px 20px;
            width: 100%; max-width: 1100px; margin: 0 auto;
        }
        .main-content { display: flex; gap: 30px; width: 100%; }
        .left-panel, .right-panel, .history-section {
            background: var(--panel-bg); border-radius: 20px; padding: 30px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.05);
        }
        .left-panel { flex-basis: 40%; display: flex; flex-direction: column; align-items: center; justify-content: center; }
        .right-panel { flex-basis: 60%; display: flex; flex-direction: column; }
        .upload-box {
            width: 100%; height: 100%; border: 2px dashed #e0e0e0;
            border-radius: 15px; display: flex; flex-direction: column;
            align-items: center; justify-content: center; text-align: center; padding: 20px;
        }
        .upload-box.has-image { border-style: solid; padding: 10px; }
        .upload-instructions h3 { font-size: 18px; font-weight: 600; margin-bottom: 5px; }
        .upload-instructions p { font-size: 14px; color: var(--text-light); }
        .upload-input { display: none; }
        .upload-btn {
            background: var(--primary-color); color: white; padding: 12px 25px;
            border-radius: 25px; cursor: pointer; font-size: 16px;
            font-weight: 600; margin-top: 20px;
        }
        #preview-image { display: none; max-width: 100%; max-height: 300px; border-radius: 10px; margin-bottom: 15px; object-fit: contain; }
        #reset-btn { display: none; background: #95a5a6; color: white; padding: 8px 20px; border-radius: 20px; font-weight: 600; border: none; cursor: pointer; }
        .result-header { font-size: 20px; font-weight: 700; margin-bottom: 20px; border-bottom: 1px solid #f0f0f0; padding-bottom: 10px; }
        .result-content { flex-grow: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; }
        #result-placeholder .emoji { font-size: 48px; margin-bottom: 15px; }
        .result-text { width: 100%; display: none; }
        .result-main { display: flex; align-items: center; justify-content: center; gap: 15px; margin-bottom: 20px; }
        .result-emoji { font-size: 48px; }
        .result-label-main { font-size: 32px; font-weight: 700; }
        .result-badges { display: flex; flex-direction: column; gap: 10px; align-items: center; margin-bottom: 25px; }
        .result-badge { padding: 8px 16px; border-radius: 20px; font-size: 14px; font-weight: 600; }
        .details-title { font-size: 16px; font-weight: 600; margin-bottom: 15px; text-align: left; }
        .probability-item { display: flex; align-items: center; margin-bottom: 12px; font-size: 14px; }
        .class-name { width: 70px; text-align: left; font-weight: 600; }
        .progress-bar-container { flex-grow: 1; background-color: #e9ecef; border-radius: 25px; height: 20px; margin: 0 10px; overflow: hidden; }
        .progress-bar { height: 100%; border-radius: 25px; }
        .percentage-text { width: 55px; text-align: right; font-weight: 600; }
        .share-btns { display: flex; gap: 15px; justify-content: center; margin-top: 25px; }
        .wa-share, .tg-share { padding: 10px 20px; border: none; border-radius: 25px; cursor: pointer; font-weight: 600; font-size: 14px; }
        .wa-share { background: #25d366; color: white; }
        .tg-share { background: #0088cc; color: white; }
        .spinner { width: 40px; height: 40px; border: 4px solid #f3f3f3; border-top: 4px solid var(--secondary-color); border-radius: 50%; animation: spin 1s linear infinite; margin-bottom: 10px; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        .history-section { width: 100%; margin-top: 30px; }
        .history-label { font-size: 20px; font-weight: 700; margin-bottom: 20px; display: block; }
        .history-list { display: flex; flex-direction: column; gap: 15px; }
        .history-item { display: flex; align-items: center; gap: 20px; padding: 15px; background: #f8f9fa; border-radius: 10px; border-left: 5px solid var(--secondary-color); }
        .history-result { flex: 1; }
        .history-result .result-main { justify-content: flex-start; margin-bottom: 8px; }
        .history-result .result-emoji { font-size: 24px; }
        .history-result .result-label-main { font-size: 18px; }
        .history-result .result-badges { flex-direction: row; justify-content: flex-start; gap: 8px; margin-bottom: 0; }
        .history-result .result-badge { font-size: 12px; padding: 5px 10px; }
        .history-empty { text-align: center; color: var(--text-light); font-style: italic; padding: 40px; }
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); }
        .modal-content { background-color: #fefefe; margin: 10% auto; padding: 30px; border-radius: 15px; width: 90%; max-width: 600px; position: relative; }
        .close { color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer; position: absolute; right: 20px; top: 15px; }
        @media (max-width: 900px) { .main-content { flex-direction: column; } }
        @media (max-width: 768px) { .header { justify-content: center; } .title { font-size: 20px; } }
    </style>
</head>
<body>
    <header class="header">
        <div class="logo-title">
            <div class="logo">
                <img src="{{ asset('images/logo_cabai.png') }}" alt="Logo Cabai">
            </div>
            <h1 class="title">Sistem Klasifikasi Tingkat Kekeringan Cabai</h1>
        </div>
        <button id="howto-btn" class="howto-btn">Cara Penggunaan</button>
    </header>

    <div class="main-container">
        <div class="main-content">
            <div class="left-panel">
                <div class="upload-box" id="upload-box">
                    <div id="upload-instructions">
                        <h3>Unggah Gambar Cabai</h3>
                        <p>Pilih atau seret gambar ke area ini</p>
                    </div>
                    <img id="preview-image" src="" alt="Preview Gambar Cabai" />
                    <input type="file" id="chili-image" class="upload-input" accept="image/*">
                    <label for="chili-image" class="upload-btn" id="upload-btn-label">Pilih File</label>
                    <button type="button" id="reset-btn">Hapus Gambar</button>
                </div>
            </div>
            <div class="right-panel">
                <h2 class="result-header">Hasil Prediksi</h2>
                <div class="result-content" id="result-content">
                    <div id="result-placeholder">
                         <div id="loading-spinner" style="display: none; align-items:center; flex-direction:column;">
                            <div class="spinner"></div>
                            <span>Menganalisis...</span>
                        </div>
                        <div id="waiting-instruction">
                            <div class="emoji">🖼️</div>
                            <p>Menunggu gambar untuk dianalisis</p>
                        </div>
                    </div>
                    <div class="result-text" id="result-text"></div>
                </div>
            </div>
        </div>
        <section class="history-section">
            <label class="history-label">Riwayat Analisis</label>
            <div class="history-list" id="history-list"></div>
        </section>
    </div>
    
    <div id="howto-modal" class="modal">
        <div class="modal-content">
             <span class="close" id="howto-close">&times;</span>
            <h2 style="color: #e74c3c; margin-bottom: 20px;">Cara Penggunaan</h2>
            <ol style="line-height: 1.8; color: #2c3e50;">
                <li>Klik tombol "Pilih File" atau seret gambar ke panel kiri.</li>
                <li>Hasil analisis akan otomatis muncul di panel kanan.</li>
                <li>Riwayat 3 analisis terakhir akan tersimpan di bagian bawah.</li>
                <li>Untuk menganalisis gambar lain, klik "Hapus Gambar".</li>
            </ol>
        </div>
    </div>

<script>
    // Struktur untuk menampung semua elemen DOM agar rapi
    const dom = {
        input: document.getElementById('chili-image'),
        preview: document.getElementById('preview-image'),
        uploadBox: document.getElementById('upload-box'),
        uploadInstructions: document.getElementById('upload-instructions'),
        uploadBtnLabel: document.getElementById('upload-btn-label'),
        resetBtn: document.getElementById('reset-btn'),
        resultText: document.getElementById('result-text'),
        resultPlaceholder: document.getElementById('result-placeholder'),
        loadingSpinner: document.getElementById('loading-spinner'),
        waitingInstruction: document.getElementById('waiting-instruction'),
        historyList: document.getElementById('history-list'),
        howtoBtn: document.getElementById('howto-btn'),
        howtoModal: document.getElementById('howto-modal'),
        howtoClose: document.getElementById('howto-close')
    };

    // Fungsi aman untuk memuat riwayat dari localStorage
    function loadHistory() {
        try {
            const storedHistory = localStorage.getItem('chiliHistory');
            return storedHistory ? JSON.parse(storedHistory) : [];
        } catch (e) {
            console.error("Gagal memuat riwayat, data dibersihkan.", e);
            localStorage.removeItem('chiliHistory');
            return [];
        }
    }
    let historyData = loadHistory();

    // Fungsi untuk menampilkan riwayat (tanpa gambar)
    function renderHistory() {
        if (!historyData || historyData.length === 0) {
            dom.historyList.innerHTML = '<div class="history-empty">Belum ada riwayat.</div>';
            return;
        }
        dom.historyList.innerHTML = historyData.map(h => {
             const { resultData, confidence, kadar_air } = h;
             const historyResultHTML = `
                <div class="result-main">
                    <span class="result-emoji">${resultData.emoji}</span>
                    <span class="result-label-main">${resultData.label_display}</span>
                </div>
                <div class="result-badges">
                    <div class="result-badge" style="background:${resultData.color}20; color:${resultData.color};">Keyakinan: <strong>${confidence}%</strong></div>
                    <div class="result-badge" style="background:#3498db20; color:#3498db;">Kadar Air: <strong>${kadar_air}</strong></div>
                </div>`;
             return `<div class="history-item"><div class="history-result">${historyResultHTML}</div></div>`;
        }).join('');
    }

    // Fungsi untuk menambah riwayat (tanpa gambar)
    function addToHistory(data) {
        historyData.unshift({
            confidence: data.confidence,
            kadar_air: data.kadar_air,
            resultData: data.resultData
        });
        if (historyData.length > 3) historyData.pop();
        localStorage.setItem('chiliHistory', JSON.stringify(historyData));
        renderHistory();
    }

    // Manajemen Tampilan UI
    function showInitialView() {
        dom.uploadInstructions.style.display = 'block';
        dom.uploadBtnLabel.style.display = 'inline-block';
        dom.preview.style.display = 'none';
        dom.resetBtn.style.display = 'none';
        dom.uploadBox.classList.remove('has-image');
        dom.input.value = '';
        dom.resultPlaceholder.style.display = 'block';
        dom.loadingSpinner.style.display = 'none';
        dom.waitingInstruction.style.display = 'block';
        dom.resultText.style.display = 'none';
        dom.resultText.innerHTML = '';
    }

    function showImageView(imgSrc) {
        dom.uploadInstructions.style.display = 'none';
        dom.uploadBtnLabel.style.display = 'none';
        dom.preview.src = imgSrc;
        dom.preview.style.display = 'block';
        dom.resetBtn.style.display = 'inline-block';
        dom.uploadBox.classList.add('has-image');
    }

    function showLoadingView() {
        dom.waitingInstruction.style.display = 'none';
        dom.loadingSpinner.style.display = 'flex';
        dom.resultText.style.display = 'none';
    }

    function showResultView(html) {
        dom.resultPlaceholder.style.display = 'none';
        dom.resultText.innerHTML = html;
        dom.resultText.style.display = 'block';
    }

    // Fungsi Utama untuk menangani prediksi
    function handlePrediction(file) {
        if (!file) return;

        const reader = new FileReader();
        reader.onload = e => showImageView(e.target.result);
        reader.readAsDataURL(file);

        showLoadingView();

        const formData = new FormData();
        formData.append('image', file);
        const apiUrl = 'http://127.0.0.1:5000/predict';

        fetch(apiUrl, { method: 'POST', body: formData })
        .then(res => res.ok ? res.json() : res.json().then(err => Promise.reject(err)))
        .then(data => {
            if (data.status === 'Gagal') {
                 const errorHtml = `<div style="text-align:center; color: var(--primary-color);"><h3>❌ Bukan  Cabai</h3><p>${data.message || 'Terjadi kesalahan'}${data.confidence ? ` (Keyakinan: ${data.confidence})` : ''}</p></div>`;
                 showResultView(errorHtml);
                 return;
            }

            const { prediction, confidence, probabilities, kadar_air } = data;
            
            const labelMap = {
                'kering': { color: '#e74c3c', emoji: '🌶️', label_display: 'Cabai Kering', description: 'Cabai dalam kondisi kering dan siap untuk disimpan'},
                'sedang': { color: '#f39c12', emoji: '🌶️', label_display: 'Cabai Sedang', description: 'Cabai dalam kondisi setengah kering'},
                'segar': { color: '#27ae60', emoji: '🫑', label_display: 'Cabai Segar', description: 'Cabai dalam kondisi segar dan baru dipetik'}
            };
            const resultData = labelMap[prediction.toLowerCase()] || { color: '#7f8c8d', emoji: '❔', label_display: 'Tidak Diketahui', description: 'Hasil prediksi tidak dapat ditentukan'};
            
            probabilities.sort((a, b) => parseFloat(b.persentase) - parseFloat(a.persentase));
            const probabilitiesHTML = probabilities.map(prob => {
                const mappedClass = labelMap[prob.kelas.toLowerCase()] || labelMap['kering'];
                return `<div class="probability-item"><div class="class-name">${prob.kelas}</div><div class="progress-bar-container"><div class="progress-bar" style="width: ${prob.persentase}%; background-color: ${mappedClass.color};"></div></div><div class="percentage-text">${prob.persentase}%</div></div>`;
            }).join('');

            const resultHTML = `
                <div class="result-main" style="color:${resultData.color}">
                    <span class="result-emoji">${resultData.emoji}</span>
                    <span class="result-label-main">${resultData.label_display}</span>
                </div>
                <div class="result-badges">
                    <div class="result-badge" style="background:${resultData.color}20; color:${resultData.color};">Tingkat Keyakinan: <strong>${confidence}%</strong></div>
                    <div class="result-badge" style="background:#3498db20; color:#3498db;">💧 Perkiraan Kadar Air Sisa: <strong>${kadar_air}</strong></div>
                </div>
                <div style="width:100%; text-align:left;"><h3 class="details-title">Detail Prediksi</h3>${probabilitiesHTML}</div>
                <div class="share-btns">
                    <button onclick="share('wa', \`${resultData.label_display}\`, \`${kadar_air}\`, \`${confidence}\`, \`${resultData.description}\`)" class="wa-share">Bagikan ke WhatsApp</button>
                    <button onclick="share('tg', \`${resultData.label_display}\`, \`${kadar_air}\`, \`${confidence}\`, \`${resultData.description}\`)" class="tg-share">Bagikan ke Telegram</button>
                </div>`;
            
            showResultView(resultHTML);
            addToHistory({ confidence, kadar_air, resultData });
        })
        .catch(err => {
            const errorMessage = err.error || err.message || "Terjadi kesalahan koneksi.";
            const errorHtml = `<div style="text-align:center; color: var(--primary-color);"><h3>❌ Error</h3><p>${errorMessage}</p><small>Periksa koneksi atau coba lagi.</small></div>`;
            showResultView(errorHtml);
        });
    }

    function share(platform, label, moisture, confidence, description) {
        const text = `🌶️ Hasil Analisis Cabai: ${label}\n💧 Perkiraan Kadar Air: ${moisture}\n📊 Tingkat Keyakinan: ${confidence}%\n📝 ${description}`;
        const url = (platform === 'wa') ? `https://wa.me/?text=${encodeURIComponent(text)}` : `https://t.me/share/url?url=&text=${encodeURIComponent(text)}`;
        window.open(url, '_blank');
    }

    // === Event Listeners ===
    document.addEventListener('DOMContentLoaded', () => {
        showInitialView();
        renderHistory();
    });
    dom.resetBtn.addEventListener('click', showInitialView);
    dom.input.addEventListener('change', (e) => handlePrediction(e.target.files[0]));
    dom.howtoBtn.addEventListener('click', () => { dom.howtoModal.style.display = 'block'; });
    dom.howtoClose.addEventListener('click', () => { dom.howtoModal.style.display = 'none'; });
    window.addEventListener('click', (e) => {
        if (e.target === dom.howtoModal) { dom.howtoModal.style.display = 'none'; }
    });
</script>
</body>
</html>