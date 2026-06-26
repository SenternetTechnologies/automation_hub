<?php
/**
 * SenterNet Technologies - Self-Contained PHP Realtime JSON Engine
 * This architecture uses the same PHP file as a Data Storage and UI Interface.
 */

// 1. Data Segment Layer: Define the data injection line
// The line below stores the inline JSON string that acts as our database.
// ---DATA_START---
$inline_data = '{"views":150,"likes":52,"comments":[{"id":1,"text":"Incredible template! Orey file la database simulation dynamic ah iruku thalaiva."}]}';
// ---DATA_END---

// 2. API Endpoint Controller Layer
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action'])) {
    header('Content-Type: application/json');
    $current_data = json_decode($inline_data, true);
    $action = $_GET['action'];

    if ($action === 'like') {
        $current_data['likes'] += 1;
    } elseif ($action === 'comment') {
        $input = json_decode(file_get_contents('php://input'), true);
        if (!empty($input['text'])) {
            $current_data['comments'][] = [
                'id' => time(),
                'text' => htmlspecialchars($input['text'])
            ];
        }
    } elseif ($action === 'reset') {
        $current_data = [
            'views' => 0,
            'likes' => 0,
            'comments' => []
        ];
    }

    // Rewrite the current file to save data in real-time
    $new_json = json_encode($current_data);
    $file_path = __FILE__;
    $file_content = file_get_contents($file_path);
    
    // Regex targeting mechanism to update the inline JSON node safely
    $pattern = '/(Slots:)?\/\/\s*---DATA_START---\s*\$inline_data\s*=\s*\'.*?\'\s*;\s*\/\/\s*---DATA_END---/s';
    $replacement = "// ---DATA_START---\n\$inline_data = '" . addslashes($new_json) . "';\n// ---DATA_END---";
    
    // Fallback if formatting changes slightly
    if (!preg_match('/(\/\/ ---DATA_START---)/', $file_content)) {
        $pattern = '/\$inline_data\s*=\s*\'.*?\'\s*;/';
        $replacement = "\$inline_data = '" . addslashes($new_json) . "';";
    }

    $updated_content = preg_replace($pattern, $replacement, $file_content);
    
    if ($updated_content) {
        file_put_contents($file_path, $updated_content);
    }

    echo json_encode(['success' => true, 'data' => $current_data]);
    exit;
}

// 3. Auto View Tracker Middleware on standard page visit
$metadata = json_decode($inline_data, true);
if (!isset($_COOKIE['visited_session'])) {
    setcookie('visited_session', 'true', time() + 3600);
    $metadata['views'] += 1;
    
    $new_json = json_encode($metadata);
    $file_path = __FILE__;
    $file_content = file_get_contents($file_path);
    $pattern = '/\$inline_data\s*=\s*\'.*?\'\s*;/';
    $replacement = "\$inline_data = '" . addslashes($new_json) . "';";
    $updated_content = preg_replace($pattern, $replacement, $file_content);
    if ($updated_content) {
        file_put_contents($file_path, $updated_content);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SenterNet Technologies - Pure PHP Single-File App</title>
    
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #fef08a 0%, #f97316 100%);
        }
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #ea580c;
            border-radius: 4px;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col justify-between antialiased text-slate-800">

    <header class="w-full bg-white/90 backdrop-blur-md border-b border-orange-100 sticky top-0 z-50 px-6 py-4 flex flex-wrap justify-between items-center shadow-xs">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-orange-600 flex items-center justify-center text-white shadow-md shadow-orange-200">
                <i class="fa-solid fa-server text-lg"></i>
            </div>
            <div>
                <h1 class="text-lg font-bold text-slate-900 tracking-tight">SenterNet Backend Lab</h1>
                <p class="text-xs text-slate-500 font-medium">Single File Realtime Server System</p>
            </div>
        </div>
        <div class="flex items-center gap-3 mt-2 sm:mt-0">
            <a href="https://chat.whatsapp.com/E7qjZ5SAn8A7qPNEEEXiNn?s=cl&p=a&mlu=1" target="_blank" rel="noopener noreferrer" aria-label="Join WhatsApp Community Hub" class="bg-[#25D366] text-white px-4 py-2 rounded-lg text-sm font-semibold flex items-center gap-2 hover:scale-105 transition-all shadow-md shadow-green-100">
                <i class="fa-brands fa-whatsapp text-base"></i> Join Community
            </a>
        </div>
    </header>

    <main class="flex-grow flex items-center justify-center p-4 sm:p-8">
        <section class="max-w-xl w-full bg-white rounded-3xl shadow-2xl shadow-orange-950/20 border border-slate-100 p-6 sm:p-8">
            
            <div class="mb-6">
                <span class="bg-orange-50 text-orange-600 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">Advanced: Single File DB Simulation</span>
                <h2 class="text-2xl font-bold text-slate-900 mt-2">Realtime Dynamic Content Node</h2>
                <p class="text-sm text-slate-500 mt-1">Every action directly rewrites the active JSON payload within this file structure.</p>
            </div>

            <div class="grid grid-cols-3 gap-4 mb-8">
                <div class="bg-slate-50 rounded-2xl p-4 text-center border border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-slate-200/50 flex items-center justify-center text-slate-600 mx-auto mb-2">
                        <i class="fa-solid fa-eye text-base"></i>
                    </div>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Views</p>
                    <span id="view-count" class="text-xl font-bold text-slate-900 block mt-1"><?php echo $metadata['views']; ?></span>
                </div>
                
                <div class="bg-orange-50/50 rounded-2xl p-4 text-center border border-orange-100/50">
                    <div class="w-10 h-10 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center mx-auto mb-2">
                        <i class="fa-solid fa-heart text-base"></i>
                    </div>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Likes</p>
                    <span id="like-count" class="text-xl font-bold text-slate-900 block mt-1"><?php echo $metadata['likes']; ?></span>
                </div>

                <div class="bg-blue-50/50 rounded-2xl p-4 text-center border border-blue-100/50">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center mx-auto mb-2">
                        <i class="fa-solid fa-comments text-base"></i>
                    </div>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Comments</p>
                    <span id="comment-count" class="text-xl font-bold text-slate-900 block mt-1"><?php echo count($metadata['comments']); ?></span>
                </div>
            </div>

            <div class="flex gap-3 mb-8">
                <button id="like-btn" class="flex-1 bg-orange-600 hover:bg-orange-700 text-white font-semibold py-3 px-4 rounded-xl shadow-lg shadow-orange-200 flex items-center justify-center gap-2 cursor-pointer transition-all active:scale-95">
                    <i class="fa-solid fa-heart"></i> Express Like
                </button>
                <button id="reset-btn" class="bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold p-3 rounded-xl cursor-pointer transition-all active:scale-95" title="Reset Storage Data Node">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
            </div>

            <hr class="border-slate-100 mb-6">

            <div>
                <h3 class="text-sm font-bold text-slate-900 mb-3 flex items-center gap-2">
                    <i class="fa-solid fa-comment-dots text-orange-500"></i> Dispatch Structured Comment
                </h3>
                <form id="comment-form" class="space-y-3">
                    <div class="relative">
                        <input type="text" id="comment-input" required placeholder="Type something to commit into source code node..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-hidden focus:border-orange-500 focus:bg-white transition-all pr-12">
                        <button type="submit" class="absolute right-2 top-2 bottom-2 bg-orange-600 hover:bg-orange-700 text-white px-3 rounded-lg text-xs font-medium transition-all cursor-pointer">
                            <i class="fa-solid fa-paper-plane"></i>
                        </button>
                    </div>
                </form>

                <div id="comments-container" class="mt-4 max-h-40 overflow-y-auto space-y-2 pr-1">
                    <?php if (empty($metadata['comments'])): ?>
                        <p class="text-xs text-slate-400 italic text-center py-2">No dynamic comment nodes found.</p>
                    <?php else: ?>
                        <?php foreach (array_reverse($metadata['comments']) as $comment): ?>
                            <div class="bg-slate-50 border border-slate-100 rounded-xl p-3 text-xs text-slate-700">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="font-bold text-slate-900 flex items-center gap-1">
                                        <i class="fa-solid fa-circle-user text-slate-400"></i> Server Node Client
                                    </span>
                                    <span class="text-[10px] text-orange-500 font-semibold">PHP Live Row</span>
                                </div>
                                <p class="text-slate-600 leading-relaxed"><?php echo $comment['text']; ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

        </section>
    </main>

    <footer class="w-full bg-white border-t border-slate-100 mt-auto px-6 py-6 text-center sm:text-left">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-4">
                <img src="https://www.jotform.com/uploads/senternettechnologies/agent_files/avatar_images/1000009224-6944f3cfaee596.07540393.webp" alt="Senthilkumar CEO Footprint Avatar" class="w-12 h-12 rounded-full border-2 border-orange-500 shadow-sm object-cover">
                <div>
                    <p class="text-sm font-bold text-slate-900">SENTERNET TECHNOLOGIES</p>
                    <p class="text-xs text-slate-500">Location: Aranthangi | Gmail: senternettechnologies@gmail.com</p>
                    <p class="text-xs text-slate-400 font-medium">WhatsApp Infrastructure Helpdesk: +91 81900 38085</p>
                </div>
            </div>
            <div class="flex flex-wrap justify-center gap-4 text-xs font-semibold text-slate-600">
                <a href="https://senternet.page.gd" target="_blank" rel="noopener noreferrer" class="hover:text-orange-600 transition-colors">Website</a>
                <a href="https://t.me/senternet_technologies" target="_blank" rel="noopener noreferrer" class="hover:text-sky-500 transition-colors">Telegram</a>
                <a href="https://www.youtube.com/@senternet-technologies" target="_blank" rel="noopener noreferrer" class="hover:text-red-600 transition-colors">YouTube</a>
                <a href="https://senternet.blogspot.com" target="_blank" rel="noopener noreferrer" class="hover:text-amber-600 transition-colors">Blogger</a>
            </div>
        </div>
    </footer>

    <script>
        const viewCountEl = document.getElementById('view-count');
        const likeCountEl = document.getElementById('like-count');
        const commentCountEl = document.getElementById('comment-count');
        const commentsContainer = document.getElementById('comments-container');
        const likeBtn = document.getElementById('like-btn');
        const resetBtn = document.getElementById('reset-btn');
        const commentForm = document.getElementById('comment-form');
        const commentInput = document.getElementById('comment-input');

        async function triggerAction(actionUrl, payload = null) {
            try {
                const config = {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' }
                };
                if (payload) config.body = JSON.stringify(payload);

                const response = await fetch(actionUrl, config);
                const result = await response.json();
                
                if (result.success) {
                    renderDOMState(result.data);
                }
            } catch (err) {
                console.error("Pipeline Sync Error:", err);
            }
        }

        function renderDOMState(data) {
            viewCountEl.textContent = data.views;
            likeCountEl.textContent = data.likes;
            commentCountEl.textContent = data.comments.length;

            commentsContainer.innerHTML = '';
            if (data.comments.length === 0) {
                commentsContainer.innerHTML = `<p class="text-xs text-slate-400 italic text-center py-2">No dynamic comment nodes found.</p>`;
                return;
            }

            // Render reversing stack list nodes layout
            [...data.comments].reverse().forEach(comment => {
                const commentDiv = document.createElement('div');
                commentDiv.className = 'bg-slate-50 border border-slate-100 rounded-xl p-3 text-xs text-slate-700';
                commentDiv.innerHTML = `
                    <div class="flex justify-between items-center mb-1">
                        <span class="font-bold text-slate-900 flex items-center gap-1">
                            <i class="fa-solid fa-circle-user text-slate-400"></i> Server Node Client
                        </span>
                        <span class="text-[10px] text-orange-500 font-semibold">PHP Live Row</span>
                    </div>
                    <p class="text-slate-600 leading-relaxed">${comment.text}</p>
                `;
                commentsContainer.appendChild(commentDiv);
            });
        }

        likeBtn.addEventListener('click', () => triggerAction('?action=like'));
        
        commentForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const text = commentInput.value.trim();
            if (!text) return;
            triggerAction('?action=comment', { text });
            commentInput.value = '';
        });

        resetBtn.addEventListener('click', () => {
            if (confirm("Reset current internal structural PHP data content?")) {
                triggerAction('?action=reset');
            }
        });
    </script>
</body>
</html>
