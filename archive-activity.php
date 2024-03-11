<?php 
fust_set_title('Activities');

function render_activity_time($id) {
    $start_hours = str_pad(get_post_meta($id, 'start_hours', true), 2, '0');
    $start_minutes = str_pad(get_post_meta($id, 'start_minutes', true), 2, '0');
    $end_hours = str_pad(get_post_meta($id, 'end_hours', true), 2, '0');
    $end_minutes = str_pad(get_post_meta($id, 'end_minutes', true), 2, '0');

    return $start_hours . ':' . $start_minutes . ' - ' . $end_hours . ':' . $end_minutes;
}

$activities = fust_get_activities();
?>

<?= get_template_part('templates/header') ?>


<main role="main" class="no-banner">
    <section class="archive-content activities reduced-top-space">
        <div class="section-content">
            <h1 class="title section-title">Activities</h1>

            <div class="activity-overview">
                <div class="activity-list">
                <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

                    <section class="container-wrapper activity-list-item">
                        <div class="activity-list-item-left">
                            <h1><?= render_activity_time($post->ID) ?></h1>
                            <p><?= get_post_meta($post->ID, 'date', true) ?></p>
                        </div>
                        <div class="activity-list-item-inner">
                            <h1 class="title"><a class="link white" href="<?= service_get_the_custom_permalink($post) ?>"><?= get_the_title($p) ?></h1></a>
                            <div class="activity-tags">
                                <?php
                                $tags = get_post_meta($post->ID, 'tags', true);
                                $tags_array = FUST_Activity::parse_tags($tags);

                                foreach ($tags_array as $tag) { ?>
                                    <span><?= $tag ?></span>
                                <?php } ?>
                            </div>
                            <p class="activity-list-item-subtitle truncate"><?= get_excerpt(100, $p) ?></p>
                        </div>
                    </section>

                <?php endwhile; endif; ?>
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

console.log(calendarData)

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
    if (dateElement) {
        cellClickListener(dateElement)
    }
}

function checkDateMatch(date1, date2) {
    console.log(date1)
    console.log(date2)
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