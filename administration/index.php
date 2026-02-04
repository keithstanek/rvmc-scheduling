<?php
$title = "Parents Admin";
$breadcrumb = "Parents";

require dirname(__FILE__) . "/page-includes/header.php";
require dirname(__FILE__) . "/page-includes/nav-bar.php";
require dirname(__FILE__) . "/page-includes/page-wrapper-start.php";

$LESSONS_API_URI = "api/lessons.php";
$STUDENTS_API_URI = "api/students.php";
$CLASSES_API_URI = "api/courses.php";
$TEACHERS_API_URI = "api/teachers.php";

?>
    <title>Music Lesson Scheduler</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #0d6efd;
            --border-color: #dee2e6;
            --hover-bg: #f8f9fa;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
        }

        .calendar-header {
            background: white;
            padding: 20px;
            border-bottom: 2px solid var(--border-color);
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .view-tabs {
            border-bottom: 2px solid var(--border-color);
            margin-bottom: 0;
        }

        .view-tabs .nav-link {
            color: #6c757d;
            border: none;
            border-bottom: 3px solid transparent;
            padding: 12px 24px;
            font-weight: 500;
        }

        .view-tabs .nav-link.active {
            color: var(--primary-color);
            border-bottom-color: var(--primary-color);
            background: transparent;
        }

        .calendar-container {
            background: white;
            margin: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            border-left: 1px solid var(--border-color);
            border-top: 1px solid var(--border-color);
        }

        .calendar-day-header {
            background: #f8f9fa;
            padding: 12px;
            text-align: center;
            font-weight: 600;
            border-right: 1px solid var(--border-color);
            border-bottom: 1px solid var(--border-color);
            color: #495057;
        }

        .calendar-day {
            min-height: 120px;
            padding: 8px;
            border-right: 1px solid var(--border-color);
            border-bottom: 1px solid var(--border-color);
            background: white;
            position: relative;
        }

        .calendar-day.other-month {
            background: #fafafa;
            color: #adb5bd;
        }

        .calendar-day.today {
            background: #e7f3ff;
        }

        .day-number {
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 4px;
        }

        .lesson-item {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 4px 8px;
            margin-bottom: 4px;
            border-radius: 4px;
            font-size: 12px;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .lesson-item:hover {
            transform: translateX(2px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        .lesson-item.monthly {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .lesson-item.weekly {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        .lesson-item.biweekly {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        }

        .lesson-item.single {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        }

        .week-view-container, .day-view-container {
            padding: 20px;
        }

        .time-slot {
            display: flex;
            border-bottom: 1px solid var(--border-color);
            min-height: 60px;
        }

        .time-label {
            width: 80px;
            padding: 10px;
            font-size: 12px;
            color: #6c757d;
            border-right: 1px solid var(--border-color);
        }

        .time-content {
            flex: 1;
            padding: 10px;
            position: relative;
        }

        .lesson-block {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 8px;
            border-radius: 4px;
            margin-bottom: 8px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .lesson-block:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            transform: translateY(-2px);
        }

        .modal-header {
            background: var(--primary-color);
            color: white;
        }

        .modal-header .btn-close {
            filter: invert(1);
        }

        .add-lesson-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            z-index: 1000;
        }

        .badge-frequency {
            font-size: 10px;
            padding: 2px 6px;
            margin-left: 4px;
        }

        .list-view-item {
            border-left: 4px solid var(--primary-color);
            margin-bottom: 12px;
            padding: 12px;
            background: white;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .list-view-item:hover {
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transform: translateX(4px);
        }
    </style>

    <!-- Header -->
    <div class="calendar-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-0">Music Lesson Scheduler</h2>
                <p class="text-muted mb-0" id="teacherName">
                    <select id="teacherSelect" class="form-select mt-1">
                        <option value="">Select a teacher</option>
                    </select>
                </p>
            </div>
            <div class="d-flex gap-2">
                <h4 class="mb-0 mx-3 align-self-center" id="currentPeriod"></h4>
                <button class="btn btn-outline-primary" id="todayBtn">
                    <i class="bi bi-calendar-day"></i> Today
                </button>
                <button class="btn btn-outline-secondary" id="prevBtn">
                    <i class="bi bi-chevron-left"></i>
                </button>
                <button class="btn btn-outline-secondary" id="nextBtn">
                    <i class="bi bi-chevron-right"></i>
                </button>
            </div>
        </div>
        <ul class="nav view-tabs mt-3" id="viewTabs">
            <li class="nav-item">
                <a class="nav-link active" href="#" data-view="month">Month</a>
            </li>
            <!-- <li class="nav-item">
                <a class="nav-link" href="#" data-view="week">Week</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" data-view="day">Day</a>
            </li> -->
        </ul>
    </div>

    <!-- Calendar Container -->
    <div class="calendar-container">
        <!-- Month View -->
        <div id="monthView" class="view-content">
            <div class="calendar-grid" id="calendarGrid"></div>
        </div>

        <!-- Week View -->
        <div id="weekView" class="view-content" style="display:none;">
            <div class="week-view-container" id="weekViewContainer"></div>
        </div>

        <!-- Day View -->
        <div id="dayView" class="view-content" style="display:none;">
            <div class="day-view-container" id="dayViewContainer"></div>
        </div>
    </div>

    <!-- Floating Add Button -->
    <button class="btn btn-primary add-lesson-btn" onclick="openLessonModal()">
        <i class="bi bi-plus-lg fs-4"></i>
    </button>

    <!-- Lesson Modal -->
    <div class="modal fade" id="lessonModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="lessonModalTitle">Add Lesson</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="lessonForm">
                        <input type="hidden" id="lessonId">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Student *</label>
                                <select class="form-select" id="studentSelect" required>
                                    <option value="">Select Student</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Class *</label>
                                <select class="form-select" id="classSelect" required>
                                    <option value="">Select Class</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Date *</label>
                                <input type="date" class="form-control" id="lessonDate" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Time *</label>
                                <input type="time" class="form-control" id="lessonTime" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Duration (minutes) *</label>
                                <input type="number" class="form-control" id="lessonDuration" value="30" min="15" step="15" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Frequency *</label>
                                <select class="form-select" id="lessonFrequency" required>
                                    <option value="single">Single Lesson</option>
                                    <option value="weekly">Weekly</option>
                                    <option value="biweekly">Bi-Weekly</option>
                                    <option value="monthly">Monthly</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Notes</label>
                            <textarea class="form-control" id="lessonNotes" rows="3"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Payment Collected</label>
                                <input type="number" class="form-control" id="paymentAmount" step="0.01" min="0" placeholder="0.00">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Payment Method</label>
                                <select class="form-select" id="paymentMethod">
                                    <option value="">No Payment</option>
                                    <option value="cash">Cash</option>
                                    <option value="check">Check</option>
                                    <option value="debit">Debit Card</option>
                                    <option value="ach">ACH Transfer</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3" id="applyToAllDiv" style="display: none;">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="applyToAll">
                                <label class="form-check-label" for="applyToAll"><b>Apply this update to all lessons in this series</b></label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="saveLesson()">
                        <i class="bi bi-save"></i> Save
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="deleteBtn" style="display:none;" onclick="deleteLesson()">
                        <i class="bi bi-trash"></i> Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // State Management
        let currentView = 'month';
        let currentDate = new Date();
        let lessons = [];
        let students = [];
        let parents = [];
        let courses = [];
        let originalCourses = [];
        let teachers = [];
        let teacherId = null;

        // Initialize
        document.addEventListener('DOMContentLoaded', async function() {
            await initializeData();
            setupEventListeners();
            renderCalendar();
        });

        async function initializeData() {
            try {
                const [
                    studentsResp, 
                    coursesResp, 
                    teachersResp
                ] = await Promise.all([
                    fetch('<?= $STUDENTS_API_URI ?>').then(res => res.json()),
                    fetch('<?= $CLASSES_API_URI ?>').then(res => res.json()),
                    fetch('<?= $TEACHERS_API_URI ?>').then(res => res.json())
                ]);

                students = Array.isArray(studentsResp.students) ? studentsResp.students : [];
                courses = Array.isArray(coursesResp.courses) ? coursesResp.courses : [];
                teachers = Array.isArray(teachersResp.teachers) ? teachersResp.teachers : [];
                originalCourses = [...courses];
            } catch (e) {
                students = [];
                courses = [];
            }
            populateDropdowns();
        }

        async function populateDropdowns() {
            const studentSelect = document.getElementById('studentSelect');
            const teacherSelect = document.getElementById('teacherSelect');
            
            students.forEach(student => {
                const option = document.createElement('option');
                option.value = student.id;
                option.textContent = `${student.first_name} ${student.last_name}`;
                studentSelect.appendChild(option);
            });

            teachers.forEach(teacher => {
                const option = document.createElement('option');
                option.value = teacher.id;
                option.textContent = `${teacher.first_name} ${teacher.last_name}`;
                teacherSelect.appendChild(option);
            });

            teacherSelect.addEventListener("change", async () => {
                const selectedId = parseInt(teacherSelect.value);
                const selectedTeacher = teachers.find(t => t.id === selectedId);

                if (selectedTeacher) {
                    teacherId = selectedTeacher.id;
                    filterCourses(selectedTeacher.id);
                    await getLessonsByTeacherId(selectedTeacher.id);
                }
            });
        }

        function filterCourses(teacherId) {
            teacher = teachers.find(t => t.id === teacherId);
            courses = teacher.courses;
            classSelect.innerHTML = ''; // Clear existing options
            classSelect.innerHTML = `<option value="">Select Class</option>`;
            courses.forEach(course => {
                const option = document.createElement('option');
                option.value = course.id;
                option.textContent = course.name;
                classSelect.appendChild(option);
            });
        }

        async function getLessonsByTeacherId(teacherId) {
            try {
                const response = await fetch(`<?= $LESSONS_API_URI ?>?teacher_id=${teacherId}`);
                const data = await response.json();
                lessons = Array.isArray(data.lessons) ? data.lessons : [];
                renderCalendar();
            } catch (e) {
                lessons = [];
            }
        }

        function setupEventListeners() {
            document.getElementById('todayBtn').addEventListener('click', () => {
                currentDate = new Date();
                renderCalendar();
            });

            document.getElementById('prevBtn').addEventListener('click', () => {
                navigatePeriod(-1);
            });

            document.getElementById('nextBtn').addEventListener('click', () => {
                navigatePeriod(1);
            });

            document.querySelectorAll('#viewTabs .nav-link').forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    switchView(link.dataset.view);
                });
            });
        }

        function navigatePeriod(direction) {
            if (currentView === 'month') {
                currentDate.setMonth(currentDate.getMonth() + direction);
            } else if (currentView === 'week') {
                currentDate.setDate(currentDate.getDate() + (7 * direction));
            } else if (currentView === 'day') {
                currentDate.setDate(currentDate.getDate() + direction);
            }
            renderCalendar();
        }

        function switchView(view) {
            currentView = view;
            document.querySelectorAll('#viewTabs .nav-link').forEach(link => {
                link.classList.toggle('active', link.dataset.view === view);
            });
            document.querySelectorAll('.view-content').forEach(content => {
                content.style.display = 'none';
            });
            document.getElementById(view + 'View').style.display = 'block';
            renderCalendar();
        }

        function renderCalendar() {
            updatePeriodLabel();
            if (currentView === 'month') {
                renderMonthView();
            } else if (currentView === 'week') {
                renderWeekView();
            } else if (currentView === 'day') {
                renderDayView();
            }
        }

        function updatePeriodLabel() {
            const label = document.getElementById('currentPeriod');
            if (currentView === 'month') {
                label.textContent = currentDate.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
            } else if (currentView === 'week') {
                const weekStart = getWeekStart(currentDate);
                const weekEnd = new Date(weekStart);
                weekEnd.setDate(weekEnd.getDate() + 6);
                label.textContent = `${weekStart.toLocaleDateString('en-US', { month: 'short', day: 'numeric' })} - ${weekEnd.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}`;
            } else {
                label.textContent = currentDate.toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' });
            }
        }

        function renderMonthView() {
            const grid = document.getElementById('calendarGrid');
            grid.innerHTML = '';

            // Headers
            const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            days.forEach(day => {
                const header = document.createElement('div');
                header.className = 'calendar-day-header';
                header.textContent = day;
                grid.appendChild(header);
            });

            // Days
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();
            const firstDay = new Date(year, month, 1);
            const lastDay = new Date(year, month + 1, 0);
            const startDate = new Date(firstDay);
            startDate.setDate(startDate.getDate() - firstDay.getDay());

            const today = new Date();
            today.setHours(0, 0, 0, 0);

            for (let i = 0; i < 42; i++) {
                const dayCell = document.createElement('div');
                dayCell.className = 'calendar-day';
                
                const cellDate = new Date(startDate);
                cellDate.setDate(cellDate.getDate() + i);
                
                if (cellDate.getMonth() !== month) {
                    dayCell.classList.add('other-month');
                }
                
                if (cellDate.toDateString() === today.toDateString()) {
                    dayCell.classList.add('today');
                }

                const dayNumber = document.createElement('div');
                dayNumber.className = 'day-number';
                dayNumber.textContent = cellDate.getDate();
                dayCell.appendChild(dayNumber);

                // Add lessons for this day
                const dayLessons = getLessonsForDate(cellDate);
                dayLessons.forEach(lesson => {
                    const lessonItem = createLessonItem(lesson);
                    dayCell.appendChild(lessonItem);
                });

                grid.appendChild(dayCell);
            }
        }

        function renderWeekView() {
            const container = document.getElementById('weekViewContainer');
            container.innerHTML = '';

            const weekStart = getWeekStart(currentDate);
            const hours = Array.from({length: 14}, (_, i) => i + 8); // 8 AM to 9 PM

            hours.forEach(hour => {
                const slot = document.createElement('div');
                slot.className = 'time-slot';

                const label = document.createElement('div');
                label.className = 'time-label';
                label.textContent = formatHour(hour);
                slot.appendChild(label);

                const content = document.createElement('div');
                content.className = 'time-content';

                // Check for lessons in this hour
                for (let day = 0; day < 7; day++) {
                    const checkDate = new Date(weekStart);
                    checkDate.setDate(checkDate.getDate() + day);
                    
                    const dayLessons = getLessonsForDate(checkDate);
                    dayLessons.forEach(lesson => {
                        const lessonHour = parseInt(lesson.time.split(':')[0]);
                        if (lessonHour === hour) {
                            const block = createLessonBlock(lesson);
                            content.appendChild(block);
                        }
                    });
                }

                slot.appendChild(content);
                container.appendChild(slot);
            });
        }

        function renderDayView() {
            const container = document.getElementById('dayViewContainer');
            container.innerHTML = '';

            const hours = Array.from({length: 14}, (_, i) => i + 8);

            hours.forEach(hour => {
                const slot = document.createElement('div');
                slot.className = 'time-slot';

                const label = document.createElement('div');
                label.className = 'time-label';
                label.textContent = formatHour(hour);
                slot.appendChild(label);

                const content = document.createElement('div');
                content.className = 'time-content';

                const dayLessons = getLessonsForDate(currentDate);
                dayLessons.forEach(lesson => {
                    const lessonHour = parseInt(lesson.time.split(':')[0]);
                    if (lessonHour === hour) {
                        const block = createLessonBlock(lesson);
                        content.appendChild(block);
                    }
                });

                slot.appendChild(content);
                container.appendChild(slot);
            });
        }

        function getLessonsForDate(date) {
            const dateStr = date.toISOString().split('T')[0];
            return lessons.filter(lesson => lesson.date === dateStr);
        }

        function createLessonItem(lesson) {
            const item = document.createElement('div');
            item.className = `lesson-item ${lesson.frequency}`;
            item.innerHTML = `
                <div><strong>${lesson.time}</strong> ${lesson.student_name}</div>
                <div style="font-size: 11px;">${lesson.name}</div>
            `;
            item.onclick = () => openLessonModal(lesson);
            return item;
        }

        function createLessonBlock(lesson) {
            const block = document.createElement('div');
            block.className = 'lesson-block';
            block.innerHTML = `
                <div><strong>${lesson.time}</strong> - ${lesson.student_name}</div>
                <div>${lesson.class_name}</div>
                <span class="badge badge-frequency bg-light text-dark">${lesson.frequency}</span>
            `;
            block.onclick = () => openLessonModal(lesson);
            return block;
        }

        function getWeekStart(date) {
            const d = new Date(date);
            const day = d.getDay();
            const diff = d.getDate() - day;
            return new Date(d.setDate(diff));
        }

        function formatHour(hour) {
            const ampm = hour >= 12 ? 'PM' : 'AM';
            const h = hour % 12 || 12;
            return `${h}:00 ${ampm}`;
        }

        function openLessonModal(lesson = null) {
            if (!teacherId) {
                alert('Please select a teacher first.');
                return;
            }

            lesson 
                ? document.getElementById('applyToAllDiv').style.display = 'block' 
                : document.getElementById('applyToAllDiv').style.display = 'none';

            const modal = new bootstrap.Modal(document.getElementById('lessonModal'));
            const form = document.getElementById('lessonForm');
            form.reset();

            if (lesson) {
                document.getElementById('lessonModalTitle').textContent = 'Edit Lesson';
                document.getElementById('lessonId').value = lesson.id;
                document.getElementById('studentSelect').value = lesson.student_id;
                document.getElementById('classSelect').value = lesson.course_id;
                document.getElementById('lessonDate').value = lesson.date;
                document.getElementById('lessonTime').value = convertTo24Hour(lesson.time);
                document.getElementById('lessonDuration').value = lesson.duration;
                document.getElementById('lessonFrequency').value = lesson.frequency;
                document.getElementById('lessonNotes').value = lesson.notes || '';
                document.getElementById('paymentAmount').value = lesson.payment || '';
                document.getElementById('paymentMethod').value = lesson.payment_method || '';
                document.getElementById('deleteBtn').style.display = 'block';
            } else {
                document.getElementById('lessonModalTitle').textContent = 'Add Lesson';
                document.getElementById('lessonId').value = '';
                document.getElementById('lessonDate').value = currentDate.toISOString().split('T')[0];
                document.getElementById('deleteBtn').style.display = 'none';
            }

            modal.show();
        }

        function convertTo24Hour(time12h) {
            const [time, modifier] = time12h.split(' ');
            let [hours, minutes] = time.split(':');

            if (modifier === 'PM' && hours !== '12') {
                hours = parseInt(hours, 10) + 12;
            }
            if (modifier === 'AM' && hours === '12') {
                hours = '00';
            }

            return `${hours.toString().padStart(2, '0')}:${minutes}`;
        }

        async function saveLesson() {
            const form = document.getElementById('lessonForm');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const lessonData = {
                id: document.getElementById('lessonId').value,
                student_id: document.getElementById('studentSelect').value,
                course_id: document.getElementById('classSelect').value,
                date: document.getElementById('lessonDate').value,
                time: document.getElementById('lessonTime').value,
                duration: document.getElementById('lessonDuration').value,
                frequency: document.getElementById('lessonFrequency').value,
                notes: document.getElementById('lessonNotes').value,
                payment: document.getElementById('paymentAmount').value,
                payment_method: document.getElementById('paymentMethod').value,
                teacher_id: teacherId + "",
                apply_to_all: document.getElementById('applyToAll').checked ? 1 : 0
            };

            // API call to save lesson
            const resp = await fetch('<?= $LESSONS_API_URI ?>', { method: 'POST', body: JSON.stringify(lessonData) })
            console.log('insert response', resp)
            await bootstrap.Modal.getInstance(document.getElementById('lessonModal')).hide();
            await getLessonsByTeacherId(teacherId);
        }

        async function deleteLesson() {
            if (!confirm('Are you sure you want to delete this lesson?')) return;
            const id = document.getElementById('lessonId').value;
            const apply_to_all = document.getElementById('applyToAll').checked ? 1 : 0;
            const lesson = lessons.find(l => l.id == id);
            const resp = await fetch('<?= $LESSONS_API_URI ?>', { method: 'DELETE', body: JSON.stringify({id,apply_to_all}) });
            bootstrap.Modal.getInstance(document.getElementById('lessonModal')).hide();
            await getLessonsByTeacherId(teacherId);
        }
    </script>
</body>
</html>