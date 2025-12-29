</div>

<script>
    function openTab(tabName, el) {
        document.querySelectorAll('.card').forEach(card => card.classList.remove('active'));
        document.querySelectorAll('.menu-item').forEach(item => item.classList.remove('active'));
        const target = document.getElementById(tabName);
        if (target) target.classList.add('active');
        if (el) el.classList.add('active');
        if (window.innerWidth <= 768) closeMobileMenu();
    }

    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');

    function openMobileMenu() {
        if (sidebar) sidebar.classList.add('open');
        if (overlay) overlay.classList.add('active');
    }

    function closeMobileMenu() {
        if (sidebar) sidebar.classList.remove('open');
        if (overlay) overlay.classList.remove('active');
    }

    class SearchableSelect {
        constructor(selectElement) {
            this.select = selectElement;
            if (this.select.nextElementSibling && this.select.nextElementSibling.classList.contains('searchable-select-wrapper')) {
                this.select.nextElementSibling.remove();
                this.select.classList.remove('hidden-native-select');
            }

            this.reposition = this.reposition.bind(this);
            this.close = this.close.bind(this);

            this.init();
        }

        init() {
            this.select.classList.add('hidden-native-select');
            this.wrapper = document.createElement('div');
            this.wrapper.className = 'searchable-select-wrapper';

            this.input = document.createElement('input');
            this.input.type = 'text';
            this.input.className = 'searchable-input';
            this.input.placeholder = 'Wybierz...';
            this.input.autocomplete = 'off';

            this.dropdown = document.createElement('div');
            this.dropdown.className = 'searchable-dropdown';

            this.wrapper.appendChild(this.input);
            document.body.appendChild(this.dropdown);

            this.select.parentNode.insertBefore(this.wrapper, this.select.nextSibling);

            this.optionsData = [];
            Array.from(this.select.options).forEach(opt => {
                if (opt.value) this.optionsData.push({
                    value: opt.value,
                    text: opt.text
                });
            });

            this.input.addEventListener('focus', () => {
                this.renderOptions();
                this.open();
            });

            this.input.addEventListener('input', () => {
                this.renderOptions(this.input.value);
                this.open();
            });

            document.addEventListener('click', (e) => {
                if (!this.wrapper.contains(e.target) && !this.dropdown.contains(e.target)) {
                    this.close();
                }
            });

            document.addEventListener('scroll', this.reposition, true);

            this.syncInputWithSelect();
        }

        open() {
            this.dropdown.classList.add('open');
            this.reposition();

            document.addEventListener('scroll', this.reposition, true);
            window.addEventListener('resize', this.reposition);
        }

        close() {
            this.dropdown.classList.remove('open');

            document.removeEventListener('scroll', this.reposition, true);
            window.removeEventListener('resize', this.reposition);

            this.syncInputWithSelect();
        }

        reposition() {
            const rect = this.input.getBoundingClientRect();

            this.dropdown.style.top = (rect.bottom) + 'px';
            this.dropdown.style.left = (rect.left) + 'px';
            this.dropdown.style.width = (rect.width) + 'px';
        }

        renderOptions(filterText = '') {
            this.dropdown.innerHTML = '';
            const filter = filterText.toLowerCase();
            const filtered = this.optionsData.filter(opt => opt.text.toLowerCase().includes(filter));

            if (filtered.length === 0) {
                const div = document.createElement('div');
                div.className = 'searchable-option no-results';
                div.innerText = 'Brak wyników';
                this.dropdown.appendChild(div);
            } else {
                filtered.forEach(opt => {
                    const div = document.createElement('div');
                    div.className = 'searchable-option';
                    div.innerText = opt.text;
                    div.addEventListener('click', () => {
                        this.selectValue(opt.value, opt.text);
                    });
                    this.dropdown.appendChild(div);
                });
            }
        }

        selectValue(value, text) {
            this.select.value = value;
            this.input.value = text;
            this.close();
            this.select.dispatchEvent(new Event('change'));
        }

        syncInputWithSelect() {
            const selectedOption = this.select.options[this.select.selectedIndex];
            if (selectedOption && selectedOption.value) {
                this.input.value = selectedOption.text;
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {

        const hamburger = document.getElementById('hamburger');
        if (hamburger) hamburger.addEventListener('click', openMobileMenu);
        if (overlay) overlay.addEventListener('click', closeMobileMenu);
        const flashNotification = document.querySelector('.flash-notification');
        if (flashNotification) {
            setTimeout(() => {
                flashNotification.style.opacity = '0';
                setTimeout(() => flashNotification.remove(), 500);
            }, 5000);
            document.querySelector('.close-flash-btn')?.addEventListener('click', () => flashNotification.remove());
        }
        document.querySelectorAll('.modal-overlay').forEach(modal => {
            modal.querySelector('.close-modal-btn')?.addEventListener('click', () => modal.classList.remove('active'));
            modal.addEventListener('click', function(e) {
                if (e.target === this) this.classList.remove('active');
            });
        });

        document.querySelectorAll('.table-search').forEach(input => {
            input.addEventListener('keyup', function() {
                const filter = this.value.toLowerCase();
                const table = document.getElementById(this.dataset.table);
                if (!table) return;
                table.querySelectorAll('tbody tr').forEach(row => {
                    row.style.display = row.innerText.toLowerCase().includes(filter) ? '' : 'none';
                });
            });
        });
        document.querySelectorAll('select.searchable').forEach(select => new SearchableSelect(select));

        ['addGradeModal', 'addNoteModal', 'addClassModal', 'addUserModal', 'addSubjectModal', 'addAssignmentModal'].forEach(id => {
            const modal = document.getElementById(id);
            const btn = document.getElementById('open' + id.charAt(0).toUpperCase() + id.slice(1) + 'Btn');
            if (modal && btn) btn.addEventListener('click', () => modal.classList.add('active'));
        });

        document.querySelectorAll('.auto-edit-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const modal = document.querySelector(this.dataset.target);
                if (!modal) return;
                for (const key in this.dataset) {
                    if (key.startsWith('fill')) {
                        const fieldName = key.replace('fill', '').toLowerCase();
                        const inputs = modal.querySelectorAll('input, select, textarea');
                        inputs.forEach(input => {
                            if (input.name.toLowerCase() === fieldName) {
                                input.value = this.dataset[key];
                                if (input.tagName === 'SELECT' && input.classList.contains('searchable')) new SearchableSelect(input);
                            }
                        });
                    }
                }
                modal.classList.add('active');
            });
        });

        document.querySelectorAll('.auto-delete-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                if (confirm(this.dataset.confirm || 'Czy na pewno usunąć?')) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = this.dataset.action;

                    for (const key in this.dataset) {
                        if (key.startsWith('input')) {
                            let inputName = key.replace('input', '');
                            const hidden = document.createElement('input');
                            hidden.type = 'hidden';
                            hidden.name = inputName.charAt(0).toLowerCase() + inputName.slice(1);
                        }
                    }

                    const inputName = this.dataset.key || 'id';
                    const val = this.dataset.value;

                    if (this.dataset.payload) {
                        const payload = JSON.parse(this.dataset.payload);
                        for (const [k, v] of Object.entries(payload)) {
                            const h = document.createElement('input');
                            h.type = 'hidden';
                            h.name = k;
                            h.value = v;
                            form.appendChild(h);
                        }
                    } else {
                        const h = document.createElement('input');
                        h.type = 'hidden';
                        h.name = this.dataset.key;
                        h.value = this.dataset.value;
                        form.appendChild(h);
                    }

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });

        const editUserModal = document.getElementById('editUserModal');
        const assignChildModal = document.getElementById('assignChildModal');
        
        if (editUserModal) {
            const roleSelect = document.getElementById('addUserRoleSelect');
            if (roleSelect) roleSelect.addEventListener('change', function() {
                manageRoleFields(document.getElementById('addUserModal'), this.value);
            });

            function manageRoleFields(container, role) {
                container.querySelectorAll('.role-specific-fields').forEach(f => {
                    f.style.display = 'none';
                    f.querySelectorAll('input').forEach(i => i.required = false);
                });
                const active = container.querySelector(`.role-specific-fields[data-role="${role}"]`);
                if (active) {
                    active.style.display = 'block';
                }
            }

            function renderAssignedChildren(children, parentId) {
                const listContainer = document.getElementById('assigned-children-list');
                if (!listContainer) return;

                listContainer.innerHTML = '';

                if (children && children.length > 0) {
                    children.forEach(child => {
                        const div = document.createElement('div');
                        div.className = 'assigned-child-item';
                        div.innerHTML = `
                            <span>${child.first_name} ${child.last_name}</span>
                            <button type="button" class="action-btn delete-btn unassign-btn" style="padding:5px 10px; font-size:12px;">Odłącz</button>
                        `;

                        div.querySelector('.unassign-btn').addEventListener('click', async () => {
                            if (!confirm('Odłączyć dziecko?')) return;
                            const fd = new FormData();
                            fd.append('parentID', parentId);
                            fd.append('studentID', child.studentID);

                            const res = await fetch('../../actions/unassign_child.php', {
                                method: 'POST',
                                body: fd
                            });
                            const json = await res.json();
                            if (json.success) {
                                const newChildren = children.filter(c => c.studentID !== child.studentID);
                                renderAssignedChildren(newChildren, parentId);
                                listContainer.dataset.children = JSON.stringify(newChildren);
                            } else {
                                alert('Błąd odłączania');
                            }
                        });
                        listContainer.appendChild(div);
                    });
                } else {
                    listContainer.innerHTML = '<p style="color:#888; font-style:italic;">Brak przypisanych dzieci.</p>';
                }
            }

            document.querySelectorAll('.edit-user-btn').forEach(btn => {
                btn.addEventListener('click', async function() {
                    const userId = this.dataset.userId;
                    const username = this.dataset.username;

                    try {
                        const res = await fetch(`../../actions/get_user_details.php?userID=${userId}`);
                        if (!res.ok) throw new Error('Błąd');
                        const data = await res.json();

                        const form = editUserModal.querySelector('form');
                        form.reset();
                        editUserModal.querySelector('#edit-modal-username-title').innerText = username;

                        form.querySelector('#edit_userID').value = data.userID;
                        form.querySelector('#edit_username').value = data.username;
                        form.querySelector('#edit_email').value = data.email;
                        form.querySelector('#edit_phone').value = data.phone || '';

                        editUserModal.querySelectorAll('.role-specific-fields').forEach(f => f.style.display = 'none');
                        const roleSection = editUserModal.querySelector(`.role-specific-fields[data-role="${data.role}"]`);

                        if (roleSection) {
                            roleSection.style.display = 'block';

                            if (data.role === 'student') {
                                form.querySelector('#edit_student_first_name').value = data.student_first_name;
                                form.querySelector('#edit_student_last_name').value = data.student_last_name;
                                form.querySelector('#edit_student_pesel').value = data.student_pesel;
                                form.querySelector('#edit_student_address').value = data.student_address;
                                form.querySelector('#edit_student_birth_date').value = data.student_birth_date;
                                form.querySelector('#edit_student_classID').value = data.student_classID;
                                if(form.querySelector('#edit_student_classID').classList.contains('searchable')) {
                                    new SearchableSelect(form.querySelector('#edit_student_classID'));
                                }
                            } else if (data.role === 'teacher') {
                                form.querySelector('#edit_teacher_first_name').value = data.teacher_first_name;
                                form.querySelector('#edit_teacher_last_name').value = data.teacher_last_name;
                                form.querySelector('#edit_teacher_pesel').value = data.teacher_pesel;
                                form.querySelector('#edit_teacher_address').value = data.teacher_address;
                                form.querySelector('#edit_teacher_birth_date').value = data.teacher_birth_date;
                                form.querySelector('#edit_teacher_hire_date').value = data.teacher_hire_date;
                            } else if (data.role === 'parent') {
                                form.querySelector('#edit_parent_first_name').value = data.parent_first_name;
                                form.querySelector('#edit_parent_last_name').value = data.parent_last_name;
                                form.querySelector('#edit_parent_address').value = data.parent_address || '';
                                
                                const childrenList = document.getElementById('assigned-children-list');
                                childrenList.dataset.children = JSON.stringify(data.assigned_children || []);
                                renderAssignedChildren(data.assigned_children, data.userID);
                            }
                        }

                        editUserModal.classList.add('active');

                    } catch (e) {
                        alert('Nie udało się pobrać danych: ' + e);
                    }
                });
            });
            
            const openAssignBtn = document.getElementById('openAssignChildModalBtn');
            const allStudentsList = document.getElementById('all-students-list');
            const studentSearchInput = document.getElementById('student-search-input');
            let allStudentsCache = [];

            if (openAssignBtn && assignChildModal) {
                openAssignBtn.addEventListener('click', async () => {
                    const res = await fetch('../../actions/get_all_students.php');
                    allStudentsCache = await res.json();
                    renderStudentSearch(allStudentsCache);
                    assignChildModal.classList.add('active');
                });

                if (studentSearchInput) {
                    studentSearchInput.addEventListener('keyup', () => {
                        const term = studentSearchInput.value.toLowerCase();
                        const filtered = allStudentsCache.filter(s => 
                            (s.first_name + ' ' + s.last_name).toLowerCase().includes(term)
                        );
                        renderStudentSearch(filtered);
                    });
                }

                function renderStudentSearch(list) {
                    allStudentsList.innerHTML = '';
                    const parentId = document.getElementById('edit_userID').value;
                    
                    list.forEach(student => {
                        const div = document.createElement('div');
                        div.className = 'student-list-item';
                        div.innerText = `${student.first_name} ${student.last_name}`;
                        
                        div.addEventListener('click', async () => {
                            const fd = new FormData();
                            fd.append('parentID', parentId);
                            fd.append('studentID', student.studentID);
                            
                            const res = await fetch('../../actions/assign_child.php', { method:'POST', body:fd });
                            const json = await res.json();
                            
                            if(json.success) {
                                const currentListInfo = document.getElementById('assigned-children-list');
                                let currentChildren = [];
                                try {
                                    currentChildren = JSON.parse(currentListInfo.dataset.children || '[]');
                                } catch(e) {}
                                
                                currentChildren.push(student);
                                currentListInfo.dataset.children = JSON.stringify(currentChildren);
                                
                                renderAssignedChildren(currentChildren, parentId);
                                assignChildModal.classList.remove('active');
                            } else {
                                alert(json.message || 'Błąd przypisywania');
                            }
                        });
                        allStudentsList.appendChild(div);
                    });
                }
            }
        }

        document.querySelectorAll('.final-grade-input').forEach(input => {
            input.addEventListener('change', function() {
                const {
                    studentId,
                    subjectId,
                    field
                } = this.dataset;
                let value = this.value;
                if (value !== '' && (value < 1 || value > 6)) {
                    alert('Ocena 1-6');
                    this.value = '';
                    return;
                }

                this.classList.remove('status-success', 'status-error');
                this.classList.add('status-saving');

                fetch('../../actions/update_final_grade.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            studentID: studentId,
                            subjectID: subjectId,
                            field: field,
                            value: value
                        })
                    })
                    .then(r => r.json())
                    .then(data => {
                        this.classList.remove('status-saving');
                        if (data.success) {
                            this.classList.add('status-success');

                            const printId = `print_${field}_${studentId}`;
                            const printEl = document.getElementById(printId);
                            if (printEl) printEl.innerText = value === '' ? '-' : value;

                            setTimeout(() => this.classList.remove('status-success'), 2000);
                        } else throw new Error(data.message);
                    })
                    .catch(e => {
                        this.classList.remove('status-saving');
                        this.classList.add('status-error');
                    });
            });
        });

        const addGradeModal = document.getElementById('addGradeModal');
        if (addGradeModal) document.querySelectorAll('.add-grade-plus').forEach(p => p.addEventListener('click', function() {
            addGradeModal.querySelector('#modal_student_id').value = this.dataset.studentId;
            addGradeModal.querySelector('#modal-title-student-name').innerText = this.dataset.studentName;
            addGradeModal.classList.add('active');
        }));

        const editGradeModal = document.getElementById('editGradeModal');
        if (editGradeModal) document.querySelectorAll('.grade-pill').forEach(p => p.addEventListener('click', function() {
            this.classList.add('hide-tooltip');

            this.addEventListener('mouseleave', function() {
                this.classList.remove('hide-tooltip');
            }, {
                once: true
            });

            const d = this.dataset;
            editGradeModal.querySelector('#edit_grade_id').value = d.gradeId;
            editGradeModal.querySelector('#delete_grade_id').value = d.gradeId;
            editGradeModal.querySelector('#edit-modal-student-name').innerText = d.studentName;
            editGradeModal.querySelector('#edit_grade').value = d.gradeValue;
            editGradeModal.querySelector('#edit_weight').value = d.gradeWeight;
            editGradeModal.querySelector('#edit_category_id').value = d.categoryId;
            editGradeModal.querySelector('#edit_comment').value = d.comment;
            editGradeModal.classList.add('active');
        }));

        const addNoteModal = document.getElementById('addNoteModal');
        if (addNoteModal) document.querySelectorAll('.add-note-btn').forEach(b => b.addEventListener('click', function() {
            addNoteModal.querySelector('#note_modal_student_id').value = this.dataset.studentId;
            addNoteModal.querySelector('#note-modal-student-name').innerText = this.dataset.studentName;
            addNoteModal.classList.add('active');
        }));

        const editNoteModal = document.getElementById('editNoteModal');
        if (editNoteModal) document.querySelectorAll('.note-item-teacher-view:not(.readonly)').forEach(n => n.addEventListener('click', function() {
            const d = this.dataset;
            editNoteModal.querySelector('#edit_note_id').value = d.noteId;
            editNoteModal.querySelector('#delete_note_id').value = d.noteId;
            editNoteModal.querySelector('#edit-note-student-name').innerText = d.studentName;
            editNoteModal.querySelector('#edit_note_content').value = d.noteContent;
            const r = editNoteModal.querySelector(`input[name="note_type"][value="${d.noteType}"]`);
            if (r) r.checked = true;
            editNoteModal.classList.add('active');
        }));
    });
</script>
</body>

</html>