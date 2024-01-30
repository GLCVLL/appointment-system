import Chart from 'chart.js/auto';

//*** FUNCTIONS ***//
const initChart = (title, labels, data) => {
    return new Chart(ctx, {
        type: 'line',
        data: {
            labels,
            datasets: [{
                label: title,
                data,
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}


//*** DATA ***//
// New Clients Chart
const ctx = document.getElementById('chart-users');
const clientLabels = JSON.parse(ctx.dataset.labels);
const clientsData = JSON.parse(ctx.dataset.values);

initChart('# of Clients', clientLabels, clientsData);
