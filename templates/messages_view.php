<?php
$view_mode = isset($messages_view_mode) ? $messages_view_mode : 'normal';
$target_user_id = isset($messages_target_user_id) ? $messages_target_user_id : $_SESSION['userID'];

$scope_id = 'msg_' . $target_user_id;

$my_children = [];
if ($_SESSION['role'] === 'parent' && isset($_SESSION['children'])) {
    $my_children = $_SESSION['children'];
}
?>

<div class="messages-layout <?php echo ($view_mode === 'child_read_only') ? 'read-only-mode' : ''; ?>" id="<?php echo $scope_id; ?>">
    <div class="messages-sidebar">

        <div class="msg-tools">
            <?php if ($view_mode !== 'child_read_only'): ?>
                <button class="compose-btn-large" onclick="MsgApp_<?php echo $scope_id; ?>.openCompose()">+ Nowa Wiadomość</button>
            <?php else: ?>
                <div style="padding: 12px; text-align: center; font-weight: bold; color: #666; background: #eee; border-radius: 6px;">
                    Podgląd poczty dziecka
                </div>
            <?php endif; ?>
        </div>

        <div class="msg-folders">
            <button class="msg-folder-btn active" onclick="MsgApp_<?php echo $scope_id; ?>.loadMessages('inbox', this)">Odebrane</button>
            <button class="msg-folder-btn" onclick="MsgApp_<?php echo $scope_id; ?>.loadMessages('sent', this)">Wysłane</button>
        </div>

        <div class="msg-list-container msg-list">
            <div style="text-align:center; padding:20px; color:#888;">Ładowanie...</div>
        </div>
    </div>

    <div class="message-reading-pane">

        <div class="message-reader" style="display:none;">
            <div class="msg-full-header">
                <h2 class="reader-subject msg-full-subject">
                    <button class="mobile-back-btn" onclick="MsgApp_<?php echo $scope_id; ?>.goBackToList()">&#8592;</button>
                    <span class="reader-subject-text"></span>
                </h2>
                <div class="msg-full-info">
                    <span class="reader-meta"></span>
                </div>
            </div>
            <div class="reader-body msg-full-body"></div>
        </div>

        <div class="message-composer" style="display:none;">
            <h2 style="margin-top:0; display:flex; align-items:center;">
                <button class="mobile-back-btn" onclick="MsgApp_<?php echo $scope_id; ?>.goBackToList()">&#8592;</button>
                Nowa Wiadomość
            </h2>
            <form class="compose-form" onsubmit="return MsgApp_<?php echo $scope_id; ?>.sendMessage(event)">

                <div class="form-group">
                    <label>Do kogo:</label>
                    <select class="recipient-type" onchange="MsgApp_<?php echo $scope_id; ?>.loadRecipients(this.value)" required style="width:100%; padding:8px; margin-bottom:10px;">
                        <option value="">-- Wybierz typ --</option>
                        <option value="teacher">Nauczyciel</option>
                        <?php if ($_SESSION['role'] === 'teacher' || $_SESSION['role'] === 'admin'): ?>
                            <option value="student">Pojedynczy Uczeń</option>
                            <option value="parent">Pojedynczy Rodzic</option>
                            <option value="class">Cała Klasa (Uczniowie)</option>
                            <option value="class_parents">Cała Klasa (Rodzice)</option>
                            <option value="teachers">Wszyscy Nauczyciele</option>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="form-group recipient-select-container" style="display:none;">
                    <label>Wybierz:</label>
                    <select class="recipient-id" name="recipient_id" required style="width:100%; padding:8px; margin-bottom:10px;"></select>
                </div>

                <div class="form-group">
                    <label>Temat:</label>
                    <input type="text" class="compose-subject" required style="width:100%; padding:8px; margin-bottom:10px; border:1px solid #ccc;">
                </div>

                <div class="form-group">
                    <label>Treść:</label>
                    <textarea class="compose-content" rows="8" required style="width:100%; padding:8px; border:1px solid #ccc; resize:vertical;"></textarea>
                </div>

                <div class="compose-buttons-row">
                    <button type="submit" class="action-btn add-btn">Wyślij</button>
                    <button type="button" class="action-btn delete-btn" onclick="MsgApp_<?php echo $scope_id; ?>.goBackToList()">Anuluj</button>
                </div>
            </form>
        </div>

        <div class="message-placeholder no-msg-selected">
            Wybierz wiadomość z listy, aby ją przeczytać.
        </div>

    </div>
</div>

<script>
    const MsgApp_<?php echo $scope_id; ?> = (function() {

        const root = document.getElementById('<?php echo $scope_id; ?>');

        let targetUserId = <?php echo $target_user_id; ?>;
        let currentFolder = 'inbox';

        const els = {
            layout: root,
            list: root.querySelector('.msg-list'),
            placeholder: root.querySelector('.message-placeholder'),
            reader: root.querySelector('.message-reader'),
            composer: root.querySelector('.message-composer'),
            form: root.querySelector('.compose-form'),
            subjectInput: root.querySelector('.compose-subject'),
            contentInput: root.querySelector('.compose-content'),
            recipientType: root.querySelector('.recipient-type'),
            recipientId: root.querySelector('.recipient-id'),
            recipientContainer: root.querySelector('.recipient-select-container'),
            readerSubjectText: root.querySelector('.reader-subject-text'),
            readerBody: root.querySelector('.reader-body'),
            readerMeta: root.querySelector('.reader-meta'),
            mailboxSelect: root.querySelector('.mailbox-select')
        };

        setTimeout(() => {
            loadMessages('inbox');

            if (els.mailboxSelect) {
                els.mailboxSelect.addEventListener('change', function() {
                    targetUserId = this.value;
                    loadMessages(currentFolder);
                });
            }
        }, 100);

        function showFlash(type, message) {
            const flash = document.createElement('div');
            flash.className = `flash-notification ${type}`;
            flash.style.position = 'fixed';
            flash.style.top = '20px';
            flash.style.right = '20px';
            flash.style.zIndex = '9999';
            flash.innerHTML = `<span>${message}</span>`;
            document.body.appendChild(flash);
            setTimeout(() => {
                flash.style.transition = 'opacity 0.5s';
                flash.style.opacity = '0';
                setTimeout(() => flash.remove(), 500);
            }, 3000);
        }

        function goBackToList() {
            els.layout.classList.remove('show-content');
            els.reader.style.display = 'none';
            els.composer.style.display = 'none';
            els.placeholder.style.display = 'block';
        }

        function showContentPane() {
            els.layout.classList.add('show-content');
        }

        function loadMessages(folder, btn = null) {
            currentFolder = folder;
            if (btn) {
                root.querySelectorAll('.msg-folder-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
            }

            goBackToList();

            els.list.innerHTML = '<div style="padding:20px; text-align:center;">Odświeżanie...</div>';

            fetch(`../../actions/get_messages.php?folder=${folder}&mailbox_user_id=${targetUserId}`)
                .then(r => r.json())
                .then(data => {
                    els.list.innerHTML = '';
                    if (data.length === 0) {
                        els.list.innerHTML = '<div style="padding:20px; text-align:center; color:#888;">Skrzynka pusta.</div>';
                        return;
                    }
                    data.forEach(msg => {
                        const div = document.createElement('div');
                        div.className = `msg-item ${msg.is_read == 0 && folder === 'inbox' ? 'unread' : ''}`;
                        div.onclick = () => openMessage(msg, div);
                        const senderLabel = folder === 'inbox' ? 'Od: ' : 'Do: ';
                        div.innerHTML = `<h4>${escapeHtml(msg.subject)}</h4><div class="msg-item-meta"><span>${senderLabel} ${escapeHtml(msg.other_name)}</span><span>${msg.date}</span></div>`;
                        els.list.appendChild(div);
                    });
                })
                .catch(err => {
                    console.error(err);
                    els.list.innerHTML = 'Błąd ładowania.';
                });
        }

        function openMessage(msg, el) {
            root.querySelectorAll('.msg-item').forEach(i => i.classList.remove('active'));
            el.classList.add('active');

            els.placeholder.style.display = 'none';
            els.composer.style.display = 'none';
            els.reader.style.display = 'block';

            showContentPane();

            els.readerSubjectText.innerText = msg.subject;
            els.readerBody.innerText = msg.content;
            const senderInfo = currentFolder === 'inbox' ? 'Nadawca' : 'Odbiorca';
            els.readerMeta.innerHTML = `<strong>${senderInfo}:</strong> ${escapeHtml(msg.other_name)} <br><strong>Data:</strong> ${msg.date}`;

            if (currentFolder === 'inbox' && msg.is_read == 0) {
                fetch('../../actions/mark_message_read.php', {
                    method: 'POST',
                    body: JSON.stringify({
                        message_id: msg.id,
                        mailbox_user_id: targetUserId
                    })
                });
                el.classList.remove('unread');
                msg.is_read = 1;
            }
        }

        function openCompose() {
            els.placeholder.style.display = 'none';
            els.reader.style.display = 'none';
            els.composer.style.display = 'block';
            showContentPane();

            els.form.reset();
            els.recipientContainer.style.display = 'none';
        }

        function loadRecipients(type) {
            if (!type || type === 'teachers') {
                els.recipientContainer.style.display = 'none';
                return;
            }
            els.recipientContainer.style.display = 'block';
            els.recipientId.innerHTML = '';

            els.recipientId.classList.remove('hidden-native-select');

            let backendType = type;
            if (type === 'class_parents') backendType = 'class';

            fetch(`../../actions/get_users_for_message.php?type=${backendType}`)
                .then(r => r.json())
                .then(data => {
                    if (data.length === 0) {
                        els.recipientId.innerHTML = '<option value="">Brak danych</option>';
                    } else {
                        const empty = document.createElement('option');
                        empty.value = "";
                        empty.text = "";
                        els.recipientId.appendChild(empty);
                        data.forEach(item => {
                            const opt = document.createElement('option');
                            opt.value = item.id;
                            opt.innerText = item.label;
                            els.recipientId.appendChild(opt);
                        });
                    }
                    els.recipientId.disabled = false;

                    new SearchableSelect(els.recipientId);
                })
                .catch(err => {
                    console.error(err);
                    els.recipientId.innerHTML = '<option>Błąd ładowania</option>';
                });
        }

        function sendMessage(e) {
            e.preventDefault();
            const type = els.recipientType.value;
            const subject = els.subjectInput.value;
            const content = els.contentInput.value;
            let id = 0;
            if (type !== 'teachers' && els.recipientId) id = els.recipientId.value;

            const btn = e.target.querySelector('button[type="submit"]');
            const orgText = btn.innerText;
            btn.disabled = true;
            btn.innerText = 'Wysyłanie...';

            fetch('../../actions/send_message.php', {
                    method: 'POST',
                    body: JSON.stringify({
                        recipient_type: type,
                        recipient_id: id,
                        subject: subject,
                        content: content
                    })
                })
                .then(r => r.json())
                .then(data => {
                    btn.disabled = false;
                    btn.innerText = orgText;
                    if (data.success) {
                        showFlash('success', data.message);
                        goBackToList();
                        loadMessages('sent');
                        const sentBtn = root.querySelectorAll('.msg-folder-btn')[1];
                        if (sentBtn) sentBtn.click();
                    } else {
                        showFlash('error', data.message);
                    }
                })
                .catch(err => {
                    btn.disabled = false;
                    btn.innerText = orgText;
                    showFlash('error', 'Błąd sieci.');
                });
            return false;
        }

        function escapeHtml(text) {
            if (!text) return '';
            return text.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
        }

        return {
            loadMessages,
            openCompose,
            goBackToList,
            loadRecipients,
            sendMessage
        };

    })();
</script>