
<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../auth/admin-login.html');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Admin - Forum</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: { 500: '#009B3A', 600: '#008030' },
                        secondary: { 500: '#FBDE4A', 600: '#F0D030' },
                        accent: { 500: '#DC241F' }
                    }
                }
            }
        }
    </script>
    <style>
        body { background: #f4f6f8; }
        nav { background: linear-gradient(90deg, #009B3A 0%, #FBDE4A 50%, #DC241F 100%); box-shadow: 0 2px 12px 0 #009b3a22; }
        nav span { font-family: 'Orbitron', 'Segoe UI', Arial, sans-serif; letter-spacing: 0.06em; }
        #logout-btn { background: #fff; color: #009B3A; border: none; font-weight: 700; border-radius: 0.5rem; padding: 0.5rem 1.2rem; transition: background 0.2s, color 0.2s; }
        #logout-btn:hover { background: #009B3A; color: #fff; }
        main { margin-top: 2rem; }
        #admin-topics-list>div { border: 2px solid #e5e7eb; border-radius: 1rem; background: #fff; box-shadow: 0 2px 12px 0 #009b3a11; margin-bottom: 1.5rem; padding: 2rem 1.5rem; transition: box-shadow 0.2s, border 0.2s; }
        #admin-topics-list>div:hover { border-color: #009B3A; box-shadow: 0 4px 24px 0 #009b3a22; }
        .validate-btn { background: #009B3A; color: #fff; font-weight: 600; border: none; border-radius: 0.4rem; padding: 0.4rem 1.1rem; margin-right: 0.5rem; transition: background 0.2s; }
        .validate-btn:not([disabled]):hover { background: #008030; }
        .delete-btn { background: #DC241F; color: #fff; font-weight: 600; border: none; border-radius: 0.4rem; padding: 0.4rem 1.1rem; transition: background 0.2s; }
        .delete-btn:hover { background: #A91D1A; }
        .text-pending { color: #FBDE4A; }
        .bg-pending { background-color: #fefce8; }
    </style>
</head>
<body class="font-sans">
    <nav>
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <span class="font-extrabold text-2xl tracking-wide text-white">Tableau de bord Admin</span>
            <button id="logout-btn" type="button"></button>
        </div>
    </nav>
    <main class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Gestion des Sujets du Forum</h1>
        <section id="validation-section" class="mb-10">
            <h2 class="text-2xl font-bold text-gray-700 mb-6 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#009B3A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-square mr-2 text-primary-500"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg> Sujets en attente de validation
            </h2>
            <div id="admin-topics-list" class="space-y-6"></div>
        </section>
        <hr class="my-10 border-gray-300">
        <section id="validated-topics-section">
            <h2 class="text-2xl font-bold text-gray-700 mb-6 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-list mr-2 text-blue-600"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg> Tous les sujets (Validés et en attente)
            </h2>
            <div id="all-admin-topics-list" class="space-y-6"></div>
        </section>
    </main>
    <script>
        let topics = [];
        function loadTopics() {
            return fetch('../backend/get_topics.php')
                .then(r => r.json())
                .then(data => { topics = Array.isArray(data) ? data : []; });
        }
        function escapeHtml(text) {
            return text.replace(/[&<>\"]/g, function (c) {
                return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c];
            });
        }
        function formatContentWithLinksAndImages(text) {
            let safe = escapeHtml(text);
            safe = safe.replace(/(https?:\/\/(?:[\w.-]+)\.(?:jpg|jpeg|png|gif|webp|bmp|svg)(?:\?[^\s]*)?)/gi,
                '<a href="$1" target="_blank" rel="noopener"><img src="$1" alt="image" style="max-width:180px;max-height:120px;vertical-align:middle;margin:4px 0;box-shadow:0 2px 8px #0002;border-radius:6px;"></a>');
            safe = safe.replace(/(https?:\/\/[\w\-._~:/?#[\]@!$&'()*+,;=%]+)(?![^<]*>|[^<>]*<\/)/gi,
                '<a href="$1" target="_blank" rel="noopener">$1</a>');
            return safe;
        }
        function categoryLabel(cat) {
            switch (cat) {
                case 'general': return 'Général';
                case 'annonces': return 'Annonces';
                case 'entraide': return 'Entraide';
                case 'divers': return 'Divers';
                default: return cat;
            }
        }
        function renderAdminTopics() {
            loadTopics().then(() => {
                const list = document.getElementById('admin-topics-list');
                list.innerHTML = '';
                const pendingTopics = topics.filter(t => !parseInt(t.validated)).slice().reverse();
                if (pendingTopics.length === 0) {
                    list.innerHTML = '<div class="text-gray-500 p-4 bg-white rounded-lg border border-gray-200">Aucun sujet en attente de validation.</div>';
                    return;
                }
                pendingTopics.forEach(topic => {
                    const div = document.createElement('div');
                    div.className = 'bg-pending p-6 rounded-xl shadow-md border-secondary-500 border-2';
                    let attachmentHtml = '';
                    if (topic.attachment) {
                        if (/(.jpg|.jpeg|.png|.gif|.webp|.bmp|.svg)$/i.test(topic.attachment)) {
                            attachmentHtml = `<div class=\"mb-2\"><a href=\"${topic.attachment}\" target=\"_blank\"><img src=\"${topic.attachment}\" alt=\"Pièce jointe\" style=\"max-width:180px;max-height:120px;vertical-align:middle;margin:4px 0;box-shadow:0 2px 8px #0002;border-radius:6px;\"></a></div>`;
                        } else {
                            attachmentHtml = `<div class=\"mb-2\"><a href=\"${topic.attachment}\" target=\"_blank\" style=\"color:#2563eb;text-decoration:underline;\">Voir la pièce jointe</a></div>`;
                        }
                    }
                    let videoHtml = '';
                    if (topic.video) {
                        videoHtml = `<p class=\"text-xs text-blue-600 mb-4\">Contient une vidéo : <a href=\"${topic.video}\" target=\"_blank\" style=\"text-decoration:underline;\">${topic.video.slice(0, 50)}...</a></p>`;
                    }
                    div.innerHTML = `
                        <div class=\"flex items-center mb-2\">
                            <span class=\"text-xs bg-primary-100 text-primary-500 px-2 py-1 rounded-full mr-2\">${categoryLabel(topic.category)}</span>
                            <span class=\"text-xs text-gray-500\">${topic.date}</span>
                            <span class=\"ml-auto text-sm font-semibold text-pending\">EN ATTENTE</span>
                        </div>
                        <h3 class=\"text-xl font-bold text-gray-900 mb-1\">${escapeHtml(topic.title)}</h3>
                        <div class=\"text-gray-700 mb-4\">${formatContentWithLinksAndImages(topic.content)}</div>
                        ${attachmentHtml}
                        ${videoHtml}
                        <div class=\"flex justify-between items-center text-gray-500 text-sm mb-4\">
                            <span><svg xmlns=\"http://www.w3.org/2000/svg\" width=\"16\" height=\"16\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\" class=\"inline w-4 h-4 mr-1\"><path d=\"M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2\"></path><circle cx=\"12\" cy=\"7\" r=\"4\"></circle></svg> ${escapeHtml(topic.author || 'Anonyme')}</span>
                            <span><svg xmlns=\"http://www.w3.org/2000/svg\" width=\"16\" height=\"16\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\" class=\"inline w-4 h-4 mr-1\"><path d=\"M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z\"></path></svg> 0 réponse(s)</span>
                        </div>
                        <div class=\"flex justify-end mt-4\">
                            <button class=\"validate-btn\" onclick=\"validateTopic(${topic.id})\">Valider</button>
                            <button class=\"delete-btn\" onclick=\"deleteTopic(${topic.id})\">Supprimer</button>
                        </div>
                    `;
                    list.appendChild(div);
                });
            });
        }
        function renderAllAdminTopics() {
            loadTopics().then(() => {
                const allList = document.getElementById('all-admin-topics-list');
                allList.innerHTML = '';
                topics.slice().reverse().forEach(topic => {
                    const statusClass = parseInt(topic.validated) ? 'border-primary-500' : 'bg-pending border-secondary-500';
                    const statusText = parseInt(topic.validated) ? '<span class="ml-auto text-sm font-semibold text-primary-500">VALIDÉ</span>' : '<span class="ml-auto text-sm font-semibold text-pending">EN ATTENTE</span>';
                    const div = document.createElement('div');
                    div.className = `p-4 rounded-xl shadow-md border-2 ${statusClass}`;
                    let attachmentHtml = '';
                    if (topic.attachment) {
                        if (/(.jpg|.jpeg|.png|.gif|.webp|.bmp|.svg)$/i.test(topic.attachment)) {
                            attachmentHtml = `<div class=\"mb-2\"><a href=\"${topic.attachment}\" target=\"_blank\"><img src=\"${topic.attachment}\" alt=\"Pièce jointe\" style=\"max-width:180px;max-height:120px;vertical-align:middle;margin:4px 0;box-shadow:0 2px 8px #0002;border-radius:6px;\"></a></div>`;
                        } else {
                            attachmentHtml = `<div class=\"mb-2\"><a href=\"${topic.attachment}\" target=\"_blank\" style=\"color:#2563eb;text-decoration:underline;\">Voir la pièce jointe</a></div>`;
                        }
                    }
                    let videoHtml = '';
                    if (topic.video) {
                        videoHtml = `<p class=\"text-xs text-blue-600 mb-4\">Contient une vidéo : <a href=\"${topic.video}\" target=\"_blank\" style=\"text-decoration:underline;\">${topic.video.slice(0, 50)}...</a></p>`;
                    }
                    div.innerHTML = `
                        <div class=\"flex items-center mb-2\">
                            <span class=\"text-xs bg-gray-100 text-gray-500 px-2 py-1 rounded-full mr-2\">${categoryLabel(topic.category)}</span>
                            <span class=\"text-xs text-gray-400\">${topic.date}</span>
                            ${statusText}
                        </div>
                        <h3 class=\"text-lg font-bold text-gray-800 mb-1\">${escapeHtml(topic.title)}</h3>
                        <div class=\"text-gray-700 mb-4\">${formatContentWithLinksAndImages(topic.content)}</div>
                        ${attachmentHtml}
                        ${videoHtml}
                        <div class=\"flex justify-end mt-4\">
                            <button class=\"validate-btn\" onclick=\"validateTopic(${topic.id})\" ${parseInt(topic.validated) ? 'disabled' : ''}>${parseInt(topic.validated) ? 'Validé' : 'Valider'}</button>
                            <button class=\"delete-btn\" onclick=\"deleteTopic(${topic.id})\">Supprimer</button>
                        </div>
                    `;
                    allList.appendChild(div);
                });
            });
        }
        document.addEventListener('DOMContentLoaded', () => {
            renderAdminTopics();
            renderAllAdminTopics();
            feather.replace();
            setInterval(() => {
                renderAdminTopics();
                renderAllAdminTopics();
                feather.replace();
            }, 5000);
        });
    </script>
</body>
</html>
