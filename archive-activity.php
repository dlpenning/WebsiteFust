<?php 
fust_set_title('Activities');

$activities = array(
    0 => [
        'title' => 'Test activity 1',
        'date' => '2024-02-28',
    ],
    1 => [
        'title' => 'Test activity 2',
        'date' => '2024-02-28',
    ],
    2 => [
        'title' => 'Test activity 3',
        'date' => '2024-03-02',
    ],
    3 => [
        'title' => 'Test activity 4',
        'date' => '2024-03-03',
    ],
    4 => [
        'title' => 'Test activity 5',
        'date' => '2024-03-04',
    ],
    5 => [
        'title' => 'Test activity 6',
        'date' => '2024-03-08',
    ],
    6 => [
        'title' => 'Test activity 7',
        'date' => '2024-03-10',
    ],
)
?>

<?= get_template_part('templates/header') ?>


<main role="main" class="no-banner">
    <section class="archive-content activities reduced-top-space">
        <div class="section-content">
            <h1 class="title section-title">Activities</h1>

            <div class="activity-overview">
                <div class="activity-list">
                <?php foreach ($activities as $activity) { ?>

                <section class="container-wrapper">
                    <h1 class="title section-title"><?= $activity['title'] ?></h1>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Eius in sint sed tempora a totam, maxime numquam expedita laborum illum!</p>
                </section>

                <?php } ?>
                </div>

                <div class="activity-calendar calendar">
                    <header class="action-bar">
                        <span class="calendar-label" id="activity-calendar-month-label">January 2024</span>
                        <div style="display: none;">
                            <span class="prev"><i class="fas fa-chevron-left"></i></span>
                            <span class="next"><i class="fas fa-chevron-right"></i></span>
                        </div>
                    </header>
                    <div class="calendar-header">
                        <span>M</span>
                        <span>T</span>
                        <span>W</span>
                        <span>T</span>
                        <span>F</span>
                        <span>S</span>
                        <span>S</span>
                    </div> 
                    <div class="calendar-grid" id="activity-calendar-grid"></div>
                </div>
            </div>
    </section>
</main>

<script>
const calendarData = <?= json_encode($activities) ?>

const cellClickListener = (cell) => {
    // Update selected grid item
    const currentActive = document.querySelector('.calendar-grid .selected')

    if (currentActive) {
        currentActive.classList.remove('selected')
    }

    cell.classList.add('selected')
}

function selectDate(date) {
    const dateElement = document.querySelector(`[data-date='${date.toLocaleDateString('nl-NL')}']`)
    cellClickListener(dateElement)
}

function checkDateMatch(date1, date2) {
  return date1.getDate() == date2.getDate() &&
    date1.getMonth() == date2.getMonth() &&
    date1.getFullYear() == date2.getFullYear()
}

function initCalendar() {
    const today = new Date()
    const calendarLabel = document.getElementById('activity-calendar-month-label')
    calendarLabel.innerText = today.toLocaleDateString('en-EN', { month: 'long', year: 'numeric' })

    const dates = []
    const numDaysInMonth = (y, m) => new Date(y, m, 0).getDate()
    const numDaysInPrevMonth = numDaysInMonth(today.getFullYear(), today.getMonth())
    const numDaysInCurrMonth = numDaysInMonth(today.getFullYear(), today.getMonth() + 1)
    const startingDayOfCurrMonth = new Date(today.getFullYear(), today.getMonth(), 0).getDay()

    // Loop over every virtual cell and add the appropriate value to the array
    for (let i = 0; i < 35; i++) {
        if (i < startingDayOfCurrMonth) {
            const day = numDaysInPrevMonth - startingDayOfCurrMonth + 1 + i
            dates.push({
                date: new Date(today.getFullYear(), today.getMonth() - 1, day),
                otherMonth: true,
            })
        } else if (i >= startingDayOfCurrMonth && i < numDaysInCurrMonth + startingDayOfCurrMonth) {
            const day = i + 1 - startingDayOfCurrMonth
            dates.push({
                date: new Date(today.getFullYear(), today.getMonth(), day),
                otherMonth: false,
            })
        } else {
            const day = i % (numDaysInCurrMonth + startingDayOfCurrMonth) + 1
            dates.push({
                date: new Date(today.getFullYear(), today.getMonth() + 1, day),
                otherMonth: true,
            })
        }
    }

    // Clear calendar-grid inner html
    const calendarGrid = document.getElementById('activity-calendar-grid')
    calendarGrid.innerHTML = ''

    // Construct DOM elements
    for (const date of dates) {
        const el = document.createElement("div")

        el.setAttribute('data-date', date.date.toLocaleDateString('nl-NL'))
        el.addEventListener('click', () => cellClickListener(el))

        if (date.otherMonth) {
            el.classList.add('other-month')
        }

        calendarGrid.appendChild(el)

        // Append activity indicators
        const currentData = calendarData.filter(item => checkDateMatch(new Date(item.date), date.date))
        
        let indicatorHTML = ''
        currentData.forEach(() => indicatorHTML += '<div></div>')
        el.innerHTML = `<span>${date.date.getDate()}</span><div class="activity-indicators">${indicatorHTML}</div>`
    }

    selectDate(new Date(calendarData[0].date))

    // Highlight today
    const todayEl = document.querySelector(`[data-date='${new Date().toLocaleDateString('nl-NL')}']`)
    todayEl.classList.add('today')
}


initCalendar()

</script>

<?= get_template_part('templates/footer') ?>