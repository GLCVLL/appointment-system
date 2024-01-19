/**
 * Utility for getting a formatted date string from a date
 * 
 * [ Available formats: m,d,Y,H,i,s ]
 * 
 * @param {Date} date - a date object to format
 * @param {string} format - a format string
 * @returns {string} a formatted date string
 */
const formatDate = (date = new Date(), format = 'Y-m-d') => {

    const map = {
        'm': date.getMonth() + 1,
        'd': date.getDate(),
        'Y': date.getFullYear(),
        'H': date.getHours(),
        'i': date.getMinutes(),
        's': date.getSeconds()
    };

    return format.replace(/m|d|Y|H|i|s/gi, matched => map[matched].toString().padStart(2, '0'));
}


/**
 * Get a time array from start to end incremented by an interval in minutes
 * and with an optional pause
 * 
 * @param {string} start - start time string
 * @param {string} end - end time string
 * @param {number | 30} interval - incremental interval in minutes
 * @param {string | null} pauseStart - optional pause start
 * @param {string | null} pauseEnd - optional pause end
 * @returns {
 * value: string
 * text: string
 * }
 */
const getTimePeriod = (start, end, interval = 30, pauseStart = null, pauseEnd = null) => {

    // Validation
    if (end <= start || interval <= 0) return [];

    // Split time strings
    const startTimeArray = start.split(':');
    const endTimeArray = end.split(':');
    const pauseStartArray = pauseStart?.split(':');
    const pauseEndArray = pauseEnd?.split(':');

    // Set datetime vars [timestamps]
    let currentTime = new Date().setHours(...startTimeArray);
    const pauseStartTime = pauseStartArray ? new Date().setHours(...pauseStartArray) : null;
    const pauseEndTime = pauseEndArray ? new Date().setHours(...pauseEndArray) : null;
    const endTime = new Date().setHours(...endTimeArray);

    // Create time intervals
    let timeArray = [];
    while (currentTime <= endTime) {

        // Pause Time Check
        if (pauseStartTime && pauseEndTime && currentTime < pauseEndTime && currentTime > pauseStartTime) {
            currentTime = pauseEndTime;
        } else {

            // Convert to date
            const currentDate = new Date(currentTime);

            // Push time to array
            timeArray.push({
                value: currentDate.toTimeString().substring(0, 5), // "HH:MM"
                text: currentDate.toTimeString().substring(0, 5) // "HH:MM"
            });

            // Increment by interval
            currentTime = currentDate.setMinutes(currentDate.getMinutes() + interval);
        }
    }

    return timeArray;
}


export { formatDate, getTimePeriod };