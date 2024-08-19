<?php 
fust_set_title('Activities');

function render_activity_time($id) {
    $start_hours = get_post_meta($id, 'start_hours', true);
    $start_minutes = get_post_meta($id, 'start_minutes', true);
    $end_hours = get_post_meta($id, 'end_hours', true);
    $end_minutes = get_post_meta($id, 'end_minutes', true);

    return sprintf('%02d:%02d - %02d:%02d', $start_hours, $start_minutes, $end_hours, $end_minutes);
}

$activities = fust_get_activities();

// Initialize future and past activities arrays
$activities_future = array();
$activities_past = array();

// Loop through all activities and classify them into future or past
foreach ($activities as $activity) {
    // Retrieve the date meta field, assume it's stored as 'Y/m/d'
    $date = get_post_meta($activity->ID, 'date', true);
    $activity_timestamp = strtotime($date);

    if ($activity_timestamp >= strtotime(date('Y/m/d'))) {
        $activities_future[] = $activity;
    } else {
        $activities_past[] = $activity;
    }
}

// Sort future activities: closest to furthest
usort($activities_future, function($a, $b) {
    $date_a = strtotime(get_post_meta($a->ID, 'date', true));
    $date_b = strtotime(get_post_meta($b->ID, 'date', true));
    return $date_a - $date_b;
});

// Sort past activities: most recent to oldest
usort($activities_past, function($a, $b) {
    $date_a = strtotime(get_post_meta($a->ID, 'date', true));
    $date_b = strtotime(get_post_meta($b->ID, 'date', true));
    return $date_b - $date_a;
});



// $activities_future = [];
// $activities_past = [];

// foreach ($activities as $activity) {
//     $now = intval(date("Ymd"));
//     $date = get_post_meta($activity->ID, 'date', true);
//     $components = explode('/', $date);

//     if (!count($components) == 3) {
//         break;
//     }

//     // Format date as {YYYY}{MM}{dd} for sorting purposes
//     $date_formatted = $components[2] . $components[1] . $components[0];

//     if ( isset( $ordered_activities[$date_formatted] )) {
//         array_push($ordered_activities[$date_formatted], $activity);
//     } else {
//         $ordered_activities[$date_formatted] = $activity;
//     }
// }

// // Sort the events
// ksort($ordered);

// // The keys are the dates, formatted as "Ymd"
// $dates = array_keys( $ordered );

function fust_render_activity($activity, $date) {
    $id = $activity->ID;

    // Render time
    $time = render_activity_time($id);

    // Render date
    $d = DateTime::createFromFormat('Ymd', $date);
    if ($d === false) {
        echo 'Error: Incorrect date format!</br>';
    } else {
        setlocale(LC_TIME, "nl_NL");
        $date_day = strftime('%d', $d->getTimestamp());
        $date_month_short = strftime('%b', $d->getTimestamp());
        $date_stylized = strftime('%d %B %Y', $d->getTimestamp());
    }
    ?>
    <h1>Test</h1>
    <?php
}
?>

<?= get_template_part('templates/header') ?>


<main role="main" class="no-banner">
    <section class="archive-content activities reduced-top-space">
        <div class="section-content">
            <h1 class="title section-title">Activities</h1>

            <div class="activity-overview">
                <div class="activity-list">

                <?php

                $i = 0;

                foreach ($ordered_activities as $activity) {
                    $date = $dates[i];
                    $now = intval(date("Ymd"));
                }

                ?>
                <?php if (have_posts()) {
                    while (have_posts()) : the_post(); ?>

                        <div class="activity-list-item">
                            <h1><a class="link white" href="<?= service_get_the_custom_permalink($post) ?>"><?= get_the_title($p) ?></h1></a>
                            <p><?= FUST_Activity::formatLocaleDate(get_post_meta($post->ID, 'date', true), 'en') ?></p>
                            <p class="meta"><?= render_activity_time($post->ID) ?></p>
                            <div class="activity-tags">
                                <?php
                                $tags = get_post_meta($post->ID, 'tags', true);
                                $tags_array = FUST_Activity::parse_tags($tags);

                                foreach ($tags_array as $tag) { ?>
                                    <span><?= $tag ?></span>
                                <?php } ?>
                            </div>
                            <p class="activity-list-item-subtitle"><?= get_excerpt(100, $p) ?></p>
                        </div>

                    <?php endwhile;
                } else {
                    echo 'No activities planned currently, please come back later!';
                } ?>
                </div>

                <?php /*
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
                */ ?>
            </div>
    </section>
</main>

<?php /*
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
    const dateElement = document.querySelector(`[data-date='${date.toLocaleDateString('en-GB')}']`)
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

        el.setAttribute('data-date', date.date.toLocaleDateString('en-GB'))
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
    const todayEl = document.querySelector(`[data-date='${new Date().toLocaleDateString('en-GB')}']`)
    todayEl.classList.add('today')
}


initCalendar()
</script>
*/ ?>

<?= get_template_part('templates/footer') ?>