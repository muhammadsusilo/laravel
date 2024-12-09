<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemilih Rentang Tanggal</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        #date-range-picker {
            position: relative;
            display: inline-block;
        }

        input {
            padding: 10px;
            margin: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            cursor: pointer;
        }

        #calendar-container {
            position: absolute;
            top: 40px;
            left: 0;
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 10px;
            z-index: 1000;
        }

        .hidden {
            display: none;
        }

        #calendar {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
        }

        #calendar div {
            width: 100%;
            text-align: center;
            padding: 5px;
            box-sizing: border-box;
            cursor: pointer;
        }

        #calendar .selected {
            background-color: rgb(0, 128, 0);
            color: white;
        }

        #calendar .in-range {
            background-color: rgb(125, 199, 125);
            color: white;
        }

        #calendar .other-month {
            color: gray;
        }

        #calendar .today {
            background-color: rgb(0, 128, 0);
            color: white;
        }

        button {
            margin: 5px;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        #cancel-button {
            background-color: #f44336;
            color: white;
        }

        #apply-button {
            background-color: #4CAF50;
            color: white;
        }

        .nav-buttons {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .nav-buttons button {
            width: 20%;
        }

        .header {
            text-align: center;
            width: 60%;
        }

        .day-names {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            text-align: center;
            margin-bottom: 10px;
        }

        .day-names li {
            list-style: none;
        }
    </style>
</head>

<body>
    <div id="date-range-picker">
        <input type="text" id="date-range" placeholder="Pilih Rentang Tanggal" readonly>
        <div id="calendar-container" class="hidden">
            <div class="nav-buttons">
                <button id="prev-button">&lt;</button>
                <div class="header" id="current-month-year">Mei 2024</div>
                <button id="next-button">&gt;</button>
            </div>
            <ul class="day-names">
                <li>MIN</li>
                <li>SEN</li>
                <li>SEL</li>
                <li>RAB</li>
                <li>KAM</li>
                <li>JUM</li>
                <li>SAB</li>
            </ul>
            <div id="calendar"></div>
            <button id="cancel-button">Batal</button>
            <button id="apply-button">Selesai</button>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dateRangeInput = document.getElementById('date-range');
            const calendarContainer = document.getElementById('calendar-container');
            const calendar = document.getElementById('calendar');
            const cancelButton = document.getElementById('cancel-button');
            const applyButton = document.getElementById('apply-button');
            const prevButton = document.getElementById('prev-button');
            const nextButton = document.getElementById('next-button');
            const currentMonthSpan = document.getElementById('current-month-year');

            let startDate = null;
            let endDate = null;
            let currentYear = new Date().getFullYear();
            let currentMonth = new Date().getMonth();

            dateRangeInput.addEventListener('click', toggleCalendar);
            cancelButton.addEventListener('click', function() {
                calendarContainer.classList.add('hidden');
            });

            applyButton.addEventListener('click', function() {
                if (startDate && endDate) {
                    dateRangeInput.value = `${formatDate(startDate)} - ${formatDate(endDate)}`;
                } else {
                    dateRangeInput.value = '';
                }
                calendarContainer.classList.add('hidden');
            });

            prevButton.addEventListener('click', function() {
                currentMonth--;
                if (currentMonth < 0) {
                    currentMonth = 11;
                    currentYear--;
                }
                renderCalendar();
            });

            nextButton.addEventListener('click', function() {
                currentMonth++;
                if (currentMonth > 11) {
                    currentMonth = 0;
                    currentYear++;
                }
                renderCalendar();
            });

            function toggleCalendar() {
                calendarContainer.classList.toggle('hidden');
                if (!calendarContainer.classList.contains('hidden')) {
                    renderCalendar();
                }
            }

            function renderCalendar() {
                calendar.innerHTML = '';
                updateHeader();
                const today = new Date();
                const firstDay = new Date(currentYear, currentMonth, 1).getDay();
                const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();

                const prevMonthDays = new Date(currentYear, currentMonth, 0).getDate();
                for (let i = firstDay - 1; i >= 0; i--) {
                    const dayElement = document.createElement('div');
                    dayElement.textContent = prevMonthDays - i;
                    dayElement.classList.add('other-month');
                    calendar.appendChild(dayElement);
                }

                for (let i = 1; i <= daysInMonth; i++) {
                    const dayElement = document.createElement('div');
                    dayElement.textContent = i;

                    dayElement.addEventListener('click', function() {
                        selectDate(i);
                    });

                    const date = new Date(currentYear, currentMonth, i);

                    if (isToday(date, today)) {
                        dayElement.classList.add('today');
                    } else if (isSelectedDate(date)) {
                        dayElement.classList.add('selected');
                    } else if (isInRange(date)) {
                        dayElement.classList.add('in-range');
                    }

                    calendar.appendChild(dayElement);
                }

                const totalCells = firstDay + daysInMonth;
                const nextMonthDays = 42 - totalCells;
                for (let i = 1; i <= nextMonthDays; i++) {
                    const dayElement = document.createElement('div');
                    dayElement.textContent = i;
                    dayElement.classList.add('other-month');
                    calendar.appendChild(dayElement);
                }
            }

            function isToday(date, today) {
                return date.toDateString() === today.toDateString();
            }

            function isSelectedDate(date) {
                return date.toDateString() === (startDate ? startDate.toDateString() : '') || date
                    .toDateString() === (endDate ? endDate.toDateString() : '');
            }

            function isInRange(date) {
                if (!startDate || !endDate) return false;
                return date >= startDate && date <= endDate;
            }

            function selectDate(day) {
                const date = new Date(currentYear, currentMonth, day);
                if (!startDate || (startDate && endDate)) {
                    startDate = date;
                    endDate = null;
                } else if (date < startDate) {
                    endDate = startDate;
                    startDate = date;
                } else {
                    endDate = date;
                }
                renderCalendar();
            }

            function formatDate(date) {
                if (!date) return '';
                const day = date.getDate();
                const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus',
                    'September', 'Oktober', 'November', 'Desember'
                ];
                const month = monthNames[date.getMonth()];
                const year = date.getFullYear();
                return `${day} ${month}`;
            }

            function updateHeader() {
                const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus',
                    'September', 'Oktober', 'November', 'Desember'
                ];
                currentMonthSpan.textContent = `${monthNames[currentMonth]} ${currentYear}`;
            }
            renderCalendar();
        });
    </script>
</body>

</html>
